<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Member;
use Symfony\Component\BrowserKit\AbstractBrowser;

class indexControllerTest extends WebTestCase
{
    public function testLoginPage()/*Test de l'affichage de la page*/
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testSuccessLoginReferent() /*Test de la connection du referent*/
    {

        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User U WHERE U.username=:username")->setParameter('username','Test_S_R')->execute();
        $entityManager->commit();

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
        $entityManager->createQuery("DELETE FROM App\Entity\User U WHERE U.username=:username")->setParameter('username','Test_S_R')->execute();
        $entityManager->commit();
    }

    public function testSuccessLoginTechnicien() /*Test de la connection du technicien*/
    {
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\User U WHERE U.username=:username")->setParameter('username','Test_S_T')->execute();
        $entityManager->commit();


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
        $entityManager->createQuery("DELETE FROM App\Entity\User U WHERE U.username=:username")->setParameter('username','Test_S_T')->execute();
        $entityManager->commit();
    }
/*
    public function testBadLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'JeNeSuisPasUnUser',
            'login_form[password]' => '1234',
        ]);

        $client->submit($form);

        $this->assertSelectorTextContains('p#bad_login','Nom d\'utilisateur ou mot de passe incorrect');
    }

    public function testBadPwd()
    {
        $client = static::createClient();
        // Delete the entry in the database to avoid conflict with the tests
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $ref1 = new Member();
        $ref1->setLogin('Test_S_R');
        $ref1->setPassword('123');
        $ref1->setRole('REFERENT');
        $entityManager->persist($ref1);
        $entityManager->flush();

        $crawler = $client->request('GET','/');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'Test_S_R',
            'login_form[password]' => '456',
        ]);
        $client->submit($form);

        $this->assertSelectorTextContains('p#bad_pwd','Nom d\'utilisateur ou mot de passe incorrect');
        $entityManager->beginTransaction(); // Begin a transaction
        $entityManager->createQuery("DELETE FROM App\Entity\Member M WHERE M.login=:login")->setParameter('login','Test_S_R')->execute();
        $entityManager->commit();
    }*/
}