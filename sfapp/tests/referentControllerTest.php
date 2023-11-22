<?php
namespace App\Tests;

use App\Entity\Room;
use App\Entity\SA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class referentControllerTest extends WebTestCase
{
    public function testSAPage()/*Test de l'affichage de la page*/
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        //Test Room
        $room = new Room();
        $room->setName('Test_Room');
        $room->setFacing('N');
        $room->setNbComputer(3);
        $entityManager->persist($room);
        $entityManager->flush();
        // Test SA
        $sa = new SA();
        $sa->setName("Test_VIEW_SA");
        $sa->setState("ACTIF");
        $sa->setCurrentRoom($room);
        $entityManager->persist($sa);
        $entityManager->flush();

        $client->request('GET', '/referent/sa/'.$sa->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        // Delete the entry in the database to avoid conflict with the tests
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_VIEW_SA')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room')->execute();
        $entityManager->commit();

    }
    public function testviewNouveauSAPage()
    {
        $client = static::createClient([], ['base_uri' => 'http://localhost:8000']);
        $client->request('GET', '/referent/nouveausa');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
    public function testcreateNouveauSAInactif()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/referent/nouveausa');
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room')->execute();
        $entityManager->commit();

        $form = $crawler->selectButton('Creer le SA')->form([
            'nouveau_sa_form[name]' => 'Test_SA',
            'nouveau_sa_form[currentRoom]' => ''
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/referent');

        // Assert that the data is in the database
        $saRepository = $entityManager->getRepository(SA::class);
        $sa = $saRepository->findOneBy(['name' => 'Test_SA']);
        $this->assertInstanceOf(SA::class, $sa);
        $this->assertSame('INACTIF', $sa->getState()); // Assuming this is the expected state

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room')->execute();
        $entityManager->commit();
    }

    public function testcreateNouveauSAAInstaller()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA_INSTA')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room')->execute();
        $entityManager->commit();
        // Create a new room for testing
        $room = new Room();
        $room->setName('Test_Room');
        $room->setFacing('N');
        $room->setNbComputer(3);
        $entityManager->persist($room);
        $entityManager->flush();

        $crawler = $client->request('GET', '/referent/nouveausa');

        $form = $crawler->selectButton('Creer le SA')->form([
            'nouveau_sa_form[name]' => 'Test_SA_INSTA',
            'nouveau_sa_form[currentRoom]' => $room->getId()
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/referent');

        // Assert that the data is in the database
        $saRepository = $entityManager->getRepository(SA::class);
        $sa = $saRepository->findOneBy(['name' => 'Test_SA_INSTA']);
        $this->assertInstanceOf(SA::class, $sa);
        $this->assertSame('A_INSTALLER', $sa->getState()); // Assuming this is the expected state

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA_INSTA')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room')->execute();
        $entityManager->commit();
    }
    public function testDelSA(){
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA_DEL')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room')->execute();
        $entityManager->commit();
        $room = new Room();
        $room->setName("Test_Room");
        $room->setNbComputer(20);
        $room->setFacing("S");
        $entityManager->persist($room);

        $sa = new SA();
        $sa->setName("Test_SA_DEL");
        $sa->setState("ACTIF");
        $sa->setCurrentRoom($room);
        $entityManager->persist($sa);
        $entityManager->flush();

        $this->assertNotEquals(null, $sa->getCurrentRoom());
        $this->assertNotEquals(null, $sa->getId());

        $client->request('GET', '/referent/delete_SA/'.$sa->getId());

        $this->assertEquals(null, $sa->getCurrentRoom());
        $this->assertNotEquals(null, $room->getId());

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA_DEL')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','Test_Room')->execute();
        $entityManager->commit();
    }
}