<?php

// WIP not implemented yet for the current version

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
   /**
     * @Route("/email/user/{id}", name="mailer_confirm")
     */
    public function confirm(User $user)
    {
        //TODO change "verified" to true in User entity

        //TODO redirect to url login page
        return $this->redirect('');
    }
}
