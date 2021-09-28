<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserCreateController extends AbstractController
{
    /**
     * @Route("/", name="user_create_index", methods={"GET"}),
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user_create/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route ("/new",name="user_create_new",methods={"GET","POST"}),
     */
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setStatus('pending');

            $passwordToHash = $user->getPassword();
            $user->setPassword($passwordHasher->hashPassword($user, $passwordToHash));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('user_create/created.html.twig');
        }

        return $this->renderForm('user_create/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route ("/{id}/toDefine", name="user_create_show",methods={"GET"}),
     */
    public function show(User $user): Response
    {
        return $this->render('user_create/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}",name="user_create_edit",methods={"GET","POST"}),
     */
    public function edit(Request $request, User $user, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $curriculumFile = $form->get('curriculum_vitae')->getData();

            if ($curriculumFile) {
                $FileName = $fileUploader->upload($curriculumFile);
                $user->setCurriculumVitae($FileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('user_create/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete",name="user_create_delete",methods={"POST"}),
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_create_index', [], Response::HTTP_SEE_OTHER);
    }
}
