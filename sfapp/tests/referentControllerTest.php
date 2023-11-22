<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

use App\Entity\Room;
use App\Entity\SA;
use Doctrine\Persistence\ManagerRegistry;

class referentControllerTest extends WebTestCase
{
    public function testSAPage()
    {
        $client = static::createClient();
        $client->request('GET', '/referent/sa/2');

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

    public function testChangeRoomPage() // Test de l'affichage de la page du formulaire de changement de salle
    {

        $client = static::createClient();
        $client->request('GET', '/referent/changersalle/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
    public function testChangerSalle()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Suppression des instances de test pour eviter les conflit dans la bdd
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA S WHERE S.name=:nom")->setParameter('nom','Test_SA')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room1')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room2')->execute();
        $entityManager->commit();

        // creation des instances de test
        $room1 = new Room();
        $room1->setName('Test_Room1');
        $room1->setFacing('N');
        $room1->setNbComputer(3);
        $entityManager->persist($room1);

        // Create a new sa for testing
        $sa = new SA();
        $sa->setName('Test_SA');
        $sa->setState('ACTIF');
        $sa->setCurrentRoom($room1);
        $entityManager->persist($sa);

        // Create a new room for testing
        $room2 = new Room();
        $room2->setName('Test_Room2');
        $room2->setFacing('N');
        $room2->setNbComputer(3);
        $entityManager->persist($room2);

        $entityManager->flush();


        $crawler = $client->request('GET', '/referent/changersalle/'.$sa->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler->selectButton('Confirmer les modifications')->form([
            'changer_salle_form[newRoom]' => $room2->getId(),
        ]);
        $client->submit($form);
        $saModif = $entityManager->find(SA::class,$sa->getId());

        $this->assertEquals($saModif->getCurrentRoom()->getId(), $room2->getId()); // On verifie que la nouvelle salle a bien été affilié au SA
        $this->assertEquals($saModif->getOldRoom()->getId(), $room1->getId()); // On verifie que l'ancienne salle a bien été enregistrer
        $this->assertEquals($saModif->getState(), 'A_INSTALLER');// On verifie que l'état a bien été modifier

        // Suppression des instances de test pour eviter les conflit dans la bdd
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA S WHERE S.name=:nom")->setParameter('nom','Test_SA')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room1')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room2')->execute();
        $entityManager->commit();
    }
}