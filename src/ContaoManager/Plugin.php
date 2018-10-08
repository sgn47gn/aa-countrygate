<?php

declare(strict_types=1);

/*
 * Countrygate Bundle, 47GradNord - Agentur für Internetlösungen
 *
 * @copyright  Copyright (c) 2008-2018, 47GradNord - Agentur für Internetlösungen
 * @author     47GradNord - Agentur für Internetlösungen <info@47gradnord.de>
 */

namespace sgn47gn\Countrygate\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use sgn47gn\Countrygate\CountrygateBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CountrygateBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
