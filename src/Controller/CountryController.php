<?php

namespace App\Controller;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Service\SetlistApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{
     /**
     * @Route("/api/country", name="country_list", methods="GET")
     */
    public function list(CountryRepository $countryRepository): Response
    {
        $countries = $countryRepository->findAll();
        
        return $this->json($countries, Response::HTTP_OK, [], ['groups' => 'country_get']);
    }

    /**
     * @Route("/api/country/{id}", name="country")
     */
    public function searchCountry(Country $country, SetlistApi $setlistApi, Request $request)
    {
        $jsonContent = $request->getContent();

        $researchParams = json_decode($jsonContent, true);

        // $researchParams['parameters']['CountryName'] = $country->getName();

        $researchParams['parameters']['countryCode'] = $country->getCountryCode();
        
        $responseContent = $setlistApi->fetchEventsList($researchParams);

        return $this->json($responseContent);
    }
}
