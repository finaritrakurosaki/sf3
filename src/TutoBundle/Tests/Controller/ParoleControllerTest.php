<?php

namespace TutoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParoleControllerTest extends WebTestCase
{
    public function testAddparole()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addParole');
    }

    public function testListparole()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listParole');
    }

}
