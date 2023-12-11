<?php

namespace App\DataFixtures;

use App\Entity\Intervention;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Room;
use App\Entity\SA;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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

        // Création de la salle C103
        $C103 = new Room();
        $C103->setName("C103");
        $C103->setNbComputer("16");
        $C103->setFacing("N");
        $manager->persist($C103);
        $this->addReference('C103',$C103);

        // Création de la salle C105
        $C105 = new Room();
        $C105->setName("C105");
        $C105->setNbComputer("16");
        $C105->setFacing("N");
        $manager->persist($C105);
        $this->addReference('C105',$C105);

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
// Création de la salle D105
        $D105 = new Room();
        $D105->setName("D105");
        $D105->setNbComputer("1");
        $D105->setFacing("N");
        $manager->persist($D105);
        $this->addReference('D105',$D105);

// Création de la salle D107
        $D107 = new Room();
        $D107->setName("D107");
        $D107->setNbComputer("1");
        $D107->setFacing("N");
        $manager->persist($D107);
        $this->addReference('D107',$D107);

// Création de la salle D109
        $D109 = new Room();
        $D109->setName("D109");
        $D109->setNbComputer("1");
        $D109->setFacing("N");
        $manager->persist($D109);
        $this->addReference('D109',$D109);

// ------ 2eme étage --------
// INFORMATIQUE

// Création de la salle D201
        $D201 = new Room();
        $D201->setName("D201");
        $D201->setNbComputer("0");
        $D201->setFacing("N");
        $manager->persist($D201);
        $this->addReference('D201',$D201);

// Création de la salle D205
        $D205 = new Room();
        $D205->setName("D205");
        $D205->setNbComputer("15");
        $D205->setFacing("N");
        $manager->persist($D205);
        $this->addReference('D205',$D205);

// Création de la salle D207
        $D207 = new Room();
        $D207->setName("D207");
        $D207->setNbComputer("15");
        $D207->setFacing("N");
        $manager->persist($D207);
        $this->addReference('D207',$D207);

// Création de la salle D204
        $D204 = new Room();
        $D204->setName("D204");
        $D204->setNbComputer("15");
        $D204->setFacing("S");
        $manager->persist($D204);
        $this->addReference('D204',$D204);

// Création de la salle D206
        $D206 = new Room();
        $D206->setName("D206");
        $D206->setNbComputer("15");
        $D206->setFacing("S");
        $manager->persist($D206);
        $this->addReference('D206',$D206);


// RESEAU ET TELECOM

// Création de la salle C205
        $C205 = new Room();
        $C205->setName("C205");
        $C205->setNbComputer("15");
        $C205->setFacing("N");
        $manager->persist($C205);
        $this->addReference('C205',$C205);


// Création de la salle C207
        $C207 = new Room();
        $C207->setName("C207");
        $C207->setNbComputer("15");
        $C207->setFacing("N");
        $manager->persist($C207);
        $this->addReference('C207',$C207);

// Création de la salle C204
        $C204 = new Room();
        $C204->setName("C204");
        $C204->setNbComputer("15");
        $C204->setFacing("S");
        $manager->persist($C204);
        $this->addReference('C204',$C204);

// Création de la salle C206
        $C206 = new Room();
        $C206->setName("C206");
        $C206->setNbComputer("15");
        $C206->setFacing("S");
        $manager->persist($C206);
        $this->addReference('C206',$C206);

// ------ 3eme étage --------

// Création de la salle D301
        $D301 = new Room();
        $D301->setName("D301");
        $D301->setNbComputer("15");
        $D301->setFacing("N");
        $manager->persist($D301);
        $this->addReference('D301',$D301);

// Création de la salle D303
        $D303 = new Room();
        $D303->setName("D303");
        $D303->setNbComputer("15");
        $D303->setFacing("N");
        $manager->persist($D303);
        $this->addReference('D303',$D303);

