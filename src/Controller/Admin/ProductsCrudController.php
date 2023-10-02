<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('productName', 'Product Name'),
            TextField::new('productCode', 'Product Code'),
            TextField::new('releaseDate', 'Release Date'),
            NumberField::new('starRating', 'Star Rating'),
            TextEditorField::new('description', 'Product Description'),
            NumberField::new('price', 'Product Price'),
            AssociationField::new('category', 'Category'),
        ];
    }
}
