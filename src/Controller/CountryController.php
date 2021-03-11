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
     * @Route("/api/country", name="country_list", methods="GET")
     */
    public function list(CountryRepository $countryRepository): Response
    {
        $countries = $countryRepository->findByAlphabeticalOrder();
        
        return $this->json($countries, Response::HTTP_OK, [], ['groups' => 'country_get']);
    }
}
