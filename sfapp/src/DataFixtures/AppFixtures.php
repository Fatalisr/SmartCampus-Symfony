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

        // =============================== //
        //      Création des salles        //
        // =============================== //

        /*      Rez-de-chaussée      */

        // Création de la salle du BDE Réseaux Télécom
        $bde_rt = new Room();
        $bde_rt->setName("BDE-R&T");
        $bde_rt->setNbComputer("0");
        $bde_rt->setFacing("N");
        $manager->persist($bde_rt);
        $this->addReference('bde_rt',$bde_rt);

        // Création de la salle C004
        $COO4 = new Room();
        $COO4->setName("C004");
        $COO4->setNbComputer("16");
        $COO4->setFacing("S");
        $manager->persist($COO4);
        $this->addReference('C004',$COO4);

        // Création de la salle C005
        $COO5 = new Room();
        $COO5->setName("C005");
        $COO5->setNbComputer("16");
        $COO5->setFacing("N");
        $manager->persist($COO5);
        $this->addReference('C005',$COO5);

        // Création de la salle C006
        $COO6 = new Room();
        $COO6->setName("C006");
        $COO6->setNbComputer("0");
        $COO6->setFacing("S");
        $manager->persist($COO6);
        $this->addReference('C006',$COO6);

        // Création de la salle C007
        $COO7 = new Room();
        $COO7->setName("C007");
        $COO7->setNbComputer("2");
        $COO7->setFacing("N");
        $manager->persist($COO7);
        $this->addReference('C007',$COO7);

        // Création de la salle C007
        $COO7 = new Room();
        $COO7->setName("C007");
        $COO7->setNbComputer("2");
        $COO7->setFacing("N");
        $manager->persist($COO7);
        $this->addReference('C007',$COO7);

        // Création de la salle D001
        $DOO1 = new Room();
        $DOO1->setName("D001");
        $DOO1->setNbComputer("0");
        $DOO1->setFacing("N");
        $manager->persist($DOO1);
        $this->addReference('D001',$DOO1);

        // Création de la salle D002
        $DOO2 = new Room();
        $DOO2->setName("D002");
        $DOO2->setNbComputer("0");
        $DOO2->setFacing("S");
        $manager->persist($DOO2);
        $this->addReference('D002',$DOO2);

        // Création de la salle D003
        $DOO3 = new Room();
        $DOO3->setName("D003");
        $DOO3->setNbComputer("0");
        $DOO3->setFacing("N");
        $manager->persist($DOO3);
        $this->addReference('D003',$DOO3);

        // Création de la salle D004
        $DOO4 = new Room();
        $DOO4->setName("D004");
        $DOO4->setNbComputer("0");
        $DOO4->setFacing("S");
        $manager->persist($DOO4);
        $this->addReference('D004',$DOO4);

        // Création de la salle D005
        $DOO5 = new Room();
        $DOO5->setName("D005");
        $DOO5->setNbComputer("0");
        $DOO5->setFacing("N");
        $manager->persist($DOO5);
        $this->addReference('D005',$DOO5);

        // Création de la salle du BDE Informatique
        $bde_it = new Room();
        $bde_it->setName("BDE-Info");
        $bde_it->setNbComputer("2");
        $bde_it->setFacing("N");
        $manager->persist($bde_it);
        $this->addReference('bde_it',$bde_it);

        /*      1er étage      */

        // Création de la salle C101
        $C101 = new Room();
        $C101->setName("C101");
        $C101->setNbComputer("16");
        $C101->setFacing("N");
        $manager->persist($C101);
        $this->addReference('C101',$C101);

        // Création de la salle C104
        $C104 = new Room();
        $C104->setName("C104");
        $C104->setNbComputer("16");
        $C104->setFacing("S");
        $manager->persist($C104);
        $this->addReference('C104',$C104);

        // Création de la salle C107
        $C107 = new Room();
        $C107->setName("C107");
        $C107->setNbComputer("16");
        $C107->setFacing("N");
        $manager->persist($C107);
        $this->addReference('C107',$C107);

        // Création de la salle C110
        $C110 = new Room();
        $C110->setName("C110");
        $C110->setNbComputer("16");
        $C110->setFacing("S");
        $manager->persist($C110);
        $this->addReference('C110',$C110);

        // Création de la salle D101
        $D101 = new Room();
        $D101->setName("D101");
        $D101->setNbComputer("1");
        $D101->setFacing("N");
        $manager->persist($D101);
        $this->addReference('D101',$D101);

        // Création de la salle D104
        $D104 = new Room();
        $D104->setName("D104");
        $D104->setNbComputer("2");
        $D104->setFacing("S");
        $manager->persist($D104);
        $this->addReference('D104',$D104);

        // Création de la salle D106
        $D106 = new Room();
        $D106->setName("D106");
        $D106->setNbComputer("4");
        $D106->setFacing("N");
        $manager->persist($D106);
        $this->addReference('D106',$D106);

        // Création de la salle D108
        $D108 = new Room();
        $D108->setName("D108");
        $D108->setNbComputer("4");
        $D108->setFacing("S");
        $manager->persist($D108);
        $this->addReference('D108',$D108);





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
        // SA 4
        $sa4 = new SA();
        $sa4->setName("SA3");
        $sa4->setState("INACTIF");
        $manager->persist($sa4);

        $manager->flush();
    }
}
