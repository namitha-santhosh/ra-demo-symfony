<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Artifact;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ArtifactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artifact::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Artifact Name'),
            TextField::new('version', 'Artifact Version'),
            TextField::new('status', 'Artifact Status'),
            NumberField::new('buildNum', 'Build Number'),
            TextField::new('referenceNumber', 'Source Reference'),
            DateField::new('buildDateTime', 'Build Date Time'),
            AssociationField::new('release')
                ->hideOnForm()
                ->onlyOnDetail(),
        ];
    }
}
