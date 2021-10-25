<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use App\Repository\OffersRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Define the hom foreach type of user and redirect to the good home route
     * @Route("/home", name="home"),
     */
    public function index(OffersRepository $offersRepository, CandidatureRepository $candidatureRepository): Response
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->redirectToRoute('default');
        }

        if($user->getStatus() != 'validated') {
            return $this->render('user_create/created.html.twig');
        }

        $userRole = implode($user->getRoles());

        if($this->isGranted('ROLE_CANDIDATE', $userRole))
        {
            $offers = $offersRepository->findBy(['status' => 'validated']);

            return $this->render('home/candidateHome.html.twig', [ 'offers' => $offers ]);
        }

        else if($this->isGranted('ROLE_CONSULTANT', $userRole))
        {
            return $this->redirectToRoute('homeConsultant');
        }

        else if($this->isGranted('ROLE_ADMIN', $userRole))
        {
            return $this->redirectToRoute('admin');
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
    public function homeConsultant(UserRepository $userRepository,
                                   OffersRepository $offersRepository,
                                   CandidatureRepository $candidatureRepository): Response
    {
        $users = $userRepository->findBy([ 'status' =>'pending' ]);
        $offers = $offersRepository->findBy([ 'status' => 'pending' ]);
        $candidates = $candidatureRepository->findBy(['status'=>'pending']);

        return $this->render('home/consultantHome.html.twig',
            [ 'users' => $users, 'offers' => $offers, 'candidates' => $candidates ]);
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
