<?php

namespace cobe\CommonBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetciudad()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/ciudad/');
    }

    public function testGetestado()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/estado/');
    }

    public function testGetetiqueta()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/etiqueta/');
    }

    public function testGetidioma()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/idioma/');
    }

    public function testGetpais()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/pais/');
    }

    public function testGetrol()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/rol/');
    }

    public function testGettipo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/tipo/');
    }

    public function testGettraduccion()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api-v1/traduccion/');
    }

}
