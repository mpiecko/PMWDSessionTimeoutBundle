<?php

namespace PMWD\Bundle\SessionTimeoutBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SessionListener implements EventSubscriberInterface
{
    protected $idleTime;

    public function __construct($idleTime)
    {
        $this->idleTime = (int) $idleTime;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // Don't check the idle time if it is 0, or a sub request
        if (!$this->idleTime || HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $session = $event->getRequest()->getSession();

        // If there is no session, we need to start it
        if (!$session->isStarted()) {
            $session->start();
        }

        if ((time() - $session->getMetadataBag()->getLastUsed()) > $this->idleTime) {
            $session->invalidate();
        }

    }

    static public function getSubscribedEvents()
    {
        // Subscribe just after the session is created
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 127)),
        );
    }

}