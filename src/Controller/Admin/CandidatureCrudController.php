<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CandidatureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidature::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            AssociationField::new('candidate', 'Candidat')
            ->setTemplatePath('admin/recruiter_index.html.twig'),
            AssociationField::new('offer','Offre d\'emploi'),
            ChoiceField::new('status', 'statut de la candidature')
                ->setTemplatePath('admin/status_color.html.twig')
                ->setChoices([
                    "En attente"=>"pending",
                    "Validé"=>"validated",
                ]),
        ];
    }

}
