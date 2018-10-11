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

        dump($this->sessionGate->getSession());
        exit;

        // Compile
        return $this->render('@Countrygate/module/gate.html.twig', [
            'tpl' => 'tpl',
        ]);
    }
}
