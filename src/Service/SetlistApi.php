<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SetlistApi
{
    private $client;

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
            'https://api.setlist.fm/rest/1.0/setlist/',
            [
                'headers' => [
                    'x-api-key' => '24LpnzjvbvX5AsxiSJS9ZsPkATNgtY2996EH',
                    'Accept' =>'application/json',
                    'Accept-Language' => 'fr',
                ],
            ]
        );

        $this->client;
        return $response->toArray();
    }
}