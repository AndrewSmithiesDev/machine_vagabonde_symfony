<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['startAt' => 'ASC']);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),

            FormField::addPanel('Informations affichées sur la carte'),

            TextField::new('title', 'Titre')
                ->setHelp('Exemple : Brocante, Vide-grenier...'),

            TextField::new('subtitle', 'Sous-titre')
                ->setHelp('Exemple : Villeurbanne'),

            FormField::addPanel('Informations générales'),

            TextEditorField::new('description', 'Description')
                ->setHelp('Texte optionnel')
                ->hideOnIndex(),

            TextField::new('address', 'Adresse')
                ->hideOnIndex()
                ->setFormTypeOption('attr', ['placeholder' => '12 rue de la République, Lyon']),

            DateTimeField::new('startAt', 'Date de début'),

            DateTimeField::new('endAt', 'Date de fin'),

            TextField::new('displayDay', 'Jour affiché')
                ->setHelp('Exemple : Samedi 14')
                ->hideOnIndex(),

            TextField::new('displayTime', 'Heure affiché')
                ->setHelp('Exemple : 9h - 17h')
                ->hideOnIndex(),

            ImageField::new('image', 'Image')
                ->setBasePath('uploads/events')
                ->setUploadDir('public/uploads/events')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setHelp('Téléchargez une image au format JPG ou PNG')
                ->setRequired(false)
        ];
    }
}
