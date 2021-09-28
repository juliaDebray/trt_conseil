<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConsultantType;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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
}
