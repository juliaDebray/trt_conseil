<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\CandidatureRepository;
use App\Repository\OffersRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/candidature"),
 */
class CandidatureController extends AbstractController
{
    /**
     * A candidate applies for an offer
     *
     * @IsGranted ("ROLE_CANDIDATE")
     * @Route("/new/{offerId}", name="candidature_new", methods={"GET","POST"}),
     */
    public function new(Request $request, int $offerId, OffersRepository $offersRepository): Response
    {
        $candidature = new Candidature();

        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        $candidate = $this->getUser();

        if($candidate->getCurriculumVitae() && $candidate->getFirstname() && $candidate->getLastname() ) {

        $offer = $offersRepository->find($offerId);

        $candidature->setStatus('pending');
        $candidature->setOffer($offer);
        $candidature->setCandidate($candidate);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($candidature);
        $entityManager->flush();

        Return $this->render('candidature/candidatureCreated.html.twig');
        }

        Return $this->render('candidature/candidatureNotCreated.html.twig');

    }

    /**
     * show candidate to recruiter depend on the offer and its status
     *
     * @IsGranted ("ROLE_RECRUITER")
     * @Route("/{offerId}", name="candidatures_show", methods={"GET"}),
     */
    public function showCandidates(int $offerId, CandidatureRepository $candidatureRepository): Response
    {
        $candidates = $candidatureRepository->findBy([ 'offer'=>$offerId, 'status'=>'validated' ]);

        return $this->render('candidature/show.html.twig', [
            'candidates' => $candidates,
        ]);
    }

    /**
     * the consultant refuse the new candidate
     *
     * @IsGranted ("ROLE_CONSULTANT"),
     * @Route("/delete/{candidatureId}",name="deleteCandidature",methods={"GET","POST"}),
     */
    public function delete(int $candidatureId, CandidatureRepository $candidatureRepository) : Response
    {
        $candidatureToDelete = $candidatureRepository->find($candidatureId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($candidatureToDelete);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }
}
