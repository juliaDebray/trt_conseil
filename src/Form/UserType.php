<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['required' => true])
            ->add('roles', CollectionType::class,
                [
                'entry_type' => ChoiceType::class,
                'entry_options' =>
                    [
                    'label'=> 'Êtes-vous employeur ou candidat ?',
                    'required' => true,
                    'choices' =>
                        [
                        'Recruteur' => 'ROLE_RECRUITER',
                        'Candidat' => 'ROLE_CANDIDATE',
                        ],
                    ],
                ]
            )
            ->add('password', PasswordType::class, ['required' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
