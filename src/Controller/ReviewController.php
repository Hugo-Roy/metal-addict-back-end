<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="review_list")
     */
    public function list(Request $request, SerializerInterface $serializer): Response
    {
        $jsonContent = $request->getContent();

        $content = json_decode($jsonContent);
        
        return $this->json($content);
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
