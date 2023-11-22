<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Member;

class MemberTest extends TestCase
{
    public function testMemberConstruct()
    {
        $member1 = new Member();
        $this->assertEquals(NULL,$member1->getId());
        $this->assertEquals(NULL,$member1->getLogin());
        $this->assertEquals(NULL,$member1->getPassword());
        $this->assertEquals(NULL,$member1->getRole());
    }

    public function testSetLogin()
    {
        // Vérification du cas valide de la modification
        $member1 = new Member();
        $member1->setLogin("ref1");
        $this->assertEquals("ref1",$member1->getLogin());

        $member1 = new Member();
        $member1->setLogin(2);
        $this->assertEquals(2,$member1->getLogin());
    }

    public function testPassword()
    {
        // Vérification du cas valide de la modification
        $member1 = new Member();
        $member1->setPassword("123");
        $this->assertEquals("123",$member1->getPassword());

        $member1 = new Member();
        $member1->setPassword(123);
        $this->assertEquals(123,$member1->getPassword());
    }

    public function testFacing()
    {
        // Vérification du cas valide de la modification
        $member1 = new Member();
        $member1->setRole("REFERENT");
        $this->assertEquals("REFERENT",$member1->getRole());

        // Vérification du cas invalide de la modification où la valeur rentrée n'est pas précisée dans le check


        // Vérification du cas invalide de la modification où la valeur rentrée n'est pas un string

    }




}