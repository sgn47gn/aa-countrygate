<?php

declare(strict_types=1);

/*
 * Countrygate Bundle, 47GradNord - Agentur für Internetlösungen
 *
 * @copyright  Copyright (c) 2008-2018, 47GradNord - Agentur für Internetlösungen
 * @author     47GradNord - Agentur für Internetlösungen <info@47gradnord.de>
 */

namespace AaProjects\Countrygate\Controller;

use AaProjects\Countrygate\Form\RequestTokenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends Controller implements MainActionProvidingInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function mainAction(Request $request, array $moduleSettings): Response;

    /**
     * @param string $name
     *
     * @return FormBuilderInterface
     */
    protected function createFormBuilderForContao(string $name): FormBuilderInterface
    {
        return $this->get('form.factory')->createNamedBuilder($name, FormType::class)
            ->add('REQUEST_TOKEN', RequestTokenType::class);
    }

    /**
     * @param string $step
     *
     * @return RedirectResponse
     */
    protected function redirectToStep(string $step, string $parameter = ''): RedirectResponse
    {
        $url = $GLOBALS['objPage']->getAbsoluteUrl('/' . $step);

        if ('' !== $parameter) {
            $url = $url . '?' . $parameter;
        }

        return new RedirectResponse(
            $url
        );
    }

    protected function getAvailableCountrys()
    {
        foreach($GLOBALS['TL_AA_COUNTRYS'] as $value)
        {
            dump($value);
        }
        exit;


    }
}
