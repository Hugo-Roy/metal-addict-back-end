<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Picture;
use App\Repository\EventRepository;
use App\Service\PictureUploader;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController
{
    /**
     * @Route("api/picture", name="picture_list", methods="GET")
     */
    public function list(Request $request, PictureRepository $pictureRepository, EventRepository $eventRepository, UserRepository $userRepository, PictureUploader $pictureUploader): Response
    {
        $researchParameters = $request->query->all();
        
        if(!isset($researchParameters['user']) && !isset($researchParameters['setlistId']))
        {
            return $this->json('User && Event Parameters are missing');
        }
        else if(!isset($researchParameters['user']) && isset($researchParameters['setlistId']))
        {
            $event = $eventRepository->findOneBy(['setlistId' => $researchParameters['setlistId'],]);

            $currentPicture = $pictureRepository->findByEvent($researchParameters['order'], $event);

            return $this->json($currentPicture, Response::HTTP_OK, [], ["groups" => "picture_get"]);

        }
        else if(isset($researchParameters['user']) && !isset($researchParameters['setlistId']))
        {
            $user = $userRepository->findOneBy(['id' => $researchParameters['user'],]);

            $currentPicture = $pictureRepository->findByUser($researchParameters['order'], $user);

            return $this->json($currentPicture, Response::HTTP_OK, [], ["groups" => "picture_get"]);
        }
        else if(isset($researchParameters['user']) && isset($researchParameters['setlistId']))
        {
            $event = $eventRepository->findOneBy(['setlistId' => $researchParameters['setlistId'],]);

            $user = $userRepository->findOneBy(['id' => $researchParameters['user'],]);

            $currentPicture = $pictureRepository->findByUserAndEvent($researchParameters['order'], $user, $event);

            return $this->json($currentPicture, Response::HTTP_OK, [], ["groups" => "picture_get"]);
        }
    }

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

    /**
     * @Route("/api/picture/{id}", name="picture_delete", methods="DELETE")
     */
    public function delete(Picture $picture, EntityManagerInterface $em, PictureUploader $pictureUploader, Filesystem $filesystem)
    {
        $this->denyAccessUnlessGranted('delete', $picture);

        $path = $pictureUploader->getTargetDirectory();
        $picturePath = $picture->getPath();
        $toRemove = $path . '/' . $picturePath;
        
        $filesystem->remove($toRemove);
        $em->remove($picture);
        $em->flush();

        return $this->json(Response::HTTP_OK);
    }
}
