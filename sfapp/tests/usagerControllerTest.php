<?php

namespace App\Tests;

use App\Entity\Room;
use App\Entity\SA;
use App\Form\choisirSalleUsagerForm;
use App\Repository\RoomRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class usagerControllerTest extends WebTestCase
{
    public function testChoixSalleUsager(){

        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
        $entityManager->commit();

        $room = new Room();
        $room->setName('RoomTest');
        $room->setFacing('N');
        $room->setNbComputer(15);
        $entityManager->persist($room);

        $sa = new SA();
        $sa->setName('SATest');
        $sa->setState('ACTIF');
        $sa->setCurrentRoom($room);
        $entityManager->persist($sa);

        $entityManager->flush();

        $crawler = $client->request('GET','/usager');
        $form = $crawler->filter('#room_choise_form')->form();

        $formData = [
            'choisir_salle_usager_form[room]' => $room->getId(),
        ];

        $client->submit($form,$formData);

        $this->assertTrue($client->getResponse()->isRedirect('/usager/'.$room->getId()));

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
        $entityManager->commit();
    }

    public function testSallesAssigneesSAActif()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
        $entityManager->commit();

        // Salle affecté a un SA actif
        $room1 = new Room();
        $room1->setName("Room1");
        $room1->setFacing('N');
        $room1->setNbComputer(15);

        // Salle affecter a un SA en maintenance
        $room2 = new Room();
        $room2->setName("Room2");
        $room2->setFacing('S');
        $room2->setNbComputer(15);

        // Salle sans SA
        $room3 = new Room();
        $room3->setName("Room3");
        $room3->setFacing('S');
        $room3->setNbComputer(15);

        // SA actif
        $sa1 = new SA();
        $sa1->setName('saTest');
        $sa1->setState('ACTIF');
        $sa1->setCurrentRoom($room1);

        // SA en maintenance
        $sa2 = new SA();
        $sa2->setName('saTest');
        $sa2->setState('MAINTENANCE');
        $sa2->setCurrentRoom($room2);

        // Envoie des données
        $entityManager->persist($room1);
        $entityManager->persist($room2);
        $entityManager->persist($room3);
        $entityManager->persist($sa1);
        $entityManager->persist($sa2);

        $entityManager->flush();

        // Creation de la requete qui remplis le select du formulaire
        $er = $entityManager->getRepository(Room::class);

        $query = $er->createQueryBuilder('r')
            ->leftJoin('App\Entity\SA', 's', 'WITH', 's.currentRoom = r')
            ->where('s.state = :actif')
            ->setParameter('actif', "ACTIF")
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        $choices = $query->getResult();

        $this->assertCount(1, $choices);// verifie qu'il n'y a qu'une seule salle dans le tableau
        // verifie que la salle presente dans le tableau est bien celle qui possede un SA actif
        $this->assertEquals($room1->getId(), $choices[0]->getId());

        // Clear de la BDD après le test
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
        $entityManager->commit();
    }
}
