<?php

namespace App\Controller;

use App\Repository\OffersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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

        if($this->isGranted('ROLE_CANDIDATE', $userRole)) {
        $offers = $offersRepository->findAll();
        return $this->render('home/home.html.twig', [ 'offers' => $offers ]);
        }

        return $this->redirectToRoute('homeRecruiter');
    }

    /**
     * @Route("/recruiter/home", name="homeRecruiter"),
     */
    public function homeRecruiter(OffersRepository $offersRepository): Response
    {
        $user = $this->getUser();

        if($user->getStatus() != 'validated') {
            return $this->render('user_create/created.html.twig');
        }

        $userId = $user->getId();
        $recruitersOffers = $offersRepository->getRecruitersOffers($userId);

        return $this->render('home/recruiterHome.html.twig', [ 'offers' => $recruitersOffers ]);
    }
}
