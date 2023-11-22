<?php

namespace App\Tests;
use App\Entity\Room;
use App\Entity\SA;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class referent_Controller_Test extends WebTestCase
{
    public function test_suppression_d_un_sa(){
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $Rtest = new Room();
        $Rtest->setName("WXYZ");
        $Rtest->setNbComputer("20");
        $Rtest->setFacing("S");
        $entityManager->persist($Rtest);

        $SAtest = new SA();
        $SAtest->setName("SAtest");
        $SAtest->setState("ACTIF");
        $SAtest->setCurrentRoom($Rtest);
        $entityManager->persist($SAtest);
        $entityManager->flush();

        $this->assertNotEquals(null, $SAtest->getCurrentRoom());
        $this->assertNotEquals(null, $SAtest->getId());

        $client->request('GET', '/referent/delete_SA/'.$SAtest->getId());

        $this->assertEquals(null, $SAtest->getId());
        $this->assertNotEquals(null, $Rtest->getId());
    }
}