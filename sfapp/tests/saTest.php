<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\SA;
use App\Entity\Room;

class SATest extends TestCase
{
    public function testSAConstruct()
    {
        $sa = new SA();
        $this->assertEquals(NULL,$sa->getId());
        $this->assertEquals(NULL,$sa->getName());
        $this->assertEquals(NULL,$sa->getState());
        $this->assertEquals(NULL,$sa->getOldRoom());
        $this->assertEquals(NULL,$sa->getCurrentRoom());
    }

    public function testName()
    {
        // Vérification du cas valide de la modification
        $sa = new SA();
        $sa->setName("SA01");

        $this->assertEquals("SA01",$sa->getName());

        $sa = new SA();
        $sa->setName(2);
        $this->assertEquals(2,$sa->getName());
    }

    public function testCurrentRoom()
    {
        // Vérification du cas valide de la modification
        $sa = new SA();
        $room = new Room();
        $sa->setCurrentRoom($room);
        $this->assertEquals($room,$sa->getCurrentRoom());
    }

    public function testOldRoom()
    {
        // Vérification du cas valide de la modification
        $sa = new SA();
        $room = new Room();
        $sa->setOldRoom($room);
        $this->assertEquals($room,$sa->getOldRoom());
    }

    public function testState()
    {
        // Vérification du cas valide de la modification
        $sa = new SA();
        $sa->setState("ACTIF");
        $this->assertEquals("ACTIF",$sa->getState());

        // Vérification du cas invalide de la modification où la valeur rentrée n'est pas précisée dans le check


        // Vérification du cas invalide de la modification où la valeur rentrée n'est pas un string

    }

}