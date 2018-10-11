<?php

declare(strict_types=1);

/*
 * Countrygate Bundle, 47GradNord - Agentur für Internetlösungen
 *
 * @copyright  Copyright (c) 2008-2018, 47GradNord - Agentur für Internetlösungen
 * @author     47GradNord - Agentur für Internetlösungen <info@47gradnord.de>
 */

/*
 * Frontend Module
 */
$GLOBALS['FE_MOD']['countrygate']['cg_gate'] = 'AaProjects\Countrygate\Proxy\Module';


$GLOBALS['TL_AA_COUNTRYS'] = [
    'de' => [
        'label' => [
            'de' => 'Deutschland',
            'en' => 'Germany',
        ],
        'pass' => true
    ],
    'us' => [
        'label' => [
            'de' => 'Vereinigte Statten von Amerika',
            'en' => 'United States of America',
        ],
        'pass' => true
    ],
    'ca' => [
        'label' => [
            'de' => 'Kanada',
            'en' => 'Canada',
        ],
        'pass' => false
    ],
    'au' => [
        'label' => [
            'de' => 'Australien',
            'en' => 'Australia',
        ],
        'pass' => false
    ],
    'jp' => [
        'label' => [
            'de' => 'Japan',
            'en' => 'Japan',
        ],
        'pass' => false
    ],
    'others' => [
        'label' => [
            'de' => 'Andere',
            'en' => 'Others',
        ],
        'pass' => true
    ]
];


