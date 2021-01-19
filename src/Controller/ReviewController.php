<?php

namespace App\Controller;

use App\Entity\Band;
use App\Service\SetlistApi;
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

        $content = json_decode($jsonContent, true);

        if (isset($content["parameters"])) {
            $reviews = $reviewRepository->findByLatest($content["parameters"]["orderBy"], $content["parameters"]["limit"]);
            
            return $this->json($reviews, Response::HTTP_OK, [], ['groups' => 'review_get']);
        };
    }

    /**
     * @Route("/api/search/{id}", name="event_search", methods="GET")
     */
    public function search(Band $band, SetlistApi $setlistApi, Request $request)
    {
        $jsonContent = $request->getContent();

        $researchParams = json_decode($jsonContent, true);

        $researchParams['parameters']['artistName'] = $band->getName();

        $responseContent = $setlistApi->fetchEventsList($researchParams);

        return $this->json($responseContent);
    }
}


