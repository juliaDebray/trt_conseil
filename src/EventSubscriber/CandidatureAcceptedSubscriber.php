<?php

namespace App\EventSubscriber;

use App\Event\CandidatureAcceptedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

class CandidatureAcceptedSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onCandidatureAcceptedEvent(CandidatureAcceptedEvent $event): void
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@monappli.com')
            ->to($event->getRecruiterEmail())
            ->subject('Vous avez un nouveau candidat')
            ->htmlTemplate('mail/candidate.html.twig')
            ->context([
                'candidateData' => $event,
            ])
            ->attachFromPath(
                'uploads/curriculumVitae/' . $event->getCandidateCurriculumVitae(),
                'CV-' . $event->getCandidateFirstname() . $event->getCandidateLastname()
            )
        ;

        $this->mailer->send($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            CandidatureAcceptedEvent::class => 'onCandidatureAcceptedEvent',
        ];
    }
}
