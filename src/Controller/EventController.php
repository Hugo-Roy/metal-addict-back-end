<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Event;
use App\Service\SetlistApi;
use App\Repository\CountryRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        if(!empty($researchParameters['countryId'])) {
            $country = $countryRepository->find($researchParameters['countryId']);
        
            $countryCodeParameter = $country->getCountryCode();

            $researchParameters['countryCode'] = $countryCodeParameter;

            unset($researchParameters['countryId']);
        }
        else {
            $researchParameters['countryCode'] = null;
        }


        
        $newResearchParams = [
            "artistName" => $researchParameters["artistName"],
            "cityName" => $researchParameters["cityName"],
            "countryCode" => $researchParameters["countryCode"],
            "venueName" => $researchParameters["venueName"],
            "year" => $researchParameters["year"],
            "p" => $researchParameters["p"],
        ];

        foreach ($newResearchParams as $newResearchParam => $value) {
            if(!$value) {
                unset($newResearchParams[$newResearchParam]);
            }
        }


        $responseContent = $setlistApi->fetchEventsList($newResearchParams);

        return $this->json($responseContent);
    }

    /**
     * @Route("/api/event/{setlistId}", name="event_show", methods="GET")
     */
    public function show($setlistId, SetlistApi $setlistApi)
    {   
        $responseContent = $setlistApi->fetchOneEvent($setlistId);

        return $this->json($responseContent);
    }

    /**
     * @Route("/api/event/{setlistId}", name="event_add", methods="POST")
     */
    public function add($setlistId, EntityManagerInterface $em, EventRepository $eventRepository, SetlistApi $setlistApi)
    {
        $event = $eventRepository->findOneBy(['setlistId' => $setlistId]);
        $user = $this->getUser();
        dd($user);
        if ($event !== null && $user !== null) {
            $user->addEvent($event);
            $em->flush();
        }
        elseif ($event === null && $user !== null) {
            $eventProperties = $setlistApi->fetchOneEvent($setlistId);
            dd($eventProperties);
        }
        
    }
}
