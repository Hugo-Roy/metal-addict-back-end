<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Picture;
use App\Service\PictureUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PictureController extends AbstractController
{
    /**
     * @Route("/api/picture/{setlistId}", name="picture_add", methods="POST")
     */
    public function add(Event $event, Request $request,EntityManagerInterface $em, ValidatorInterface $validator, PictureUploader $uploader): Response
    {
        $user = $this->getUser();
        $uploadedFile = $request->files->get('image');
        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Please select a file to upload'
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/*',
                    ]
                ])
            ]
        );
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }
        $filename = $uploader->upload($uploadedFile);
        $picture = new Picture();
        $picture->setPath($filename);
        $picture->setEvent($event);
        $picture->setUser($user);
        $em->persist($picture);
        $em->flush();

        return $this->json($picture->getPath(), Response::HTTP_CREATED);
    }
}