<?php

namespace App\Controller\Admin;

use App\Entity\CartItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class CartItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CartItem::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), 
            AssociationField::new('cart', 'Cart'), 
            AssociationField::new('products', 'Products')
            ->autocomplete()
            ->setFormTypeOptions([
                'multiple' => true,
                'by_reference' => false, 
            ]),
        ];
    }

}
