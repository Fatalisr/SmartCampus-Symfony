<?php

namespace App\Tests;

use App\Service\ConnexionRequetesAPI;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ConnexionRequetesAPITest
{
    public function testGetCaptures()
    {
        $api = new ConnexionRequetesAPI();
        $mockResponse = new MockResponse('', ['http_code' => 200]);
        $httpClient = new MockHttpClient($mockResponse, 'https://sae34.k8s.iut-larochelle.fr/api/captures');

        $requete = $api->getCaptures("D004");

        assertEquals("GET",$mockResponse->getRequestMethod());
        asserEquals("https://sae34.k8s.iut-larochelle.fr/api/captures",$mockResponse->getRequestUrl());
        assertEquals("dbname: sae34bdm1eq1, username: m1eq1, userpass:sodqif-vefXym-0cikho",$mockResponse->getRequestOptions()['headers']);
    }
}