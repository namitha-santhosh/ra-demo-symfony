<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class AdminLoginController extends AbstractController
{
    #[Route('/api/admin/login', name: 'admin_login')]
    public function login(Request $request, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }
        
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

    #[Route('/api/admin/logout', name: 'admin_logout')]
    public function logout()
    {
    // This controller action doesn't need to contain any logic.
    }
}
