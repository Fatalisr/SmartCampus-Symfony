<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testUserConstruct()
    {
        $member1 = new User();
        $this->assertEquals(NULL,$member1->getId());
        $this->assertEquals(NULL,$member1->getUsername());
        $this->assertEquals(["ROLE_USER"],$member1->getRoles());
    }

    public function testSetLogin()
    {
        // Vérification du cas valide de la modification
        $member1 = new User();
        $member1->setUsername("ref1");
        $this->assertEquals("ref1",$member1->getUsername());
    }

    public function testPassword()
    {
        // Vérification du cas valide de la modification
        $member1 = new User();
        $member1->setPassword("123");
        $this->assertEquals("123",$member1->getPassword());

        $member1 = new User();
        $member1->setPassword(123);
        $this->assertEquals(123,$member1->getPassword());
    }
}