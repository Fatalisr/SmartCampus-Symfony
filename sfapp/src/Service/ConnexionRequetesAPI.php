<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConnexionRequetesAPI
{
    /*
     * @brief Constructor of the ConnexionRequetesAPI service
     */
    public function __construct(private HttpClientInterface $client,)
    {

    }

    /*
     * @brief Sends a get request to the /api/captures route of the API of the IUT to get available captures
     * @return an array containing all the data available on the API
     */
    public function getCaptures()
    {

        $response = $this->client->request(                         // Creates and sends the request to the API
            'GET',                                                  // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures',[    // URL of the API and the route we want to send a request to
            'headers' => [                                          // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                         // Informing the name of our database
                'username' => 'm1eq1',                              // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',               // Informing the password to connect to our database

            ],
        ]);

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that teh request was successful)
        {
            return $response->getContent();
        }

        return $response->getContent();
        //return $response->getStatusCode();
        //return $response->getInfo();
    }

    public function postCaptures()
    {

        $response = $this->client->request(                         // Creates and sends the request to the API
            'POST',                                                 // Sets the http request methods of the request (POST)
            'https://sae34.k8s.iut-larochelle.fr/api/captures',[    // URL of the API and the route we want to send a request to
            'headers' => [                                          // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                         // Informing the name of our database
                'username' => 'm1eq1',                              // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',               // Informing the password to connect to our database

            ],
        ]);

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that teh request was successful)
        {
            return $response->getContent();
        }

        //return $response->getStatusCode();
        //return $response->getInfo();
    }

    public function getIntervalCaptures()
    {

        $response = $this->client->request(                         // Creates and sends the request to the API
            'GET',                                                  // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures',[    // URL of the API and the route we want to send a request to
            'headers' => [                                          // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                         // Informing the name of our database
                'username' => 'm1eq1',                              // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',               // Informing the password to connect to our database

            ],
        ]);

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that teh request was successful)
        {
            return $response->getContent();
        }

        return $response->getContent();
        //return $response->getStatusCode();
        //return $response->getInfo();
    }

    public function getlastCaptures()
    {

        $response = $this->client->request(                         // Creates and sends the request to the API
            'GET',                                                  // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures',[    // URL of the API and the route we want to send a request to
            'headers' => [                                          // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                         // Informing the name of our database
                'username' => 'm1eq1',                              // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',               // Informing the password to connect to our database

            ],
        ]);

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that teh request was successful)
        {
            return $response->getContent();
        }

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