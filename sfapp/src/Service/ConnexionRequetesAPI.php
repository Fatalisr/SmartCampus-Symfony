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
    

    /*
     * @brief Sends a get request to the /api/captures/interval route of the API of the IUT to get available captures between a specified time interval
     * @param date1 : starting date of the interval
     * @param date2 : ending date of the interval
     * @return an array containing all the data contained in the interval available on the API
     */
    public function getIntervalCaptures($date1,$date2)
    {

        $response = $this->client->request(                                 // Creates and sends the request to the API
            'GET',                                                          // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures/interval',[   // URL of the API and the route we want to send a request to
            'headers' => [                                                  // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                                 // Informing the name of our database
                'username' => 'm1eq1',                                      // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',                       // Informing the password to connect to our database


                ],
            'query' => [                // Filling in the parameters of the request
                'date1' => $date1,      // Interval starting date
                'date2' => $date2,      // Interval ending date
            ],
        ]);

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that the request was successful)
        {
            return $response->getContent();
        }

        return $response->getStatusCode();
        //return $response->getInfo();
    }

    /*
     * @brief Sends a get request to the /api/captures/last route of the API of the IUT to fetch the last data available
     * @param nbLines : numbers of data fields to be fetched
     * @return an array containing the last data available on the API
     */
    public function getlastCaptures(int $nbLines)
    {

        $response = $this->client->request(                             // Creates and sends the request to the API
            'GET',                                                      // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures/last',[   // URL of the API and the route we want to send a request to
            'headers' => [                                              // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                             // Informing the name of our database
                'username' => 'm1eq1',                                  // Informing the username to connect to our database
                'userpass' => 'sodqif-vefXym-0cikho',                   // Informing the password to connect to our database


                ],
            'query' => [                // Filling in the parameters of the request
                'nomsa' => "13",        // Name of the SA
                'limit' => $nbLines,    // Number of values we want to fetch
            ],
        ]);

        if($response->getStatusCode() == 200)   // Checks if the request was successful (200 indicates that the request was successful)
        {
            return $response->getContent();
        }
        return $response->getStatusCode();
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