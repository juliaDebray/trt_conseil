<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\CandidatureAcceptedEvent;
use App\Form\ConsultantType;
use App\Repository\CandidatureRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\OffersRepository;


class ConsultantController extends AbstractController
{
    /**
     * the consultant accept a new user account
     *
     * @IsGranted("ROLE_CONSULTANT"),
     * @Route ("/moderate_user/{userId}", name="moderateUser"),
     */
    public function moderateUser(UserRepository $userRepository, int $userId): Response
    {
        $user = $userRepository->find($userId);
        $user->setStatus('validated');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }

    /**
     * the consultant accept a new offer
     *
     * @IsGranted("ROLE_CONSULTANT"),
     * @Route ("/moderate_offer/{offerId}", name="moderateOffer"),
     */
    public function moderateOffer(OffersRepository $offersRepository, int $offerId): Response
    {
        $offer = $offersRepository->find($offerId);
        $offer->setStatus('validated');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }

    /**
     * the consultant accet a new candidature
     *
     * @IsGranted("ROLE_CONSULTANT"),
     * @Route ("/moderate_candidate/{candidatureId}", name="moderateCandidate"),
     */
    public function moderateCandidature(CandidatureRepository $candidatureRepository,
                                        EventDispatcherInterface $eventDispatcher,
                                        int $candidatureId): Response
    {
        $candidature = $candidatureRepository->find($candidatureId);
        $candidature->setStatus('validated');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($candidature);
        $entityManager->flush();

        $candidate = $candidature->getCandidate();
        $recruiter = $candidature->getOffer()->getAuthorId();
        $offer = $candidature->getOffer();

        $event = new CandidatureAcceptedEvent(
            $candidate->getFirstname(),
            $candidate->getLastname(),
            $candidate->getCurriculumVitae(),
            $recruiter->getEmail(),
            $offer->getName(),
        );

        $eventDispatcher->dispatch($event);

        return $this->redirectToRoute('homeConsultant');
    }
}
