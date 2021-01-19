<?php

namespace App\Service;

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
    public function fetchEventsList()
    {
        $response = $this->client->request(
            'GET',
            'https://api.setlist.fm/rest/1.0/search/setlists?artistMbid=b574bfea-2359-4e9d-93f6-71c3c9a2a4f0&countryCode=FR&p=1',
            [
                'headers' => [
                    'x-api-key' => '24LpnzjvbvX5AsxiSJS9ZsPkATNgtY2996EH',
                    'Accept' =>'application/json',
                    'Accept-Language' => 'fr',
                ],
            ]
        );

        $content = $response->toArray();

        return $content;
    }
}