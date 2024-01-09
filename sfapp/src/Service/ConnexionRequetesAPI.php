<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConnexionRequetesAPI
{
    public $nomSa = array(
        "D205" => ["1","sae34bdk1eq1"],
        "D206" => ["2","sae34bdk1eq2"],
        "D207" => ["3","sae34bdk1eq3"],
        "D204" => ["4","sae34bdk2eq1"],
        "D203" => ["5","sae34bdk2eq2"],
        "D303" => ["6","sae34bdk2eq3"],
        "D304" => ["7","sae34bdl1eq1"],
        "C101" => ["8","sae34bdl1eq2"],
        "D109" => ["9","sae34bdl1eq3"],
        "Secrétariat" => ["10","sae34bdl2eq1"],
        "D001" => ["11","sae34bdl2eq2"],
        "D002" => ["12","sae34bdl2eq3"],
        "D004" => ["13","sae34bdm1eq1"],
        "C004" => ["14","sae34bdm1eq2"],
        "C007" => ["15","sae34bdm1eq3"],
        "D201" => ["16","sae34bdm2eq1"],
        "D307" => ["17","sae34bdm2eq2"],
        "C005" => ["18","sae34bdm2eq3"]
    );
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
    public function getCaptures(string $salle)
    {

        $response = $this->client->request(                         // Creates and sends the request to the API
            'GET',                                                  // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures',[    // URL of the API and the route we want to send a request to
            'headers' => [                                          // Adding the required headers to connect to our database in the API
                'dbname' => 'sae34bdm1eq1',                         // Informing the name of our database
                'username' => 'm1eq1',                              // Informing the username to connect to the database
                'userpass' => 'sodqif-vefXym-0cikho',               // Informing the password to connect to the database

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
    public function getIntervalCaptures($date1,$date2,string $salle)
    {

        $response = $this->client->request(                                 // Creates and sends the request to the API
            'GET',                                                          // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures/interval',[   // URL of the API and the route we want to send a request to
            'headers' => [                                                  // Adding the required headers to connect to our database in the API
                'dbname' => $this->nomSa[$salle][1],                        // Informing the name of the database associated with the room
                'username' => 'm1eq1',                                      // Informing the username to connect to the database
                'userpass' => 'sodqif-vefXym-0cikho',                       // Informing the password to connect to the database
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
    public function getlastCaptures(int $nbLines, string $salle)
    {

        $response = $this->client->request(                             // Creates and sends the request to the API
            'GET',                                                      // Sets the http request methods of the request (GET)
            'https://sae34.k8s.iut-larochelle.fr/api/captures/last',[   // URL of the API and the route we want to send a request to
            'headers' => [                                              // Adding the required headers to connect to our database in the API
                'dbname' => $this->nomSa[$salle][1],                    // Informing the name of the database
                'username' => 'm1eq1',                                  // Informing the username to connect to the database
                'userpass' => 'sodqif-vefXym-0cikho',                   // Informing the password to connect to the database


            ],
            'query' => [                // Filling in the parameters of the request
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
