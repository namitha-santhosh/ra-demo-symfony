<?php

namespace App\Controller\Admin;

use App\Entity\Artifact;
use App\Entity\ArtifactRelease;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Locale;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Release;
use App\Entity\User;
use App\Entity\Deployment;
use Symfony\Component\Security\Core\Authorization\Annotation\IsGranted;



class DashboardController extends AbstractDashboardController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/api/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(ReleaseCrudController::class)->generateUrl());
    }
    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Releases'),
            MenuItem::linkToCrud('Releases', 'fa fa-tags', Release::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),

            MenuItem::section('Artifacts'),
            MenuItem::linkToCrud('Artifact', 'fa fa-comment', Artifact::class),

            MenuItem::section('Release Artifacts'),
            MenuItem::linkToCrud('ArtifactRelease', 'fa fa-comment', ArtifactRelease::class),

            MenuItem::section('Deployment'),
            MenuItem::linkToCrud('Deployment', 'fa fa-comment', Deployment::class),

        ];
    }

}

