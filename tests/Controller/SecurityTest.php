<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function createAuthenticatedClient()
    {
        $client = self::createClient();

        $credentials = [
            "username" => "lemmy@lemmy.com",
            "password" => "lemmy"
        ];

        $client->xmlHttpRequest('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($credentials));

        $data = json_decode($client->getResponse()->getContent(), true);
    
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testUserUpdate()
    {
        $body = [
            "oldPassword" => "lemmy",
	        "newPassword" => "killmister"
        ];

        $client = $this->createAuthenticatedClient();

        $client->xmlHttpRequest('PATCH', '/api/user/1');
        
        $response = $client->getResponse();
        dd($response);
        $this->assertJson($response->getContent());
        
        $this->assertResponseIsSuccessful();
    }
}