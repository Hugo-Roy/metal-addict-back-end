<?php

namespace App\Controller;

use App\Entity\Band;
use App\Repository\CountryRepository;
use App\Service\SetlistApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/api/search/{id}", name="event_search", methods="GET")
     */
    public function search(Band $band, SetlistApi $setlistApi, Request $request, CountryRepository $countryRepository)
    {
        $jsonContent = $request->getContent();

        $researchParams = json_decode($jsonContent, true);

        $researchParams['parameters']['artistName'] = $band->getName();

        if(isset($researchParams['parameters']['countryId'])) {
            $country = $countryRepository->find($researchParams['parameters']['countryId']);
        
            $researchParams['parameters']['countryCode'] = $country->getCountryCode();

            unset($researchParams['parameters']['countryId']);
        }
        

        $responseContent = $setlistApi->fetchEventsList($researchParams);

        return $this->json($responseContent);
    }
}
