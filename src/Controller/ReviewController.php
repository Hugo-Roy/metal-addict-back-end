<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="review_list", methods="GET")
     */
    public function list(Request $request, ReviewRepository $reviewRepository): Response
    {
        $limitParameter = intval($request->query->get('limit'));
        $orderParameter = $request->query->get('order');

        if (is_integer($limitParameter) && $limitParameter !== 0 && $orderParameter === 'ASC') {
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
}

