<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class AdminLoginController extends AbstractController
{
    #[Route('/admin/login', name: 'admin_login')]
    public function login(Request $request): Response
    {
        // Render your login form here...
        return $this->render('admin/login.html.twig', [
            'error' => $this->getAuthenticationError($request),
        ]);
    }

    private function getAuthenticationError(Request $request): ?string
    {
        // Check for authentication errors and return an error message if needed.
        // You can access the error message from the session.
        return $request->getSession()->get(Security::AUTHENTICATION_ERROR);
    }
}
