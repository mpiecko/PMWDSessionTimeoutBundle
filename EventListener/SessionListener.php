<?php

namespace PMWD\Bundle\SessionTimeoutBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SessionListener implements EventSubscriberInterface
{

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $session = $event->getRequest()->getSession();

        // If there is no session, we need to start it
        if (!$session->isStarted()) {
            $session->start();
        }

        if ((time() - $session->getMetadataBag()->getLastUsed()) > 15 * 60) {
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