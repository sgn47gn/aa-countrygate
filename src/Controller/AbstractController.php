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
use Contao\PageModel;
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

    /**
     * @param int    $pageId
     * @param string $parameter
     *
     * @return RedirectResponse
     */
    protected function redirectToPage(int $pageId, string $parameter = ''): RedirectResponse
    {
        /** @var PageModel $page */
        $page = PageModel::findById($pageId);

        if (null === $page) {
            return false;
        }

        $url = $page->getAbsoluteUrl();

        if ('' !== $parameter) {
            $url = $url . '?' . $parameter;
        }

        return new RedirectResponse(
            $url
        );
    }

    /**
     * @return array
     */
    protected function getAvailableCountrys()
    {
        $r = [];

        foreach($GLOBALS['TL_AA_COUNTRYS'] as $key => $value)
        {
            $r[$value['label'][$this->getLanguage()]] = $key ;
        }

        return $r;
    }

    /**
     * @return mixed
     */
    protected function getRootFallbackLanguage()
    {
        return $GLOBALS['objPage']->rootFallbackLanguage;
    }

    /**
     * @return mixed
     */
    protected function getLanguage()
    {
        return $GLOBALS['objPage']->language;
    }
}
