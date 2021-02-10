<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Event;
use App\Repository\BandRepository;
use App\Service\SetlistApi;
use App\Repository\CountryRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\FanartApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EventController extends AbstractController
{
    /**
     * Renders a Json list of events for given parameters
     * 
     * @Route("/api/search/{id}", name="event_search", methods="GET")
     */
    public function search(Band $band, SetlistApi $setlistApi, Request $request, CountryRepository $countryRepository)
    {
        $researchParameters = $request->query->all();

        $researchParameters['artistMbid'] = $band->getMusicbrainzId();

        //Checks if a countryId is setted and then get its CountryCode
        if(!empty($researchParameters['countryId'])) {
            $country = $countryRepository->find($researchParameters['countryId']);
        
            $countryCodeParameter = $country->getCountryCode();

            $researchParameters['countryCode'] = $countryCodeParameter;

            unset($researchParameters['countryId']);
        }
        else {
            $researchParameters['countryCode'] = null;
        }
        
        //Orders the parameters array
        $newResearchParams = [
            "artistMbid" => $researchParameters["artistMbid"],
            "cityName" => $researchParameters["cityName"],
            "countryCode" => $researchParameters["countryCode"],
            "venueName" => $researchParameters["venueName"],
            "year" => $researchParameters["year"],
            "p" => $researchParameters["p"],
        ];

        //Unsets empty parameters
        foreach ($newResearchParams as $newResearchParam => $value) {
            if(!$value) {
                unset($newResearchParams[$newResearchParam]);
            }
        }

        $responseContent = $setlistApi->fetchEventsList($newResearchParams);

        return $this->json($responseContent);
    }

    /**
     * Renders an event and its associated data
     * 
     * @Route("/api/event/{setlistId}", name="event_show", methods="GET")
     */
    public function show($setlistId, SetlistApi $setlistApi, FanartApi $fanartApi)
    {  
        $setlist = $setlistApi->fetchOneEvent($setlistId);

        if ($setlist === null) {
            return $this->json('Setlist Not Found.', Response::HTTP_NOT_FOUND);
        }
        
        $bandImages = $fanartApi->fetchImages($setlist['artist']['mbid']);

        // Merges the arrays
        $responseContent = [
            'setlist' => $setlist,
            'bandImages' => $bandImages,
        ];

        return $this->json($responseContent);
    }

    /**
     * Renders a Json list of all events for a given user by a given order
     * 
     * @Route("/api/event", name="event_list", methods="GET")
     */
    public function listByUser(Request $request, UserRepository $userRepository, EventRepository $eventRepository)
    {
        $researchParameters['user']  = $request->query->get('user');
        $researchParameters['order'] = $request->query->get('order');

        $user = $userRepository->find($researchParameters['user']);
        
        $events = $user->getEvents();
        
        $events = $eventRepository->findByUser($user, $researchParameters['order']);

        return $this->json($events, Response::HTTP_OK, [], ["groups" => "event_get"]);
    }

    /**
     * Adds an event to the user profile
     * 
     * @Route("/api/event/{setlistId}", name="event_add", methods="POST")
     */
    public function add($setlistId, EntityManagerInterface $em, EventRepository $eventRepository,CountryRepository $countryRepository, BandRepository $bandRepository, SetlistApi $setlistApi)
    {
        $event = $eventRepository->findOneBy(['setlistId' => $setlistId]);
        $user = $this->getUser();

        if ($event === null && $user !== null) {
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

            return $this->json('The event is created and associated with the user.', Response::HTTP_CREATED);
        }
        elseif ($event->getUsers()->contains($user))  {
            return $this->json('The user is already associated with the event', Response::HTTP_FORBIDDEN);
        }
        elseif ($event !== null && $user !== null) {
            $event->addUser($user);
            $em->flush();

            return $this->json('The user is associated with the event', Response::HTTP_CREATED);
        }
    }
}
