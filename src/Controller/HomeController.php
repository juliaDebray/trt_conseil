<?php

namespace App\Controller;

use App\Repository\OffersRepository;
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
        $offers = $offersRepository->findAll();
        return $this->render('home/home.html.twig', [ 'offers' => $offers ]);
    }
}
