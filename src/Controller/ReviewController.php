<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="review_list")
     */
    public function list(Request $request, ReviewRepository $reviewRepository): Response
    {
        $jsonContent = $request->getContent();

        $content = json_decode($jsonContent, true);

        if (isset($content["parameters"])) {
            $reviews = $reviewRepository->findByLatest($content["parameters"]["orderBy"], $content["parameters"]["limit"]);
            
            dump($reviews);
            return $this->json($reviews, Response::HTTP_OK, [], ['groups' => 'review_get']);
        };
    }
    

    // [
    //     {
    //         "parameters": {
    //             "limit": 6,
    //             "orderBy": "ASC"
    //         }
    //     }
    // ]
}


