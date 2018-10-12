<?php

declare(strict_types=1);

/*
 * Countrygate Bundle, 47GradNord - Agentur für Internetlösungen
 *
 * @copyright  Copyright (c) 2008-2018, 47GradNord - Agentur für Internetlösungen
 * @author     47GradNord - Agentur für Internetlösungen <info@47gradnord.de>
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['cg_gate'] = '
{title_legend},name,type;
{redirect_legend},jumpToGatePassed,jumpTo;
{text_select_legend},cg_de_select,cg_en_select;
{text_disclaimer_legend},cg_de_disclaimer,cg_en_disclaimer;
{text_disclaimer_us_legend},cg_de_disclaimer_us,cg_en_disclaimer_us, cg_de_disclaimer_us_checkbox_1, cg_en_disclaimer_us_checkbox_1, cg_de_disclaimer_us_checkbox_2, cg_en_disclaimer_us_checkbox_2;
{text_disclaimer_ca_au_jp_legend},cg_de_disclaimer_ca_au_jp,cg_en_disclaimer_ca_au_jp;
{protected_legend:hide},protected;
{template_legend:hide},customTpl;
{expert_legend:hide},guests,cssID,space';


$GLOBALS['TL_DCA']['tl_module']['palettes']['cg_nav_extended'] = '{title_legend},name,headline,type;{nav_legend},levelOffset,showLevel,hardLimit,showProtected,showHidden;{reference_legend:hide},defineRoot;{template_legend:hide},navigationTpl,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['jumpToGatePassed'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['jumpToGatePassed'],
    'exclude' => true,
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio'],
    'sql' => "int(10) unsigned NOT NULL default '0'",
    'relation' => ['type' => 'hasOne', 'load' => 'eager'],
];

// Select Step
$GLOBALS['TL_DCA']['tl_module']['fields']['cg_de_select'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_de_select'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['cg_en_select'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_en_select'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

// Disclaimer (DE, Others)
$GLOBALS['TL_DCA']['tl_module']['fields']['cg_de_disclaimer'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_de_disclaimer'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['cg_en_disclaimer'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_en_disclaimer'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

// Disclaimer with Chechboxes (us)
$GLOBALS['TL_DCA']['tl_module']['fields']['cg_de_disclaimer_us'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_de_disclaimer_us'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];
$GLOBALS['TL_DCA']['tl_module']['fields']['cg_en_disclaimer_us'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_en_disclaimer_us'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cg_de_disclaimer_us_checkbox_1'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_de_disclaimer_us_checkbox_1'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cg_en_disclaimer_us_checkbox_1'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_en_disclaimer_us_checkbox_1'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cg_de_disclaimer_us_checkbox_2'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_de_disclaimer_us_checkbox_2'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cg_en_disclaimer_us_checkbox_2'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_en_disclaimer_us_checkbox_2'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cg_de_disclaimer_ca_au_jp'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_de_disclaimer_ca_au_jp'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cg_en_disclaimer_ca_au_jp'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cg_en_disclaimer_ca_au_jp'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];
