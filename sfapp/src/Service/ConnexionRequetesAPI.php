<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConnexionRequetesAPI
{
    public function __construct(private HttpClientInterface $client,)
    {
    }

    public function getCaptures()
    {
        $response = $this->client->request(
            'GET',
            'https://sae34.k8s.iut-larochelle.fr/api/captures',[
            'headers' => [
                'dbname' => 'sae34bdm1eq1',
                'username' => 'm1eq1',
                'userpass' => 'sodqif-vefXym-0cikho',

            ],
        ]);

        return $response->getContent();
        //return $response->getStatusCode();
        //return $response->getInfo();
    }

    /**
     * @return HttpClientInterface
     */
    public function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    /**
     * @param HttpClientInterface $client
     */
    public function setClient(HttpClientInterface $client): void
    {
        $this->client = $client;
    }
}