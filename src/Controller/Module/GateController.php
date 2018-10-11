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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        'confirm' => 'confirm'
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

        /* @var string autoItem */
        $this->autoItem = \Input::get('auto_item');

        // Inital Request without Auto Item
        if (null === $this->autoItem) {
            return $this->redirectToStep(static::$steps['select']);
        }

        $formSelectCountry = $this->getFormSelectCountry();
        $formSelectCountry = $formSelectCountry->getForm();
        $formSelectCountry = $formSelectCountry->handleRequest($request);

        // Compile
        return $this->render('@Countrygate/module/gate.html.twig', [
            'tpl' => 'tpl',
            'formSelectCountry' => $formSelectCountry->createView()
        ]);
    }

    /**
     * @return FormBuilderInterface
     */
    protected function getFormSelectCountry()
    {
        /** @var FormBuilderInterface $form */
        $form = $this->createFormBuilderForContao('select');

        $form->add('country', ChoiceType::class, [
            'label' => $GLOBALS['TL_LANG']['CGW']['selectform_select_label'],
            'required' => true,
            'choices' => $this->getAvailableCountrys(),
        ]);

        return $form;
    }
}
