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

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that the request was successful)
        {
            return $response->getContent();
            //return json_decode($response->getContent());
        }

        return $response->getStatusCode();
        //return $response->getInfo();
    }

    public function postCaptures()
    {
        
        $response = $this->client->request(                         // Creates and sends the request to the API
            'POST',                                                 // Sets the http request methods of the request (POST)
            'https://sae34.k8s.iut-larochelle.fr/api/captures',[    // URL of the API and the route we want to send a request to
            'headers' => [                                          // Adding the required headers to connect to our database in the API
                'Content-Type: application/json',
                'Accept: application/json',
                'dbname' => 'sae34bdm1eq1',                         // Informing the name of our database
                'username' => 'm1eq1',                              // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',               // Informing the password to connect to our database
            ],
                // 'body' => $requestJson,
                'json' => ['id' => '51', 'nom' => 'co2', 'valeur' => '402', 'dateCapture' => '2022-11-28 08:00:00', 'localisation' => 'D004', 'description' => '', 'tag' => '13'],
        ]);

        if (201 != $response->getStatusCode()) {
            //throw new Exception('Response status code is different than expected.');
            //return $response->getInfo('debug');
            return $response->getStatusCode();
        }

        if($response->getStatusCode() == 201)   // Checks if the request was successful (201 indicates that the request was successful)
        {
            return json_decode($response->getContent(), true, );
        }

        //return $response->getStatusCode();
        //return $response->getInfo();
    }

    public function getIntervalCaptures($date1,$date2)
    {

        $response = $this->client->request(                                 // Creates and sends the request to the API
            'GET',                                                          // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures/interval',[   // URL of the API and the route we want to send a request to
            'headers' => [                                                  // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                                 // Informing the name of our database
                'username' => 'm1eq1',                                      // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',                       // Informing the password to connect to our database
                'query' => [
                    'date1' => $date1,
                    'date2' => $date2,
                ]
            ],
        ]);

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that teh request was successful)
        {
            return $response->getContent();
        }

        //return $response->getContent();
        return $response->getStatusCode();
        //return $response->getInfo();
    }

    public function getlastCaptures()
    {

        $response = $this->client->request(                             // Creates and sends the request to the API
            'GET',                                                      // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures/last',[   // URL of the API and the route we want to send a request to
            'headers' => [                                              // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                             // Informing the name of our database
                'username' => 'm1eq1',                                  // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',                   // Informing the password to connect to our database

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