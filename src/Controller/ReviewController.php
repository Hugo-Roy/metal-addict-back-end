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
     * @Route("/api/review", name="review_list", methods="GET")
     */
    public function list(Request $request, ReviewRepository $reviewRepository): Response
    {
        $jsonContent = $request->getContent();
        //dd($jsonContent);
        $content = json_decode($jsonContent, true);
        
        if (isset($content["parameters"])) {
            $reviews = $reviewRepository->findByLatest($content["parameters"]["orderBy"], $content["parameters"]["limit"]);
            //dd($reviews);
            //return new Response('test ok');
            return $this->json($reviews, Response::HTTP_OK, [], ['groups' => 'review_get']);
        };
        return new Response('test ok'); 
    }
}
