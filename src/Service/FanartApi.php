<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FanartApi
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
     * Get images for a band
     */
    public function fetchImages($mbId)
    {
        $response = $this->client->request(
            'GET',
            'http://webservice.fanart.tv/v3/music/'.$mbId,
            [
                'headers' => [
                    'x-api-key' => '24LpnzjvbvX5AsxiSJS9ZsPkATNgtY2996EH',
                    'Accept' =>'application/json',
                    'Accept-Language' => 'fr',
                ],
                'query' => [
                    'api_key' => 'e9c3259ee8254da9e32fbe6bbbc5e047',
                ],
            ]
        );

        if($response->getStatusCode() === 404) {
            return $this->null;
        }
       
        return $response->toArray();
    }
}