<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Review;
use App\Repository\EventRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewController extends AbstractController
{
    /**
     * Renders a list of reviews by given query parameters
     * 
     * @Route("/api/review", name="review_list", methods="GET")
     */
    public function list(Request $request, ReviewRepository $reviewRepository, EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        $limitParameter  = intval($request->query->get('limit'));
        $orderParameter  = $request->query->get('order');
        $eventParameter  = $request->query->get('setlistId');
        $userParameter   = $request->query->get('user');

        //Checks if setlistId is set and in the correct type
        if (is_string($eventParameter) && $eventParameter !== '' && ($orderParameter === 'ASC' || $orderParameter === 'DESC') && $userParameter == null) 
        {
            $reviews = $reviewRepository->findByEvent($orderParameter, $eventParameter);

            return $this->json($reviews, Response::HTTP_OK, [], ['groups' => 'review_get']);
        }


        // Checks if setlistId and user are set
        else if (is_string($eventParameter) && $eventParameter !== '' && ($orderParameter === 'ASC' || $orderParameter === 'DESC') && isset($userParameter) ) 
        {
            $event = $eventRepository->findOneBy(["setlistId" => $eventParameter]);
            
            $user = $userRepository->findOneBy(["id" => $userParameter]);

            $reviews = $reviewRepository->findBy(["event" => $event, "user" => $user], ['createdAt' => $orderParameter]);

            return $this->json($reviews, Response::HTTP_OK, [], ['groups' => 'review_get']);
        }

        //Checks if only the user parameter is set
        else if (($orderParameter === 'ASC' || $orderParameter === 'DESC') && (isset($userParameter) && $userParameter !== ""))
        {
            $user = $userRepository->findOneBy(["id" => $userParameter]);

            $reviews = $reviewRepository->findBy(["user" => $user], ['createdAt' => $orderParameter]);

            return $this->json($reviews, Response::HTTP_OK, [], ["groups" => "review_get"]);
        }

        // Limits the number of rendered reviews
        elseif (is_integer($limitParameter) && $limitParameter !== 0 && ($orderParameter === 'ASC' || $orderParameter === 'DESC')) 
        {
            $reviews = $reviewRepository->findByLatest($orderParameter, $limitParameter);
            
            return $this->json($reviews, Response::HTTP_OK, [], ['groups' => 'review_get']);
        };


        //TODO handle errors
    }

    /**
     * Renders a review and its associated data
     * 
     * @Route("/api/review/{id}", name="review_show", methods="GET")
     */
    public function show(Review $review)
    {
        return $this->json($review, Response::HTTP_OK, [], ['groups' => 'review_get']);
    }

    /**
     * Adds a review for given event and renrs it
     * 
     * @Route("/api/review/{setlistId}", name="review_add", methods="POST")
     */
    public function add(Event $event = null, Request $request,ValidatorInterface $validator, SerializerInterface $serializer,ReviewRepository $reviewRepository, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if($reviewRepository->findByUserAndEvent($user, $event) !== null) {
            return $this->json('The user already wrote a review for this event', Response::HTTP_CONFLICT);
        }

        if($user->getEvents()->contains($event) === false) {
            return $this->json('The user is not associated with the event', Response::HTTP_CONFLICT);
        }

        $jsonContent = $request->getContent();
        
        $review = $serializer->deserialize($jsonContent, Review::class, 'json');

        $errors = $validator->validate($review);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $review->setUser($user);

        $review->setEvent($event);
        
        $em->persist($review);
    
        $em->flush();

        return $this->json($review, Response::HTTP_CREATED, ['Location' => $this->generateUrl('review_show', ['id' => $review->getId()])], ['groups' => 'review_get']);
    }

    /**
     * Updates the review and renders it
     * 
     * @Route("/api/review/{id<\d+>}", name="review_update", methods={"PUT", "PATCH"})
     */
    public function update(Review $review, EntityManagerInterface $em,ValidatorInterface $validator, SerializerInterface $serializer, Request $request)
    {
        $this->denyAccessUnlessGranted('update', $review);

        $jsonContent = $request->getContent();

        $serializer->deserialize($jsonContent, Review::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $review]);

        $errors = $validator->validate($review);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $em->flush();
        
        return $this->json($review, Response::HTTP_CREATED, ['Location' => $this->generateUrl('review_show', ['id' => $review->getId()])], ['groups' => 'review_get']);
    }

    /**
     * Deletes a review
     * 
     * @Route("api/review/{id}", name="review_delete", methods="DELETE")
     */
    public function delete(Review $review, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('delete', $review);

        $em->remove($review);
        $em->flush();

        return $this->json(Response::HTTP_OK);
    }
}
