<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Event;
use App\Repository\BandRepository;
use App\Service\SetlistApi;
use App\Repository\CountryRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/api/event", name="event_list", methods="GET")
     */
    public function list(Request $request, UserRepository $userRepository, EventRepository $eventRepository)
    {
        $researchParameters = $request->query->get('user');

        $user = $userRepository->findOneBy(["id" => $researchParameters]);
        
        $events = $user->getEvents();
        return $this->json($events, Response::HTTP_OK, [], ["groups" => "event_get"]);
    }

    /**
     * @Route("/api/event/{setlistId}", name="event_add", methods="POST")
     */
    public function add($setlistId, EntityManagerInterface $em, EventRepository $eventRepository,CountryRepository $countryRepository, BandRepository $bandRepository, SetlistApi $setlistApi)
    {
        $event = $eventRepository->findOneBy(['setlistId' => $setlistId]);
        $user = $this->getUser();

        if ($event->getUsers()->contains($user))  {
            return $this->json('The user is already associated with the event', Response::HTTP_FORBIDDEN);
        }
      
        elseif ($event !== null && $user !== null) {
            $event->addUser($user);
            $em->flush();

            return $this->json('The user is associated with the event', Response::HTTP_CREATED);
        }
        elseif ($event === null && $user !== null) {
            $eventProperties = $setlistApi->fetchOneEvent($setlistId);
            $event = new Event();
            $event->setSetlistId($eventProperties['id']);
            $event->setVenue($eventProperties['venue']['name']);
            $event->setCity($eventProperties['venue']['city']['name']);
            $event->setDate(new \DateTime($eventProperties['eventDate']));
            $event->setBand($bandRepository->findOneBy(['name' => $eventProperties['artist']['name']]));
            $event->setCountry($countryRepository->findOneBy(['countryCode' => $eventProperties['venue']['city']['country']['code']]));
            $event->addUser($user);
            $em->persist($event);
            $em->flush();

            return $this->json('The event is cretaed with his associated user.', Response::HTTP_CREATED);
        }
    }
}
