<?php
/**
 * GIT SCHRANKEN-MODUL for Contao Open Source CMS
 *
 * Copyright (C) 2018 47GradNord - Agentur für Internetlösungen
 *
 * @license    commercial
 * @author     Holger Neuner
 */

/**
 * Expand DCA
 */
$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = ['AaProjects\Countrygate\Backend\Callback', 'adjustDcaTlPage'];

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['cg_setPageHidden'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['cg_setPageHidden'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];