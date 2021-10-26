<?php

namespace App\Controller\Admin;

use App\Entity\Offers;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OffersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offers::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields =  [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Intitulé'),
            TextField::new('company_address', 'Adresse'),
            TextField::new('Description'),
            AssociationField::new('author_id', 'Recruteur')
                ->setTemplatePath('admin/recruiter_index.html.twig'),
            AssociationField::new('candidates', 'Candidat')
                ->setTemplatePath('admin/offer_index.html.twig'),
            ChoiceField::new('status', 'Statut')
                ->setTemplatePath('admin/status_color.html.twig')
                ->setChoices([
                    "En attente"=>"pending",
                    "Validée"=>"validated",
                ])
        ];

        if($pageName === Crud::PAGE_NEW)
            $fields =  [
                TextField::new('name', 'Intitulé'),
                TextField::new('company_address', 'Adresse'),
                TextField::new('Description'),
                AssociationField::new('author_id', 'Recruteur'),
                ChoiceField::new('status', 'Statut')
                    ->setChoices([
                        "En attente"=>"pending",
                        "Validée"=>"validated",
                    ])
        ];

        return $fields;
    }

}
