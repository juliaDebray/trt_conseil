<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('email', EmailType::class, ['required' => false])
//            ->add('password', PasswordType::class, ['label'=> 'Mot de passe', 'required' => false ])
            ->add('firstname', TextType::class, ['label'=> 'Prénom', 'required' => false ])
            ->add('lastname', TextType::class, ['label'=> 'Nom de famille', 'required' => false ])
            ->add('company_name', TextType::class, ['label'=> 'Nom de l\'entreprise', 'required' => false ])
            ->add('company_address', TextType::class, ['label'=>'Adresse de l\'entreprise', 'required' => false ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
