<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="app_login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        
        return $this->json([
            'email' => $user->getEmail(),
            'nickname' => $user->getNickname(),
            'roles' => $user->getRoles(),
        ]);
    }
}
