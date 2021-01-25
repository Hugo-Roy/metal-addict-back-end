<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="review_list", methods="GET")
     */
    public function list(Request $request, ReviewRepository $reviewRepository): Response
    {
        $limitParameter = intval($request->query->get('limit'));
        $orderParameter = $request->query->get('order');

        if (is_integer($limitParameter) && $limitParameter !== 0 && ($orderParameter === 'ASC' || $orderParameter === 'DESC')) {
            $reviews = $reviewRepository->findByLatest($orderParameter, $limitParameter);
            
            return $this->json($reviews, Response::HTTP_OK, [], ['groups' => 'review_get']);
        };

        //TODO handle errors
    }

    /**
     * @Route("/api/review/{id}", name="review_show", methods="GET")
     */
    public function show(Review $review)
    {
        return $this->json($review, Response::HTTP_OK, [], ['groups' => 'review_get']);
    }

    /**
     * @Route("/api/review", name="review_add", methods="POST")
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonContent = $request->getContent();

        $review = $serializer->deserialize($jsonContent, Review::class, 'json');

        // Validation de l'entité désérialisée
        //$errors = $validator->validate($review);
        // Génération des erreurs
        // if (count($errors) > 0) {
        //     // On retourne le tableau d'erreurs en Json au front avec un status code 422
        //     return $this->json($this->generateErrors($errors), Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        $em->persist($review);
    
        $em->flush();

        return $this->json($review, Response::HTTP_CREATED, ['Location' => $this->generateUrl('review_show', ['id' => $review->getId()])], ['groups' => 'review_get']);
    }
}

