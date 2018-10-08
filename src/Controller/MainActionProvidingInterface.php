<?php
/**
 * Created by PhpStorm.
 * User: holger
 * Date: 08.10.18
 * Time: 11:11
 */

namespace sgn47gn\Countrygate\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface MainActionProvidingInterface
{
    /**
     * @param Request $request
     * @param array   $moduleSettings
     *
     * @return Response
     */
    public function mainAction(Request $request, array $moduleSettings): Response;

}