// Création de la salle D305
        $D305 = new Room();
        $D305->setName("D305");
        $D305->setNbComputer("15");
        $D305->setFacing("N");
        $manager->persist($D305);
        $this->addReference('D305',$D305);

// Création de la salle D302
        $D302 = new Room();
        $D302->setName("D302");
        $D302->setNbComputer("15");
        $D302->setFacing("S");
        $manager->persist($D302);
        $this->addReference('D302',$D302);

// Création de la salle D304
        $D304 = new Room();
        $D304->setName("D304");
        $D304->setNbComputer("15");
        $D304->setFacing("S");
        $manager->persist($D304);
        $this->addReference('D304',$D304);

// Création de la salle D306
        $D306 = new Room();
        $D306->setName("D306");
        $D306->setNbComputer("15");
        $D306->setFacing("S");
        $manager->persist($D306);
        $this->addReference('D306',$D306);

// RESEAU ET TELECOM

// Création de la salle C302
        $C302 = new Room();
        $C302->setName("C302");
        $C302->setNbComputer("15");
        $C302->setFacing("S");
        $manager->persist($C302);
        $this->addReference('C302',$C302);

// Création de la salle C304
        $C304 = new Room();
        $C304->setName("C304");
        $C304->setNbComputer("15");
        $C304->setFacing("S");
        $manager->persist($C304);
        $this->addReference('C304',$C304);

// Création de la salle C306
        $C306 = new Room();
        $C306->setName("C306");
        $C306->setNbComputer("15");
        $C306->setFacing("S");
        $manager->persist($C306);
        $this->addReference('C306',$C306);

// Création de la salle C305
        $C305 = new Room();
        $C305->setName("C305");
        $C305->setNbComputer("15");
        $C305->setFacing("N");
        $manager->persist($C305);
        $this->addReference('C305',$C305);

// Création de la salle C307
        $C307 = new Room();
        $C307->setName("C307");
        $C307->setNbComputer("15");
        $C307->setFacing("N");
        $manager->persist($C307);
        $this->addReference('C307',$C307);




        // SA 1
        $sa = new SA();
        $sa->setName("SA1");
        $sa->setState("ACTIF");
        $sa->setCurrentRoom($this->getReference('D001'));
        $manager->persist($sa);

        $sa12 = new SA();
        $sa12->setName("SA12");
        $sa12->setState("ACTIF");
        $sa12->setCurrentRoom($this->getReference('D306'));
        $manager->persist($sa12);

        $sa13 = new SA();
        $sa13->setName("SA13");
        $sa13->setState("ACTIF");
        $sa13->setCurrentRoom($this->getReference('C004'));
        $manager->persist($sa13);

        // SA 2
        $sa2 = new SA();
        $sa2->setName("SA2");
        $sa2->setState("A_INSTALLER");
        $sa2->setCurrentRoom($this->getReference('C005'));
        $manager->persist($sa2);
        // SA 3
        $sa3 = new SA();
        $sa3->setName("SA3");
        $sa3->setState("MAINTENANCE");
        $sa3->setCurrentRoom($this->getReference('D304'));
        $manager->persist($sa3);
        // SA 4
        $sa4 = new SA();
        $sa4->setName("SA3");
        $sa4->setState("INACTIF");
        $manager->persist($sa4);

        //Installation 1
        $Inst1 = new Intervention();
        $Inst1->setSa($sa2);
        $Inst1->setType("INSTALLATION");
        $Inst1->setStartingDate(new \DateTime());
        $manager->persist($Inst1);


        $ref1 = new User();
        $ref1->setUsername("ref1");
        $ref1->setPassword("$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS");
        $ref1->setRoles(["ROLE_REFERENT"]);
        $manager->persist($ref1);

        $ref1 = new User();
        $ref1->setUsername("tec1");
        //hash le password avec php bin/console security:hash-password
        $ref1->setPassword("$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS");
        $ref1->setRoles(["ROLE_TECHNICIEN"]);
        $manager->persist($ref1);

        $manager->flush();
    }
}
