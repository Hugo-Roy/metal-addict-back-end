<?php

namespace App\Controller;

use App\Entity\Band;
use App\Repository\BandRepository;
use App\Service\FanartApi;
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
        $bands = $bandRepository->findByAlphabeticalOrder();
        
        return $this->json($bands, Response::HTTP_OK, [], ['groups' => 'band_get']);
    }

    /**
     * @Route("api/band/{id}", name="band_get_images", methods="GET")
     */
    public function getImages(Band $band, FanartApi $fanartApi)
    {
        $mbId = $band->getMusicbrainzId();

        $responseArray = $fanartApi->fetchImages($mbId);

        return $this->json($responseArray);
    }
}
