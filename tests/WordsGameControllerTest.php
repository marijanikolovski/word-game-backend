<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WordsGameControllerTest extends WebTestCase
{
    public function testPoints(): void
    {
        $client = static::createClient();

        //Expects good response - when the word is not entered
        $expected = [
            "response" => "You need to enter a word."
        ];

        $client->request('GET', '/api/words-game');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(json_encode($expected), $client->getResponse()->getContent());

        //Expects good response - when the correct word is entered
        $expected = [
            "points" => 4
        ];

        $client->request('GET', '/api/words-game?term=word');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(json_encode($expected), $client->getResponse()->getContent());

        //Expects good response - when a palindrome is entered
        $expected = [
            "points" => 6
        ];

        $client->request('GET', '/api/words-game?term=level');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(json_encode($expected), $client->getResponse()->getContent());

        //Expects good response - when a almost palindrome is entered
        $expected = [
            "points" => 6
        ];

        $client->request('GET', '/api/words-game?term=levels');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(json_encode($expected), $client->getResponse()->getContent());

        //Expects good response - when the wrong word is entered
        $expected = [
            "response" => "You got 0 points, the word was not found in the dictionary."
        ];

        $client->request('GET', '/api/words-game?term=igra');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(json_encode($expected), $client->getResponse()->getContent());
    }
}
