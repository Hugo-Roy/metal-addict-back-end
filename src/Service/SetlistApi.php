<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SetlistApi
{
    private $client;

    private $emptyEventList = [
        "type" => "setlists",
        "itemsPerPage" => 20,
        "page" => 1,
        "total" => 0,
        "setlist" => [],
    ];

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get a list of events
     *
     * @param array $params search parameters
     */
    public function fetchEventsList(array $params)
    {
        $response = $this->client->request(
            'GET',
            'https://api.setlist.fm/rest/1.0/search/setlists',
            [
                'headers' => [
                    'x-api-key' => '24LpnzjvbvX5AsxiSJS9ZsPkATNgtY2996EH',
                    'Accept' =>'application/json',
                    'Accept-Language' => 'fr',
                ],
                'query' => $params,
            ]
        );

        if($response->getStatusCode() === 404) {
            return $this->emptyEventList;
        }
       
        return $response->toArray();
    }

    /**
     * Get one event
     * 
     * @param string $setlistId
     */
    public function fetchOneEvent($setlistId)
    {
        $response = $this->client->request(
            'GET',
            'https://api.setlist.fm/rest/1.0/setlist/'.$setlistId,
            [
                'headers' => [
                    'x-api-key' => '24LpnzjvbvX5AsxiSJS9ZsPkATNgtY2996EH',
                    'Accept' =>'application/json',
                    'Accept-Language' => 'fr',
                ],
            ]
        );

        if($response->getStatusCode() === 404) {
            return null;
        }

        return $response->toArray();
    }

    /**
     * Get a complete list of all supported countries
     */
    public function getCountries()
    {
        $response = $this->client->request(
            'GET',
            'https://api.setlist.fm/rest/1.0/search/countries',
            [
                'headers' => [
                    'x-api-key' => '24LpnzjvbvX5AsxiSJS9ZsPkATNgtY2996EH',
                    'Accept' =>'application/json',
                    'Accept-Language' => 'fr',
                ],
            ]
        );
       
        return $response->toArray();
    }
}