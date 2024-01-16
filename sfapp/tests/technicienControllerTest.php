<?php

namespace App\Tests;
use App\Entity\Intervention;
use App\Entity\Room;
use App\Entity\SA;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Persistence\ManagerRegistry;

class technicienControllerTest extends WebTestCase
{
    function testTechnicienPage()
    {
        // Se connecte en temps que Technicien
        //=============================================================
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();

        $ref1 = new User();
        $ref1->setUsername('Test_S_T');
        $ref1->setPassword('$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS');
        $ref1->setRoles(['ROLE_TECHNICIEN']);
        $entityManager->persist($ref1);
        $entityManager->flush();

        $crawler = $client->request('GET','/');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'Test_S_T',
            'login_form[password]' => '123',
        ]);
        $client->submit($form);
        //=============================================================
        $client->request('GET', '/technicien');
        $this->assertResponseStatusCodeSame(200);

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\INTERVENTION ")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();
    }
/*
    function testValidMaintenance()
    {
        // Se connecte en temps que Technicien
        //=============================================================
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();

        $ref1 = new User();
        $ref1->setUsername('Test_S_T');
        $ref1->setPassword('$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS');
        $ref1->setRoles(['ROLE_TECHNICIEN']);
        $entityManager->persist($ref1);
        $entityManager->flush();

        $crawler = $client->request('GET','/');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'Test_S_T',
            'login_form[password]' => '123',
        ]);
        $client->submit($form);
        //=============================================================
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Intervention")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
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

        $maintenance = new Intervention();
        $maintenance->setMessage('message');
        $maintenance->setSa($sa);
        $maintenance->setType_I("MAINTENANCE");
        $date = new \DateTime();
        $maintenance->setStartingDate($date);
        $entityManager->persist($maintenance);

        $entityManager->flush();

        $client->request('GET', '/technicien/maintenance/'.$maintenance->getId());
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('VALIDER LA MAINTENANCE',[
            'maintenance_form[report]' => ':(',
            'maintenance_form[valid]' => 'true',
        ]);


        $saRepo = $entityManager->getRepository("App\Entity\Sa");
        $saInterv = $saRepo->findOneById($sa);
        //$this->assertSame('ACTIF',$saInterv->getState());

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Intervention")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();
    }

    function testinvalidMaintenance()
    {
        // Se connecte en temps que Technicien
        //=============================================================
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();

        $ref1 = new User();
        $ref1->setUsername('Test_S_T');
        $ref1->setPassword('$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS');
        $ref1->setRoles(['ROLE_TECHNICIEN']);
        $entityManager->persist($ref1);
        $entityManager->flush();

        $crawler = $client->request('GET','/');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'Test_S_T',
            'login_form[password]' => '123',
        ]);
        $client->submit($form);
        //=============================================================

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Intervention")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
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

        $maintenance = new Intervention();
        $maintenance->setMessage('message');
        $maintenance->setSa($sa);
        $maintenance->setType_I("MAINTENANCE");
        $date = new \DateTime();
        $maintenance->setStartingDate($date);
        $entityManager->persist($maintenance);

        $entityManager->flush();

        $client->request('GET', '/technicien/maintenance/'.$maintenance->getId());
        $this->assertResponseStatusCodeSame(200);

        $client->submitForm('VALIDER LA MAINTENANCE',[
            'maintenance_form[report]' => ':(',
            'maintenance_form[valid]' => 'false',
        ]);


        $saRepo = $entityManager->getRepository("App\Entity\Sa");
        $saInterv = $saRepo->findOneById($sa);
        //$this->assertSame('INACTIF',$saInterv->getState());

        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Intervention")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\SA")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\Room")->execute();
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();
    }*/
}
