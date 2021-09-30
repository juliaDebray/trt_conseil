<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Form\OfferType;
use App\Repository\OffersRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffersController extends AbstractController
{
    /**
     * @Route ("/offers", name="offers"),
     */
    public function index(Request $request): Response
    {
        $offer = new Offers();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();

            $offer->setAuthorId($user);
            $offer->setStatus('pending');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->render('offer/offerCreated.html.twig');
        }

        return $this->renderForm('offer/newOffer.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted ("ROLE_CONSULTANT"),
     * @Route("/delete/{offerId}",name="deleteOffer",methods={"GET","POST"}),
     */
    public function delete(int $offerId, OffersRepository $offersRepository) : Response
    {
        $offerToDelete = $offersRepository->find($offerId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($offerToDelete);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }
}
