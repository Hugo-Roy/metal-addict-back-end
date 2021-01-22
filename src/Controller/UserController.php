<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="user_list", methods={"GET"})
     */
    public function list(UserRepository $userRepository): Response
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

    /**
     * @Route("/api/user", name="user_add", methods="POST")
     */
    public function add(Request $request, EntityManager $entityManager, SerializerInterface $serializer, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $jsonContent = $request->getContent();

        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        //TODO validate the user properties

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
     * Edit user PUT
     * 
     * @Route("/api/users/{id<\d+>}", name="user_update", methods={"PUT", "PATCH"})
     */
    public function update(User $user = null,UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $jsonContent = $request->getContent();

        // Deserializes given data from front in the User object to modify

        $userMod = $serializer->deserialize($jsonContent, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));
        // Deserialized entity validation
        
        // $errors = $validator->validate($user);

        // Errors generation with 422 status

        // if(count($errors) > 0)
        // {
        //     return $this->json($this->generateErrors($errors), Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        $em->flush();

        return $this->json(["message" => "Informations modifiées."], Response::HTTP_OK);
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
