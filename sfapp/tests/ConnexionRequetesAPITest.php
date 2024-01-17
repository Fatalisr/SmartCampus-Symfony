<?php

namespace App\Tests;

use App\Service\ConnexionRequetesAPI;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ConnexionRequetesAPITest extends TestCase
{
    public function testGetCaptures()
    {

        $mockResponse = new MockResponse('', ['http_code' => 200]);
        $httpClient = new MockHttpClient($mockResponse, 'https://sae34.k8s.iut-larochelle.fr/api/captures');
        $api = new ConnexionRequetesAPI($httpClient);

        $response = $api->getCaptures("D004", "co2");

        $this->assertEquals("GET",$mockResponse->getRequestMethod());
        $this->assertEquals("https://sae34.k8s.iut-larochelle.fr/api/captures?nom=co2",$mockResponse->getRequestUrl());
        $this->assertEquals([0=> 'dbname: sae34bdm1eq1', 1=> 'username: m1eq1', 2=> 'userpass: sodqif-vefXym-0cikho', 3=> 'Accept: */*'],$mockResponse->getRequestOptions()['headers']);
    }

    public function testGetIntervalCaptures()
    {

        $mockResponse = new MockResponse('', ['http_code' => 200]);
        $httpClient = new MockHttpClient($mockResponse, 'https://sae34.k8s.iut-larochelle.fr/api/captures/interval');
        $api = new ConnexionRequetesAPI($httpClient);

        $response = $api->getIntervalCaptures('2024-01-09','2024-01-10',"D004", "co2");

        $this->assertEquals("GET",$mockResponse->getRequestMethod());
        $this->assertEquals("https://sae34.k8s.iut-larochelle.fr/api/captures/interval?nom=co2&date1=2024-01-09&date2=2024-01-10",$mockResponse->getRequestUrl());
        $this->assertEquals([0=> 'dbname: sae34bdm1eq1', 1=> 'username: m1eq1', 2=> 'userpass: sodqif-vefXym-0cikho', 3=> 'Accept: */*'],$mockResponse->getRequestOptions()['headers']);
        $this->assertEquals(['date1'=>'2024-01-09', 'date2'=>'2024-01-10', 'nom'=>'co2'],$mockResponse->getRequestOptions()['query']);
    }

    public function testGetLastCaptures()
    {

        $mockResponse = new MockResponse('', ['http_code' => 200]);
        $httpClient = new MockHttpClient($mockResponse, 'https://sae34.k8s.iut-larochelle.fr/api/captures/last');
        $api = new ConnexionRequetesAPI($httpClient);

        $response = $api->getlastCaptures(5,"D004", "co2");

        $this->assertEquals("GET",$mockResponse->getRequestMethod());
        $this->assertEquals("https://sae34.k8s.iut-larochelle.fr/api/captures/last?nom=co2&limit=5",$mockResponse->getRequestUrl());
        $this->assertEquals([0=> 'dbname: sae34bdm1eq1', 1=> 'username: m1eq1', 2=> 'userpass: sodqif-vefXym-0cikho', 3=> 'Accept: */*'],$mockResponse->getRequestOptions()['headers']);
        $this->assertEquals(['limit'=>5, 'nom'=>'co2'],$mockResponse->getRequestOptions()['query']);
    }
}