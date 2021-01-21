<?php

namespace App\Controller;

use App\Repository\BandRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BandController extends AbstractController
{
    /**
     * @Route("/api/band", name="band_list", methods="GET")
     */
    public function list(BandRepository $bandRepository): Response
    {
        $bands = $bandRepository->findAll();
        
        return $this->json($bands, Response::HTTP_OK, [], ['groups' => 'band_get']);
    }
}
