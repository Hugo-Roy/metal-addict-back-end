<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="review_list")
     */
    public function list(Request $request, SerializerInterface $serializer, ReviewRepository $reviewRepository): Response
    {
        $jsonContent = $request->getContent();

        $content = json_decode($jsonContent, true);

        if (isset($content["parameters"])) {
            $reviews = $reviewRepository->findByLatest($content["parameters"]["orderBy"], $content["parameters"]["limit"]);
            
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


https://github.com/symfony/symfony/issues/35660