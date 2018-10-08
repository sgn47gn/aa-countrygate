<?php
/**
 * GIT SCHRANKEN-MODUL for Contao Open Source CMS
 *
 * Copyright (C) 2018 47GradNord - Agentur für Internetlösungen
 *
 * @license    commercial
 * @author     Holger Neuner
 */


namespace sgn47gn\Countrygate\Controller\Module;


use sgn47gn\Countrygate\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GateController extends AbstractController
{
    /**
     * @param Request $request
     * @param array   $moduleSettings
     *
     * @return Response
     */
    public function mainAction(Request $request, array $moduleSettings): Response
    {

        // Compile
        return $this->render('@Countrygate/module/gate.html.twig', [
            'tpl' => 'tpl',
        ]);
    }

}