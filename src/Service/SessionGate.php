<?php

declare(strict_types=1);

/*
 * Countrygate Bundle, 47GradNord - Agentur für Internetlösungen
 *
 * @copyright  Copyright (c) 2008-2018, 47GradNord - Agentur für Internetlösungen
 * @author     47GradNord - Agentur für Internetlösungen <info@47gradnord.de>
 */

namespace AaProjects\Countrygate\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionGate
{
    /** @var string */
    const SESSION_KEY = 'session.gate';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Gate constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array
     */
    public function getSession(): array
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();

        if (null === $session->get(self::SESSION_KEY)) {
            $session->set(self::SESSION_KEY, []);
            $session = $this->requestStack->getCurrentRequest()->getSession();
        }

        return $session->get(self::SESSION_KEY);
    }

    /**
     * @param array $values
     */
    public function setSession(array $values)
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $session->set(self::SESSION_KEY, $values);
    }

    /**
     * @return $this
     */
    public function removeSession()
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();

        if (null !== $session->get(self::SESSION_KEY)) {
            $session->remove(self::SESSION_KEY);

            return $this;
        }
    }
}
