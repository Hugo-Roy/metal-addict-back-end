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

        $client->request(
            'POST', 
            '/api/login', 
            [], 
            [],
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode($credentials)
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testUserUpdate()
    {
        $body = [
            "nickname" => "lemmyMe",
        ];

        $client = $this->createAuthenticatedClient();

        $client->xmlHttpRequest(
            'PATCH', 
            '/api/user/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode($body)
        );
        
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        
        $this->assertSame(json_encode(["message" => "Informations modifiées."]), $response->getContent());
    }

    public function testWrongUserUpdate()
    {
        $body = [
            "nickname" => "lemmyMe",
        ];

        $client = $this->createAuthenticatedClient();

        $client->xmlHttpRequest(
            'PATCH', 
            '/api/user/2',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'], 
            json_encode($body)
        );
        
        $this->assertResponseStatusCodeSame(403);
    }
}