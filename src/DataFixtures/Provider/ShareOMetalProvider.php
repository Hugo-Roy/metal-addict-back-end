<?php

namespace App\DataFixtures\Provider;

class ShareOMetalProvider 
{
    private $bands = [
        "All Them Witches",
        "Idles",
        "Eyehategod",
        "Meshuggah",
        "Pantera",
        "Judas Priest",
        "Down",
        "Metallica",
        "Pearl Jam",
        "Cannibal Corpse",
        "Queens Of The Stone Age",
        "Iron Maiden",
    ];

    private $countries = [
        "France" => "FR",
        "Etats-Unis" => "US",
        "Allemagne" => "DE",
        "Belgique" => "BE",
        "Luxembourg" => "LU",
        "Grande-Bretagne" => "GB",
    ];

    private $cities = [
        "Besançon",
        "Lille",
        "Paris",
        "Nantes",
        "Rennes",
        "Marseilles",
        "Lyon",
        "Bruxelles",
        "Londres",
        "Berlin",
        "Luxembourg",
        "New-York",
        "Los Angeles",
    ];

    private $venues = [
        "La Vapeur",
        "Aeronef",
        "Bataclan",
        "Paris Bercy",
        "Whisky à Gogo",
        "Koko",
        "Berghain",
        "Ancienne Belgique",
        "Rockhal",
        "Le Ferailleur",
        "Le Zenith",
        "Parc des Princes",
        "Ninkasi",
    ];

    public function getBands()
    {
        return $this->bands;
    }

    public function getCountries()
    {
        return $this->countries;
    }

    public function getCity()
    {
        return $this->cities[array_rand($this->cities)];
    }

    public function getVenue()
    {
        return $this->venues[array_rand($this->venues)];
    }
}