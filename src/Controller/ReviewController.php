<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/review", name="review_list")
     */
    public function list(Request $request): Response
    {
        $request->getContent();
        
        return $this->json([
            "test ok"
        ]);
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
