<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function Symfony\Component\Translation\t;

class LoginController extends AbstractController
{
    #[Route('/{_locale<en|fr>}/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('admin'),
            'username_label' => t('SECURITY.LOGIN'),
            'password_label' => t('SECURITY.PASSWORD'),
            'sign_in_label' => t('SECURITY.LOG_IN'),
        ]);
    }
}