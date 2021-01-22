<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Event;
use App\Service\SetlistApi;
use App\Repository\CountryRepository;
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

    /**
     * @Route("/api/event/{setlistId}", name="event_show", methods="GET")
     */
    public function show(Request $request, SetlistApi $setlistApi)
    {   
        $setlistId = $request->attributes;

        $responseContent = $setlistApi->fetchOneEvent($setlistId);

        return $this->json($responseContent);
    }
}
