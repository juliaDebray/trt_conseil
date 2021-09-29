<?php

namespace App\Controller;

use App\Repository\OffersRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home"),
     */
    public function index(OffersRepository $offersRepository): Response
    {
        $user = $this->getUser();

        if($user->getStatus() != 'validated') {
            return $this->render('user_create/created.html.twig');
        }

        $userRole = implode($user->getRoles());

        if($this->isGranted('ROLE_CANDIDATE', $userRole))
        {
            $offers = $offersRepository->findBy(['status' => 'validated']);
            return $this->render('home/home.html.twig', [ 'offers' => $offers ]);
        }

        else if($this->isGranted('ROLE_CONSULTANT', $userRole))
        {
            return $this->redirectToRoute('homeConsultant');
        }

        else if($this->isGranted('ROLE_ADMIN', $userRole))
        {
            return $this->redirectToRoute('homeAdmin');
        }

        return $this->redirectToRoute('homeRecruiter');
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/home", name="homeRecruiter"),
     */
    public function homeRecruiter(OffersRepository $offersRepository): Response
    {
        $user = $this->getUser();

        $userId = $user->getId();
        $recruitersOffers = $offersRepository->getRecruitersOffers($userId);

        return $this->render('home/recruiterHome.html.twig', [ 'offers' => $recruitersOffers ]);
    }

    /**
     * @IsGranted ("ROLE_CONSULTANT")
     * @Route("/consultant/home", name="homeConsultant"),
     */
    public function homeConsultant(UserRepository $userRepository, OffersRepository $offersRepository): Response
    {
        $users = $userRepository->findAll();
        $usersToReturn = [];

        foreach($users as $user) {
            if($user->getStatus() == 'pending') {
                array_push($usersToReturn, $user);
            }
        }

        $offers = $offersRepository->findAll();
        $offersToReturn = [];

        foreach($offers as $offer) {
            if($offer->getStatus() == 'pending') {
                array_push($offersToReturn, $offer);
            }
        }

        return $this->render('home/consultantHome.html.twig',
            ['users' => $usersToReturn, 'offers' => $offersToReturn]);
    }

    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route("/moderate", name="homeAdmin"),
     */
    public function homeAdmin(): Response
    {
        return $this->redirectToRoute('consultant');
    }
}
