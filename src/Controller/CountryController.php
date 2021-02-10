<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{
    /**
     * Renders a Json list of all the countries sorted by alphabetical order
     * 
     * @Route("/api/country", name="country_list", methods="GET")
     */
    public function list(CountryRepository $countryRepository): Response
    {
        $countries = $countryRepository->findByAlphabeticalOrder();
        
        return $this->json($countries, Response::HTTP_OK, [], ['groups' => 'country_get']);
    }
}
