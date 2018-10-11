<?php
/**
 * GIT SCHRANKEN-MODUL for Contao Open Source CMS
 *
 * Copyright (C) 2018 47GradNord - Agentur für Internetlösungen
 *
 * @license    commercial
 * @author     Holger Neuner
 */


namespace AaProjects\Countrygate\Controller\Module;


use AaProjects\Countrygate\Controller\AbstractController;
use AaProjects\Countrygate\Service\SessionGate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GateController extends AbstractController
{
    /** @var  */
    private $do;

    /** @var SessionGate */
    private $sessionGate;

    /**
     * @param Request $request
     * @param array   $moduleSettings
     *
     * @return Response
     */
    public function mainAction(Request $request, array $moduleSettings): Response
    {
        $this->do = $request->get('do');

        $this->sessionGate = $this->get('aa_countrygate.session_gate');

        dump($this->sessionGate->getSession()); exit;

        // Compile
        return $this->render('@Countrygate/module/gate.html.twig', [
            'tpl' => 'tpl',
        ]);
    }
}