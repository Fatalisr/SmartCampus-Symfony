<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Maintenance;
use App\Entity\Member;
use App\Entity\Room;
use App\Entity\SA;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Referent 1
        $ref1 = new Member();
        $ref1->setLogIn("ref1");
        $ref1->setPassword("123");
        $ref1->setRole("REFERENT");
        $manager->persist($ref1);

        //Technicien 1
        $tech1 = new Member();
        $tech1->setLogIn("tech1");
        $tech1->setPassword("456");
        $tech1->setRole("TECHNICIEN");
        $manager->persist($tech1);

        // Room 1
        $r1 = new Room();
        $r1->setName("D204");
        $r1->setNbComputer("20");
        $r1->setFacing("S");
        $manager->persist($r1);
        $this->addReference('r1',$r1);
        // Room 2
        $r2 = new Room();
        $r2->setName("D207");
        $r2->setNbComputer("15");
        $r2->setFacing("N");
        $manager->persist($r2);
        $this->addReference('r2',$r2);
        // Room 3
        $r3 = new Room();
        $r3->setName("D002");
        $r3->setNbComputer("0");
        $r3->setFacing("S");
        $manager->persist($r3);
        $this->addReference('r3',$r3);


        // SA 1
        $sa = new SA();
        $sa->setName("SA1");
        $sa->setState("ACTIF");
        $sa->setCurrentRoom($this->getReference('r1'));
        $manager->persist($sa);
        // SA 2
        $sa2 = new SA();
        $sa2->setName("SA2");
        $sa2->setState("A_INSTALLER");
        $sa2->setCurrentRoom($this->getReference('r2'));
        $manager->persist($sa2);
        // SA 3
        $sa3 = new SA();
        $sa3->setName("SA3");
        $sa3->setState("MAINTENANCE");
        $sa3->setCurrentRoom($this->getReference('r3'));
        $manager->persist($sa3);


        $manager->flush();
    }
}
