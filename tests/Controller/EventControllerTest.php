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
        
        $client->xmlHttpRequest('GET', '/api/search/1?cityName=Arras&venueName=Grand-Place&countryId=76&year=2008&p=1');
        
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
}
