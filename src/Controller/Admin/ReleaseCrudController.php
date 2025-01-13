<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Release;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ReleaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Release::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Release Name'),
            TextField::new('status', 'Release Status'),
            TextField::new('mainReleaseTicket', 'Main Release Ticket'),
            DateField::new('qaDate', 'QA Date'),
            DateField::new('stageDate', 'Stage Date'),
            DateField::new('productionDate', 'Production Date'),
            AssociationField::new('artifacts')
                ->hideOnForm()
                ->setTemplatePath('admin/fields/artifacts.html.twig')
                ->onlyOnDetail(),
        ];
    }
}
