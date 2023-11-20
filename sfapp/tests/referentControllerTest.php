<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

use App\Entity\SA;
use Doctrine\Persistence\ManagerRegistry;

class referentControllerTest extends WebTestCase
{
    public function testSAPage()/*Test de l'affichage de la page*/
    {
        $client = static::createClient();
        $client->request('GET', '/referent/sa/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testReferentPage() // Test de l'affichage de la page d'accueil du référent
    {
        $client = static::createClient();
        $client->request('GET', '/referent');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testChangeRoomPage() // Test de l'affichage de la page d'accueil du référent
    {
        $doctrine = new ManagerRegistry();
        $sa = new SA();

        $client = static::createClient();
        $client->request('GET', '/referent/changersalle/'.$sa->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }


}