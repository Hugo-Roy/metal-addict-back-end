<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Service\PictureUploader;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user", name="user_list", methods={"GET"})
     */
    public function listUser(EventRepository $eventRepository, Request $request): Response
    {
        $researchParameters = $request->query->all();
        
        $currentEvent = $eventRepository->findOneBy(['setlistId' => $researchParameters['setlistId']]);
        
        if($currentEvent === null) 
        {
            return $this->json('This event does not exist', Response::HTTP_NOT_FOUND);
        }

        return $this->json($currentEvent->getUsers(), Response::HTTP_OK, [], ["groups" => "user_get"]);
    }

    /**
     * @Route("/api/user/{id}", name="user_show", methods="GET")
     */
    public function show(User $user)
    {
        return $this->json($user, Response::HTTP_OK, [], ["groups" => "user_get"]);
    }

    /**
     * @Route("/api/user", name="user_add", methods="POST")
     */
    public function add(Request $request,UserRepository $userRepository, EntityManagerInterface $entityManager,ValidatorInterface $validator, SerializerInterface $serializer, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $jsonContent = $request->getContent();

        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        $errors = $validator->validate($user, null, 'registration');

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if($userRepository->findOneBy(['email' => $user->getEmail()])) {
            return $this->json('This email is already used', Response::HTTP_CONFLICT);
        }

        $user->setRoles = ['ROLE_USER'];
        $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));

        $entityManager->persist($user);
        $entityManager->flush();
    
        return $this->redirectToRoute(
            'user_show',
            [
                'id' => $user->getId()
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/api/user/{id<\d+>}", name="user_update", methods={"PUT", "PATCH"})
     */
    public function update(User $user, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('update', $user);
        
        $jsonContent = $request->getContent();
        
        $content = json_decode($jsonContent, true);
        
        $user = $serializer->deserialize($jsonContent, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        
        if(isset($content['newPassword']) && isset($content['oldPassword'])) {
            if($userPasswordEncoder->isPasswordValid($user, $content['oldPassword']) === false) {
                return $this->json('wrong password', Response::HTTP_UNAUTHORIZED);
            }
            $user->setPassword($content['newPassword']);

            $errors = $validator->validate($user, null, 'registration');
        }
        else {
            $errors = $validator->validate($user, null, 'update');
        }
        
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            
            return $this->json($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if(isset($content['newPassword']) && isset($content['oldPassword'])){
            $user->setPassword($userPasswordEncoder->encodePassword($user, $content['newPassword']));
        } 
        
        $em->flush();

        return $this->json(["message" => "Informations modifiées."], Response::HTTP_OK);
    }

    /**
     * @Route("/api/user/avatar/{id<\d+>}", name="user_add_avatar", methods={"POST"})
     */
    public function addAvatar(User $user, Request $request, EntityManagerInterface $em, ValidatorInterface $validator, PictureUploader $uploader, Filesystem $filesystem)
    {
        $this->denyAccessUnlessGranted('avatar', $user);
        
        $avatar = $user->getAvatar();

        if ($avatar !== null) {
            $path = $uploader->getTargetDirectory();
            $fullPath = $path . '/' . $avatar;
            $filesystem->remove($fullPath);
        }

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
        $user->setAvatar($filename);
        $em->flush();

        return $this->json($user->getAvatar(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/user/avatar/{id<\d+>}", name="user_delete_avatar", methods={"DELETE"})
     */
    public function deleteAvatar(User $user, EntityManagerInterface $em, PictureUploader $uploader, Filesystem $filesystem)
    {
        $this->denyAccessUnlessGranted('avatar', $user);

        $path = $uploader->getTargetDirectory();
        $avatar = $user->getAvatar();

        if ($avatar !== null) {
            $fullPath = $path . '/' . $avatar;
            $user->setAvatar(null);
            $filesystem->remove($fullPath);
            $em->flush();
    
            return $this->json('Avatar Removed', Response::HTTP_OK);
        }

        return $this->json('No avatar found', Response::HTTP_NOT_FOUND);
    }

    /**
     * Errors generation
     */
    private function generateErrors($errors)
    {
        // If there is more than 0 error
        if(count($errors) > 0)
        {
            $errorsList = [];

            // Extracts each errors
            foreach($errors as $error)
            {
                $errorsList[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $errorsList;
    }
}
