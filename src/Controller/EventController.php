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
        $researchParameters = $request->query->all();

        $researchParameters['artistName'] = $band->getName();
        
        if(isset($researchParameters['countryId'])) {
            $country = $countryRepository->find($researchParameters['countryId']);
        
            $countryCodeParameter = $country->getCountryCode();

            $researchParameters['countryCode'] = $countryCodeParameter;

            unset($researchParameters['countryId']);
        }

        foreach ($researchParameters as $researchParameter => $value) {
            if(!$value) {
                unset($researchParameters[$researchParameter]);
            }
        }

        $responseContent = $setlistApi->fetchEventsList($researchParameters);

        return $this->json($responseContent);
    }
}
