<?php

namespace App\Tests;
use App\Entity\Room;
use App\Entity\SA;
use App\Entity\Maintenance;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Persistence\ManagerRegistry;

class technicienControllerTest extends WebTestCase
{

    function testTechnicienPage()
    {
        $client = static::createClient();
        $client->request('GET', '/technicien');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    function testValidMaintenance()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Maintenance M WHERE M.message=:msg")->setParameter('msg','message')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','SATest')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','E648')->execute();
        $entityManager->commit();

        $room = new Room();
        $room->setName('E648');
        $room->setFacing('N');
        $room->setNbComputer(15);
        $entityManager->persist($room);

        $sa = new SA();
        $sa->setName('SATest');
        $sa->setState('MAINTENANCE');
        $sa->setCurrentRoom($room);
        $entityManager->persist($sa);

        $maintenance = new Maintenance();
        $maintenance->setMessage('message');
        $maintenance->setSa($sa);
        $maintenance->setStartingDate(new \DateTime());
        $entityManager->persist($maintenance);

        $entityManager->flush();

        $client->request('GET', '/technicien/maintenance/'.$sa->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('VALIDER LA MAINTENANCE',[
            'maintenance_form[valid]' => 'true',
        ]);
        $saRepository = $entityManager->getRepository(SA::class);
        $maintenanceRepository = $entityManager->getRepository(Maintenance::class);
        $maintenance2 = $maintenanceRepository->findOneBy(['message' => 'message']);
        $sa = $saRepository->findOneBy(['name' => 'SATest']);
        $this->assertSame('ACTIF',$sa->getState());
        $this->assertNotEquals(null,$maintenance2->getEndingDate());

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Maintenance M WHERE 1=1")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE 1=1")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE 1=1")->execute();
        $entityManager->commit();
    }

    function testinvalidMaintenance()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Maintenance M WHERE M.message=:msg")->setParameter('msg','message')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','SATest')->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE R.name=:nom")->setParameter('nom','E648')->execute();
        $entityManager->commit();

        $room = new Room();
        $room->setName('E648');
        $room->setFacing('N');
        $room->setNbComputer(15);
        $entityManager->persist($room);

        $sa = new SA();
        $sa->setName('SATest');
        $sa->setState('MAINTENANCE');
        $sa->setCurrentRoom($room);
        $entityManager->persist($sa);

        $maintenance = new Maintenance();
        $maintenance->setMessage('message');
        $maintenance->setSa($sa);
        $maintenance->setStartingDate(new \DateTime());
        $entityManager->persist($maintenance);

        $entityManager->flush();

        $client->request('GET', '/technicien/maintenance/'.$sa->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('MAINTENANCE IMPOSSIBLE',[
            'maintenance_form[valid]' => 'false',
        ]);
        $saRepository = $entityManager->getRepository(SA::class);
        $maintenanceRepository = $entityManager->getRepository(Maintenance::class);
        $maintenance2 = $maintenanceRepository->findOneBy(['message' => 'message']);
        $sa = $saRepository->findOneBy(['name' => 'SATest']);
        $this->assertSame('INACTIF',$sa->getState());
        $this->assertNotEquals(null,$maintenance2->getEndingDate());

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Maintenance M WHERE 1=1")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE 1=1")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room R WHERE 1=1")->execute();
        $entityManager->commit();
    }
}
