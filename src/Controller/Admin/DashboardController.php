<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Back End Symfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Users', 'fa fa-user', 'App\Entity\User');

        yield MenuItem::linkToCrud('Products', 'fa fa-shopping-cart', 'App\Entity\Products');

        yield MenuItem::linkToCrud('Carts', 'fa fa-shopping-basket', 'App\Entity\Cart');
    }
}

