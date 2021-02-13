<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    public function testSearchEvent()
    {
        $client = static::createClient();
        
        $queryParameters = [
            'cityName' => 'Arras',
            'venueName' => 'Grand-Place',
            'CountryId' => '76',
            'year' => '2008',
            'p' => '1',
        ];
        
        $client->xmlHttpRequest('GET', '/api/search/1', $queryParameters);
        
        $response = $client->getResponse();
        
        $this->assertJson($response->getContent());
        
        $this->assertResponseIsSuccessful();
    }
    
    public function testShowEvent()
    {
        $client = static::createClient();
        
        $client->xmlHttpRequest('GET', '/api/event/3bea6ccc');

        $response = $client->getResponse();
        
        $this->assertJson($response->getContent());
        
        $this->assertResponseIsSuccessful();
    }

    public function testListEvent()
    {
        $client = static::createClient();

        $queryParameters = [
            'user' => 1,
            'order' => 'ASC',
        ];
        
        $client->xmlHttpRequest('GET', '/api/event', $queryParameters);

        $response = $client->getResponse();
        
        $this->assertJson($response->getContent());
        
        $this->assertResponseIsSuccessful();
    }
}
