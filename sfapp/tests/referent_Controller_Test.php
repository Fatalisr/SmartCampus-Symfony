<?php

namespace App\Tests;
use App\Entity\Room;
use App\Entity\SA;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class referent_Controller_Test extends WebTestCase
{
    public function test_suppression_d_un_sa(){
        $Rtest = new Room();
        $Rtest->setName("WXYZ");
        $Rtest->setNbComputer("20");
        $Rtest->setFacing("S");

        $SAtest = new SA();
        $SAtest->setName("SAtest");
        $SAtest->setState("ACTIF");
        $SAtest->setCurrentRoom($Rtest->getId());

        $client = static::createClient();
        $client->request('GET', '/referent/delete_sa/'.$SAtest->getId());

        $this->assertResponseRedirects('/referent');

        $this->assertEquals($SAtest->getId(), null);
    }
}