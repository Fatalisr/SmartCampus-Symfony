<?php
namespace App\Tests;

use App\Entity\Room;
use App\Entity\SA;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class referentControllerTest extends WebTestCase
{
    public function testviewNouveauSAPage()
    {
        $client = static::createClient([], ['base_uri' => 'http://localhost:8000']);
        $client->request('GET', '/referent/nouveausa');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }


    public function testcreateNouveauSAInactif()
    {
        $client = static::createClient([], ['base_uri' => 'http://localhost:8000']);
        $crawler = $client->request('GET', '/referent/nouveausa');


        // Assert that the data is in the database
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        //To clean the database before the test
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery('DELETE FROM App\Entity\SA')->execute();
        $entityManager->createQuery('DELETE FROM App\Entity\Room')->execute();
        $entityManager->commit();



        $form = $crawler->selectButton('Creer le SA')->form([
            'nouveau_sa_form[name]' => 'Test SA',
            'nouveau_sa_form[currentRoom]' => ''
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/referent');





        $saRepository = $entityManager->getRepository(SA::class);


        $sa = $saRepository->findOneBy(['name' => 'Test SA']);

        $this->assertInstanceOf(SA::class, $sa);
        $this->assertSame('INACTIF', $sa->getState()); // Assuming this is the expected state


        //To clean the database after the test
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery('DELETE FROM App\Entity\SA')->execute();
        $entityManager->createQuery('DELETE FROM App\Entity\Room')->execute();
        $entityManager->commit();
    }

    public function testcreateNouveauSAAInstaller()
    {
        $client = static::createClient([], ['base_uri' => 'http://localhost:8000']);
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        //To clean the database before the test
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery('DELETE FROM App\Entity\SA')->execute();
        $entityManager->createQuery('DELETE FROM App\Entity\Room')->execute();
        $entityManager->commit();
        // Create a new room for testing
        $room = new Room();
        $room->setName('TestRoom');
        $room->setFacing('N');
        $room->setNbComputer(3);
        $entityManager->persist($room);
        $entityManager->flush();

        $crawler = $client->request('GET', '/referent/nouveausa');

        $form = $crawler->selectButton('Creer le SA')->form([
            'nouveau_sa_form[name]' => 'TestINSTALLER',
            'nouveau_sa_form[currentRoom]' => $room->getId()
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/referent');

        // Assert that the data is in the database
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $saRepository = $entityManager->getRepository(SA::class);


        $sa = $saRepository->findOneBy(['name' => 'TestINSTALLER']);

        $this->assertInstanceOf(SA::class, $sa);
        $this->assertSame('A_INSTALLER', $sa->getState()); // Assuming this is the expected state
    }
}