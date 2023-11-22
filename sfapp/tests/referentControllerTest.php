<?php

namespace App\Tests;

use App\Entity\SA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class referentControllerTest extends WebTestCase
{
    public function testSAPage()/*Test de l'affichage de la page*/
    {
        $client = static::createClient();
        /*
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Suppression des instances de test pour eviter les conflit dans la bdd
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA S WHERE S.name=:nom")->setParameter('nom','Test_SA')->execute();
        $entityManager->commit();

        $sa = new SA();
        $sa->setName('Test_SA');
        $sa->setState('ACTIF');
        $entityManager->persist($sa);
        $entityManager->flush();
        */

        $client->request('GET', '/referent/sa/1'/*.$sa->getId()*/);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        /*
        // Suppression des instances de test pour eviter les conflit dans la bdd
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA S WHERE S.name=:nom")->setParameter('nom','Test_SA')->execute();
        $entityManager->commit();
        */
    }
}