<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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
        $crawler = $client->request('GET','/');

        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'ref1',
            'login_form[password]' => '123',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/referent');
    }

    public function testSuccessLoginTechnicien() /*Test de la connection du technicien*/
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'tech1',
            'login_form[password]' => '456',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/technicien');
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

        $this->assertSelectorTextContains('p#bad_login','Login incorrect');
    }

    public function testBadPwd()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');

        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[username]' => 'ref1',
            'login_form[password]' => '9874',
        ]);

        $client->submit($form);

        $this->assertSelectorTextContains('p#bad_pwd','Mot de passe incorrect');
    }
}