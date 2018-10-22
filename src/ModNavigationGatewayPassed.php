<?php
/**
 * GIT SCHRANKEN-MODUL for Contao Open Source CMS
 *
 * Copyright (C) 2018 47GradNord - Agentur für Internetlösungen
 *
 * @license    commercial
 * @author     Holger Neuner
 */


namespace AaProjects\Countrygate;


use AaProjects\Countrygate\Service\SessionGate;
use Contao\ModuleNavigation;
use Contao\ModuleSitemap;
use Contao\PageModel;
use Contao\System;

class ModNavigationGatewayPassed extends ModuleNavigation
{
    /** @var SessionGate */
    private $sessionGate;

    /**
     * Recursively compile the navigation menu and return it as HTML string
     *
     * @param integer $pid
     * @param integer $level
     * @param string  $host
     * @param string  $language
     *
     * @return string
     */
    protected function renderNavigation($pid, $level=1, $host=null, $language=null)
    {
        // Get all active subpages
        $objSubpages = \PageModel::findPublishedSubpagesWithoutGuestsByPid($pid, $this->showHidden, $this instanceof ModuleSitemap);

        if ($objSubpages === null)
        {
            return '';
        }

        $items = array();
        $groups = array();

        // Get all groups of the current front end user
        if (FE_USER_LOGGED_IN)
        {
            $this->import('FrontendUser', 'User');
            $groups = $this->User->groups;
        }

        // Layout template fallback
        if ($this->navigationTpl == '')
        {
            $this->navigationTpl = 'nav_default';
        }

        $objTemplate = new \FrontendTemplate($this->navigationTpl);
        $objTemplate->pid = $pid;
        $objTemplate->type = \get_class($this);
        $objTemplate->cssID = $this->cssID; // see #4897
        $objTemplate->level = 'level_' . $level++;

        /** @var PageModel $objPage */
        global $objPage;

        /** @var SessionGate sessionGate */
        $this->sessionGate = System::getContainer()->get('countrygate.session_gate');

        // Browse subpages
        foreach ($objSubpages as $objSubpage)
        {
            if($objSubpage->cg_setPageHidden == '1' && !$this->sessionGate->getSession()['passed'])
            {
                continue;
            }

            // Skip hidden sitemap pages
            if ($this instanceof ModuleSitemap && $objSubpage->sitemap == 'map_never')
            {
                continue;
            }

            $subitems = '';
            $_groups = \StringUtil::deserialize($objSubpage->groups);

            // Override the domain (see #3765)
            if ($host !== null)
            {
                $objSubpage->domain = $host;
            }

            // Do not show protected pages unless a front end user is logged in
            if (!$objSubpage->protected || (\is_array($_groups) && \count(array_intersect($_groups, $groups))) || $this->showProtected || ($this instanceof ModuleSitemap && $objSubpage->sitemap == 'map_always'))
            {
                // Check whether there will be subpages
                if ($objSubpage->subpages > 0 && (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpage->id || \in_array($objPage->id, $this->Database->getChildRecords($objSubpage->id, 'tl_page'))))))
                {
                    $subitems = $this->renderNavigation($objSubpage->id, $level, $host, $language);
                }

                $href = null;

                // Get href
                switch ($objSubpage->type)
                {
                    case 'redirect':
                        $href = $objSubpage->url;

                        if (strncasecmp($href, 'mailto:', 7) === 0)
                        {
                            $href = \StringUtil::encodeEmail($href);
                        }
                        break;

                    case 'forward':
                        if ($objSubpage->jumpTo)
                        {
                            /** @var PageModel $objNext */
                            $objNext = $objSubpage->getRelated('jumpTo');
                        }
                        else
                        {
                            $objNext = \PageModel::findFirstPublishedRegularByPid($objSubpage->id);
                        }

                        $isInvisible = !$objNext->published || ($objNext->start != '' && $objNext->start > time()) || ($objNext->stop != '' && $objNext->stop < time());

                        // Hide the link if the target page is invisible
                        if (!$objNext instanceof PageModel || ($isInvisible && !BE_USER_LOGGED_IN))
                        {
                            continue 2;
                        }

                        $href = $objNext->getFrontendUrl();
                        break;

                    default:
                        $href = $objSubpage->getFrontendUrl();
                        break;
                }

                $row = $objSubpage->row();
                $trail = \in_array($objSubpage->id, $objPage->trail);

                // Active page
                if (($objPage->id == $objSubpage->id || ($objSubpage->type == 'forward' && $objPage->id == $objSubpage->jumpTo)) && !($this instanceof ModuleSitemap) && $href == \Environment::get('request'))
                {
                    // Mark active forward pages (see #4822)
                    $strClass = (($objSubpage->type == 'forward' && $objPage->id == $objSubpage->jumpTo) ? 'forward' . ($trail ? ' trail' : '') : 'active') . (($subitems != '') ? ' submenu' : '') . ($objSubpage->protected ? ' protected' : '') . (($objSubpage->cssClass != '') ? ' ' . $objSubpage->cssClass : '');

                    $row['isActive'] = true;
                    $row['isTrail'] = false;
                }

                // Regular page
                else
                {
                    $strClass = (($subitems != '') ? 'submenu' : '') . ($objSubpage->protected ? ' protected' : '') . ($trail ? ' trail' : '') . (($objSubpage->cssClass != '') ? ' ' . $objSubpage->cssClass : '');

                    // Mark pages on the same level (see #2419)
                    if ($objSubpage->pid == $objPage->pid)
                    {
                        $strClass .= ' sibling';
                    }

                    $row['isActive'] = false;
                    $row['isTrail'] = $trail;
                }

                $row['subitems'] = $subitems;
                $row['class'] = trim($strClass);
                $row['title'] = \StringUtil::specialchars($objSubpage->title, true);
                $row['pageTitle'] = \StringUtil::specialchars($objSubpage->pageTitle, true);
                $row['link'] = $objSubpage->title;
                $row['href'] = $href;
                $row['rel'] = '';
                $row['nofollow'] = (strncmp($objSubpage->robots, 'noindex,nofollow', 16) === 0); // backwards compatibility
                $row['target'] = '';
                $row['description'] = str_replace(array("\n", "\r"), array(' ', ''), $objSubpage->description);

                // Override the link target
                if ($objSubpage->type == 'redirect' && $objSubpage->target)
                {
                    $row['target'] = ' target="_blank"';
                }

                $arrRel = array();

                if (strncmp($objSubpage->robots, 'noindex,nofollow', 16) === 0)
                {
                    $arrRel[] = 'nofollow';
                }

                if ($objSubpage->type == 'redirect' && $objSubpage->target)
                {
                    $arrRel[] = 'noreferrer';
                    $arrRel[] = 'noopener';
                }

                // Override the rel attribute
                if (!empty($arrRel))
                {
                    $row['rel'] = ' rel="' . implode(' ', $arrRel) . '"';
                }

                $items[] = $row;
            }
        }

        // Add classes first and last
        if (!empty($items))
        {
            $last = \count($items) - 1;

            $items[0]['class'] = trim($items[0]['class'] . ' first');
            $items[$last]['class'] = trim($items[$last]['class'] . ' last');
        }

        $objTemplate->items = $items;

        return !empty($items) ? $objTemplate->parse() : '';
    }
}