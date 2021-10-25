<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('email', 'Email'),
            TextField::new('password', 'Mot de passe')
                ->onlyOnForms()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'invalid_message' => 'les mots de passe ne sont pas identiques',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répétez le mot de passe']
                ]),
            CollectionField::new('roles', 'Role')
                ->setTemplatePath('admin/user.html.twig')
                ->setFormTypeOptions([
                    'entry_type'=>ChoiceType::class,
                    'entry_options'=> [
                        'choices' => [
                            "Administateur"=>"ROLE_ADMIN",
                            "Consultant"=>"ROLE_CONSULTANT",
                            "Recruteur"=>"ROLE_RECRUITER",
                            "Candidat"=>"ROLE_CANDIDATE",
                        ]
                    ]
                ]),
            ChoiceField::new('status', 'statut du compte')
                ->setTemplatePath('admin/status_color.html.twig')
                ->setChoices([
                    "En attente"=>"pending",
                    "Validé"=>"validated",
                ]),
        ];


//            TextField::new('company_name', 'Entreprise')->onlyOnForms(),
//            TextField::new('company_address', 'Adresse d\'entreprise')->onlyOnForms(),
//
//            TextField::new('firstname', 'Prénom')->onlyOnForms(),
//            TextField::new('lastname', 'Nom')->onlyOnForms(),
//            TextEditorField::new('curriculum_vitae','Curriculum vitae')->onlyOnForms()

    }

//    public function configureCrud(Crud $crud): Crud
//    {
//        return $crud->overrideTemplate(
//            'crud/field/association', 'admin/user.html.twig');
//    }
//
//    public function configureAssets(Assets $assets): Assets
//    {
//        return $assets
//            ->addHtmlContentToBody('<script>
//                const roles = document.getElementById("User_roles_0");
//                const mail = document.getElementById("User_email");
//                const recruiterRole = "ROLE_RECRUITER";
//                const candidateRole = "ROLE_CANDIDATE";
//
//                roles.addEventListener("change", (event) => {
//                    const value = event.target.value;
//                    if (value.includes(recruiterRole)) {
//                        console.log("HEY, cest un recruteur !!");
//                    }
//
//                    if (value.includes(candidateRole)) {
//                        console.log("HEY, cest un candidat !!");
//                    }
//                });
//            function addRecruiterFields() {
//                return;
//            }
//
//            function addCandidateFields() {
//                return;
//            }
//            </script>');
//    }
}


