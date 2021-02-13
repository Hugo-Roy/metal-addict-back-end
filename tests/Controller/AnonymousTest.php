<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonymousTest extends WebTestCase
{
    public function postUrlProvider()
    {
        $types = [
            'picture',
            'event',
            'review',
        ];

        $setlistId = '6b876a6e';

        foreach ($types as $type) {
            yield ['/api/' . $type . '/' . $setlistId];
        }
    }

    /**
     * @dataProvider postUrlProvider
     */
    public function testPostAuthorization($url)
    {
        $client = self::createClient();

        $client->xmlHttpRequest('POST', $url);

        $response = $client->getResponse();

        $this->assertJson($response->getContent());
        
        $this->assertResponseStatusCodeSame(401);
    }

    public function deleteUrlProvider()
    {
        $types = [
            'picture',
            'review',
        ];

        $setlistId = '6b876a6e';

        foreach ($types as $type) {
            yield ['/api/' . $type . '/1'];
        }
    }

    /**
     * @dataProvider deleteUrlProvider
     */
    public function testDeleteAuthorization($url)
    {
        $client = self::createClient();

        $client->xmlHttpRequest('DELETE', $url);

        $response = $client->getResponse();

        $this->assertJson($response->getContent());
        
        $this->assertResponseStatusCodeSame(401);
    }
}