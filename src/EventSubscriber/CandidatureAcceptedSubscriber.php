<?php

namespace App\EventSubscriber;

use App\Event\CandidatureAcceptedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CandidatureAcceptedSubscriber implements EventSubscriberInterface
{
    public function onCandidatureAcceptedEvent(CandidatureAcceptedEvent $event)
    {
        //TODO: implement email sending method.
    }

    public static function getSubscribedEvents()
    {
        return [
            CandidatureAcceptedEvent::class => 'onCandidatureAcceptedEvent',
        ];
    }
}
