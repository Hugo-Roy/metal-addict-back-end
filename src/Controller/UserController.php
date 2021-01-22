<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_users", methods={"GET"})
     */
    public function listAll(UserRepository $userRepository): Response
    {
        // $users = $userRepository->findAll();
        
        return $this->json($userRepository->findAll(), Response::HTTP_OK, [], ["groups" => "user_get"]);
    }

    /**
     * @Route("/api/user/{id}", name="user_show", methods="GET")
     */
    public function show(User $user)
    {
        return $this->json($user, Response::HTTP_OK, [], ["groups" => "user_get"]);
    }
}
