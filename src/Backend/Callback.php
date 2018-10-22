<?php
/**
 * GIT SCHRANKEN-MODUL for Contao Open Source CMS
 *
 * Copyright (C) 2018 47GradNord - Agentur für Internetlösungen
 *
 * @license    commercial
 * @author     Holger Neuner
 */


namespace AaProjects\Countrygate\Backend;


class Callback
{
    /** Adjust DCA from tl_page */
    public function adjustDcaTlPage() : void
    {
        // Add different Billing Address
        foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $palette => $v) {
            if ('__selector__' === $palette) {
                continue;
            }

            if ('regular' === $palette) {
                $adjustValue = '';

                $singleItems = explode(';', $v);

                foreach ($singleItems as $item) {
                    if (0 === strpos($item, '{publish_legend}')) {
                        $adjustValue .= '{aa_countrygate_legend},cg_setPageHidden;' . $item . ';';
                    } else {
                        $adjustValue .= $item . ';';
                    }
                }

                $GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = $adjustValue;
            }
        }
    }
}