<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class referentControllerTest extends WebTestCase
{
    public function testSAPage()/*Test de l'affichage de la page*/
    {
        $client = static::createClient();
        $client->request('GET', '/referent/sa/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}