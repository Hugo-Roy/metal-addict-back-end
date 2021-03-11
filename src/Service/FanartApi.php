<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FanartApi
{
    private $client;

    private $apiKey;

    private $emptyContent = [
        'name' => '',
        'mbid_id' => '',
        'albums' => [],
        'artistthumb' => [],
        'hdmusiclogo' => [],
        'musiclogo' => [],
        'musicbanner' => [],
        'artistbackground' => [],
    ];

    public function __construct(HttpClientInterface $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
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
                    'Accept' =>'application/json',
                ],
                'query' => [
                    'api_key' => $this->apiKey,
                ],
            ]
        );

        if($response->getStatusCode() === 404) {
            return $this->emptyContent;
        }
       
        return $response->toArray();
    }
}