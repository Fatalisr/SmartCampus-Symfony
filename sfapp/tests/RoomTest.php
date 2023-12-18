<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Room;


class RoomTest extends TestCase
{
    public function testRoomConstruct()
    {
        $room1 = new Room();
        $this->assertEquals(NULL,$room1->getId());
        $this->assertEquals(NULL,$room1->getName());
        $this->assertEquals(NULL,$room1->getNbComputer());
        $this->assertEquals(NULL,$room1->getFacing());
    }

    public function testName()
    {
        // Vérification du cas valide de la modification
        $room1 = new Room();
        $room1->setName("D204");
        $this->assertEquals("D204",$room1->getName());

        $room1 = new Room();
        $room1->setName(2);
        $this->assertEquals(2,$room1->getName());
    }

    public function testNbComputer()
    {
        // Vérification du cas valide de la modification
        $room1 = new Room();
        $room1->setNbComputer(20);
        $this->assertEquals(20,$room1->getNbComputer());
    }

    public function testFacing()
    {
        // Vérification du cas valide de la modification
        $room1 = new Room();
        $room1->setFacing("S");
        $this->assertEquals("S",$room1->getFacing());

        // Vérification du cas invalide de la modification où la valeur rentrée n'est pas précisée dans le check


        // Vérification du cas invalide de la modification où la valeur rentrée n'est pas un string

    }
}