<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\BrowserKit\AbstractBrowser;


class indexControllerTest extends WebTestCase
{
    /*Test de l'affichage de la page*/
    public function testLoginPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
/*Test de la connection du referent*/

    public function testSuccessLoginReferent()
    {
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $ref1 = new User();
        $ref1->setUsername('Test_S_R');
        $ref1->setPassword('$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS');
        $ref1->setRoles(['ROLE_REFERENT']);
        $entityManager->persist($ref1);
        $entityManager->flush();

        $crawler = $client->request('GET','/');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'Test_S_R',
            'login_form[password]' => '123',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/referent');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();
    }

 //Test de la connection du technicien
    public function testSuccessLoginTechnicien()
    {
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $tech1 = new User();
        $tech1->setUsername('Test_S_T');
        $tech1->setPassword('$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS');
        $tech1->setRoles(['ROLE_TECHNICIEN']);
        $entityManager->persist($tech1);
        $entityManager->flush();

        $crawler = $client->request('GET','/');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'Test_S_T',
            'login_form[password]' => '123',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/technicien');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();
    }
    public function testBadLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'JeNeSuisPasUnUser',
            'login_form[password]' => '1234',
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);

    }
    public function testBadPwd()
    {
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $ref1 = new User();
        $ref1->setUsername('Test_S_R');
        $ref1->setPassword('123');
        $ref1->setRoles(['ROLE_REFERENT']);
        $entityManager->persist($ref1);
        $entityManager->flush();

        $crawler = $client->request('GET','/');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'Test_S_R',
            'login_form[password]' => '456',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User")->execute();
        $entityManager->commit();
    }
}