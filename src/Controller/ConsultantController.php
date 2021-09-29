<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConsultantType;
use App\Repository\CandidatureRepository;
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
     * @IsGranted("ROLE_ADMIN"),
     * @Route ("/new_consultant", name="consultant"),
     */
    public function newConsultant(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(ConsultantType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setRoles(['ROLE_CONSULTANT']);
            $user->setStatus('validated');

            $passwordToHash = $user->getPassword();

            $user->setPassword($passwordHasher->hashPassword($user, $passwordToHash));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('consultant/consultant_created.html.twig');
        }

        return $this->renderForm('home/adminHome.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_CONSULTANT"),
     * @Route ("/moderate_user/{id}", name="moderateUser"),
     */
    public function moderateUser(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);
        $user->setStatus('validated');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }

    /**
     * @IsGranted("ROLE_CONSULTANT"),
     * @Route ("/moderate_offer/{id}", name="moderateOffer"),
     */
    public function moderateOffer(OffersRepository $offersRepository, int $id): Response
    {
        $offer = $offersRepository->find($id);
        $offer->setStatus('validated');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }

    /**
     * @IsGranted("ROLE_CONSULTANT"),
     * @Route ("/moderate_candidate/{id}", name="moderateCandidate"),
     */
    public function moderateCandidature(CandidatureRepository $candidatureRepository, int $id): Response
    {
        $offer = $candidatureRepository->find($id);
        $offer->setStatus('validated');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }
}
