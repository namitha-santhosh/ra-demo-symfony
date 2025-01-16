<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Artifact;
use App\Entity\ArtifactRelease;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ArtifactReleaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArtifactRelease::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('artifact', 'Artifact'),
            AssociationField::new('release', 'Release'),
            TextField::new('version', 'Artifact Version'),
            TextField::new('buildNum', 'Build Number'),
            DateField::new('buildDateTime', 'Build Date & Time'),
            TextField::new('sourceRef', 'Reference Number'),
        ];
    }
}
