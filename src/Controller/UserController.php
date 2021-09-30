<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RecruiterType;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * A new user create his account
     *
     * @Route ("/new",name="user_create_new",methods={"GET","POST"}),
     */
    public function new(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            {
                $user->setStatus('pending');
            }

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
     * the recruiter edits his profile
     *
     * @IsGranted("ROLE_RECRUITER"),
     * @Route("/recruiter/{id}",name="recruiter_edit",methods={"GET","POST"}),
     */
    public function editRecruiter(Request $request, User $user): Response
    {
        $form = $this->createForm(RecruiterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('user_create/editRecruiter.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * the candidate edits his profile
     *
     * @IsGranted ("ROLE_CANDIDATE"),
     * @Route("/candidate/{id}",name="candidate_edit",methods={"GET","POST"}),
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
     * the consultant refuse the new account of a user
     *
     * @IsGranted ("ROLE_CONSULTANT"),
     * @Route("/delete/{userId}",name="deleteUser",methods={"GET","POST"}),
     */
    public function delete(int $userId, UserRepository $userRepository) : Response
    {
        $userToDelete = $userRepository->find($userId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($userToDelete);
        $entityManager->flush();

        return $this->redirectToRoute('homeConsultant');
    }
}
