<?php

declare(strict_types=1);

/*
 * Countrygate Bundle, 47GradNord - Agentur für Internetlösungen
 *
 * @copyright  Copyright (c) 2008-2018, 47GradNord - Agentur für Internetlösungen
 * @author     47GradNord - Agentur für Internetlösungen <info@47gradnord.de>
 */

namespace AaProjects\Countrygate\Controller\Module;

use AaProjects\Countrygate\Controller\AbstractController;
use AaProjects\Countrygate\Service\SessionGate;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GateController extends AbstractController
{
    /** @var  */
    private $do;

    /** @var SessionGate */
    private $sessionGate;

    /** @var string */
    private $autoItem;

    /**
     * @var array
     */
    private static $steps = [
        'select' => 'select',
        'confirm' => 'confirm',
    ];

    /**
     * @param Request $request
     * @param array   $moduleSettings
     *
     * @return Response
     */
    public function mainAction(Request $request, array $moduleSettings): Response
    {
        $this->do = $request->get('do');
        $this->sessionGate = $this->get('countrygate.session_gate');

        /** @var [] $session */
        $session = $this->sessionGate->getSession();

        /* @var string autoItem */
        $this->autoItem = \Input::get('auto_item');

        // Inital Request without Auto Item
        if (null === $this->autoItem) {
            return $this->redirectToStep(static::$steps['select']);
        }

        // Handle Form Select Country
        $formSelectCountry = $this->getFormSelectCountry();
        $formSelectCountry = $formSelectCountry->getForm();
        $formSelectCountry = $formSelectCountry->handleRequest($request);

        if ($formSelectCountry->isSubmitted() && $formSelectCountry->isValid()) {
            $session['country'] = $formSelectCountry->getData()['country'];
            $this->sessionGate->setSession($session);

            return $this->redirectToStep($formSelectCountry->getData()['next']);
        }

        // Handle Form Confirm De / Others
        $formConfirmDeOthers = $this->getFormConfirmDeOthers();
        $formConfirmDeOthers = $formConfirmDeOthers->getForm();
        $formConfirmDeOthers = $formConfirmDeOthers->handleRequest($request);

        if ($formConfirmDeOthers->isSubmitted() && $formConfirmDeOthers->isValid()) {
            $session['passed'] = true;
            $this->sessionGate->setSession($session);

            return $this->redirectToStep($formSelectCountry->getData()['next']);
        }

        // Handle Form Confirm US
        $formConfirmUs = $this->getFormConfirmUs();
        $formConfirmUs = $formConfirmUs->getForm();
        $formConfirmUs = $formConfirmUs->handleRequest($request);

        if ($formConfirmUs->isSubmitted() && $formConfirmUs->isValid()) {
            if ($formConfirmUs->getData()['confirm_check1'] && $formConfirmUs->getData()['confirm_check2']) {
                $session['us_second'] = true;
                $this->sessionGate->setSession($session);

                return $this->redirectToStep($formConfirmUs->getData()['next']);
            }
        }

        // Handle Form Confirm US Second
        $formConfirmUsSeondstep = $this->getFormConfirmUsSecondstep();
        $formConfirmUsSeondstep = $formConfirmUsSeondstep->getForm();
        $formConfirmUsSeondstep = $formConfirmUsSeondstep->handleRequest($request);

        if ($formConfirmUsSeondstep->isSubmitted() && $formConfirmUsSeondstep->isValid()) {
            return $this->redirectToPage((int) $moduleSettings['jumpToGatePassed']);
        }

        // Handle Form Confirm Canada Australien Japan
        $formConfirmCaAuJp = $this->getFormDisclaimerCaAuJp();
        $formConfirmCaAuJp = $formConfirmCaAuJp->getForm();
        $formConfirmCaAuJp = $formConfirmCaAuJp->handleRequest($request);

        if ($formConfirmCaAuJp->isSubmitted() && $formConfirmCaAuJp->isValid()) {
            $session['passed'] = false;
            $this->sessionGate->setSession($session);

            return $this->redirectToPage((int) $moduleSettings['jumpTo']);
        }

        // Compile
        return $this->render('@Countrygate/module/gate.html.twig', [
            'tpl' => 'tpl',
            'currentStep' => $this->autoItem,
            'formSelectCountry' => ('select' === $this->autoItem) ? $formSelectCountry->createView() : false,
            'formConfirmDeOthers' => ('de' === $session['country'] || 'others' === $session['country']) ? $formConfirmDeOthers->createView() : false,
            'formConfirmUs' => ('us' === $session['country'] && !$session['us_second']) ? $formConfirmUs->createView() : false,
            'formConfirmUsSecond' => ('us' === $session['country'] && $session['us_second']) ? $formConfirmUsSeondstep->createView() : false,
            'formConfirmCaAuJp' => ('ca' === $session['country'] || 'au' === $session['country'] || 'jp' === $session['country']) ? $formConfirmCaAuJp->createView() : false,
            'select' => $this->getModuleTextContent('select', $moduleSettings),
            'disclaimer' => $this->getModuleTextContent('disclaimer', $moduleSettings),
            'disclaimer_us' => $this->getModuleTextContent('disclaimer_us', $moduleSettings),
            'disclaimer_us_checkbox_1' => $this->getModuleTextContent('disclaimer_us_checkbox_1', $moduleSettings),
            'disclaimer_us_checkbox_2' => $this->getModuleTextContent('disclaimer_us_checkbox_2', $moduleSettings),
            'disclaimer_ca_au_jp' => $this->getModuleTextContent('disclaimer_ca_au_jp', $moduleSettings),
        ]);
    }

    /**
     * @param string $label
     * @param array  $moduleSettings
     *
     * @return string
     */
    protected function getModuleTextContent(string $label, array $moduleSettings)
    {
        return \StringUtil::encodeEmail($moduleSettings['cg_' . $this->getLanguage() . '_' . $label]);
    }

    /**
     * @return FormBuilderInterface
     */
    protected function getFormSelectCountry()
    {
        /** @var FormBuilderInterface $form */
        $form = $this->createFormBuilderForContao('select');
        $form->add('next', HiddenType::class, ['data' => 'confirm']);

        $form->add('country', ChoiceType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_country'],
            'required' => true,
            'choices' => $this->getAvailableCountrys(),
        ]);

        $form->add('save', SubmitType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_save'],
            'attr' => ['class' => 'save'],
        ]);

        return $form;
    }

    /**
     * @return FormBuilderInterface
     */
    protected function getFormConfirmDeOthers()
    {
        /** @var FormBuilderInterface $form */
        $form = $this->createFormBuilderForContao('confirm_de_others');
        $form->add('next', HiddenType::class, ['data' => 'confirm']);

        $form->add('accept', SubmitType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_accept'],
            'attr' => ['class' => 'save'],
        ]);

        return $form;
    }

    /**
     * @return FormBuilderInterface
     */
    protected function getFormConfirmUs()
    {
        /** @var FormBuilderInterface $form */
        $form = $this->createFormBuilderForContao('confirm_us');
        $form->add('next', HiddenType::class, ['data' => 'confirm']);

        $form->add('confirm_check1', CheckboxType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_accept'],
            'required' => true,
        ]);

        $form->add('confirm_check2', CheckboxType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_accept'],
            'required' => true,
        ]);

        $form->add('accept', SubmitType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_accept'],
            'attr' => ['class' => 'save'],
        ]);

        return $form;
    }

    /**
     * @return FormBuilderInterface
     */
    protected function getFormConfirmUsSecondstep()
    {
        /** @var FormBuilderInterface $form */
        $form = $this->createFormBuilderForContao('confirm_us_second');
        $form->add('next', HiddenType::class, ['data' => 'confirm']);

        $form->add('accept', SubmitType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_accept'],
            'attr' => ['class' => 'save'],
        ]);

        return $form;
    }

    /**
     * @return FormBuilderInterface
     */
    protected function getFormDisclaimerCaAuJp()
    {
        /** @var FormBuilderInterface $form */
        $form = $this->createFormBuilderForContao('confirm_ca_au_jp');
        $form->add('next', HiddenType::class, ['data' => 'confirm']);

        $form->add('accept', SubmitType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_label_continue'],
            'attr' => ['class' => 'save'],
        ]);

        return $form;
    }
}
