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
        //      Création des users         //
        // =============================== //


        $ref1 = new User();
        $ref1->setUsername("ref1");
        $ref1->setPassword("$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS");
        $ref1->setRoles(["ROLE_REFERENT"]);
        $this->addReference('ref1',$ref1 );
        $manager->persist($ref1);

        $tech1 = new User();
        $tech1->setUsername("tec1");
        //hash le password avec php bin/console security:hash-password
        $tech1->setPassword("$2y$13$/Bpyv7s0SexmSOxxaINszOMmtqs7iSIFINdzBfKAQUAmHMthVAKzS");
        $tech1->setRoles(["ROLE_TECHNICIEN"]);
        $this->addReference('tech1',$tech1 );
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

// Création de la salle D203
        $D203 = new Room();
        $D203->setName("D203");
        $D203->setNbComputer("2");
        $D203->setFacing("N");
        $manager->persist($D203);
        $this->addReference('D203',$D203);

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

// Création de la salle D307
        $D307 = new Room();
        $D307->setName("D307");
        $D307->setNbComputer("15");
        $D307->setFacing("N");
        $manager->persist($D307);
        $this->addReference('D307',$D307);

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

// Création de la salle Secrétariat
        $Secret = new Room();
        $Secret->setName("Secrétariat");
        $Secret->setNbComputer("4");
        $Secret->setFacing("S");
        $manager->persist($Secret);
        $this->addReference('Secrétariat',$Secret);

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


        // =============================== //
        //      Création des SA            //
        // =============================== //

        // SA 1
        $sa1 = new SA();
        $sa1->setName("sae34bdk1eq1");
        $sa1->setState("ACTIF");
        $sa1->setCurrentRoom($this->getReference('D205'));
        $this->addReference('sa1',$sa1);
        $manager->persist($sa1);

        // SA 2
        $sa2 = new SA();
        $sa2->setName("sae34bdk1eq2");
        $sa2->setState("ACTIF");
        $sa2->setCurrentRoom($this->getReference('D206'));
        $this->addReference('sa2',$sa2);
        $manager->persist($sa2);

        // SA 3
        $sa3 = new SA();
        $sa3->setName("sae34bdk1eq3");
        $sa3->setState("ACTIF");
        $sa3->setCurrentRoom($this->getReference('D207'));
        $this->addReference('sa3',$sa3);
        $manager->persist($sa3);

        // SA 4
        $sa4 = new SA();
        $sa4->setName("sae34bdk2eq1");
        $sa4->setState("ACTIF");
        $sa4->setCurrentRoom($this->getReference('D204'));
        $this->addReference('sa4',$sa4);
        $manager->persist($sa4);

        // SA 5
        $sa5 = new SA();
        $sa5->setName("sae34bdk2eq2");
        $sa5->setState("ACTIF");
        $sa5->setCurrentRoom($this->getReference('D203'));
        $this->addReference('sa5',$sa5);
        $manager->persist($sa5);

        // SA 6
        $sa6 = new SA();
        $sa6->setName("sae34bdk2eq3");
        $sa6->setState("ACTIF");
        $sa6->setCurrentRoom($this->getReference('D303'));
        $this->addReference('sa6',$sa6);
        $manager->persist($sa6);

        // SA 7
        $sa7 = new SA();
        $sa7->setName("sae34bdl1eq1");
        $sa7->setState("ACTIF");
        $sa7->setCurrentRoom($this->getReference('D304'));
        $this->addReference('sa7',$sa7);
        $manager->persist($sa7);

        // SA 8
        $sa8 = new SA();
        $sa8->setName("sae34bdl1eq2");
        $sa8->setState("ACTIF");
        $sa8->setCurrentRoom($this->getReference('C101'));
        $this->addReference('sa8',$sa8);
        $manager->persist($sa8);

        // SA 9
        $sa9 = new SA();
        $sa9->setName("sae34bdl1eq3");
        $sa9->setState("ACTIF");
        $sa9->setCurrentRoom($this->getReference('D109'));
        $this->addReference('sa9',$sa9);
        $manager->persist($sa9);

        // SA 10
        $sa10 = new SA();
        $sa10->setName("sae34bdl2eq1");
        $sa10->setState("ACTIF");
        $sa10->setCurrentRoom($this->getReference('Secrétariat'));
        $this->addReference('sa10',$sa10);
        $manager->persist($sa10);

        // SA 11
        $sa11 = new SA();
        $sa11->setName("sae34bdl2eq2");
        $sa11->setState("ACTIF");
        $sa11->setCurrentRoom($this->getReference('D001'));
        $this->addReference('sa11',$sa11);
        $manager->persist($sa11);

        // SA 12
        $sa12 = new SA();
        $sa12->setName("sae34bdl2eq3");
        $sa12->setState("ACTIF");
        $sa12->setCurrentRoom($this->getReference('D002'));
        $this->addReference('sa12',$sa12);
        $manager->persist($sa12);

        // SA 13
        $sa13 = new SA();
        $sa13->setName("sae34bdm1eq1");
        $sa13->setState("ACTIF");
        $sa13->setCurrentRoom($this->getReference('D004'));
        $this->addReference('sa13',$sa13);
        $manager->persist($sa13);

        // SA 14
        $sa14 = new SA();
        $sa14->setName("sae34bdm1eq2");
        $sa14->setState("ACTIF");
        $sa14->setCurrentRoom($this->getReference('C004'));
        $this->addReference('sa14',$sa14);
        $manager->persist($sa14);

        // SA 15
        $sa15 = new SA();
        $sa15->setName("sae34bdm1eq3");
        $sa15->setState("ACTIF");
        $sa15->setCurrentRoom($this->getReference('C007'));
        $this->addReference('sa15',$sa15);
        $manager->persist($sa15);

        // SA 16
        $sa16 = new SA();
        $sa16->setName("sae34bdm2eq1");
        $sa16->setState("ACTIF");
        $sa16->setCurrentRoom($this->getReference('D201'));
        $this->addReference('sa16',$sa16);
        $manager->persist($sa16);

        // SA 17
        $sa17 = new SA();
        $sa17->setName("sae34bdm2eq2");
        $sa17->setState("ACTIF");
        $sa17->setCurrentRoom($this->getReference('D307'));
        $this->addReference('sa17',$sa17);
        $manager->persist($sa17);

        // SA 18
        $sa18 = new SA();
        $sa18->setName("sae34bdm2eq3");
        $sa18->setState("ACTIF");
        $sa18->setCurrentRoom($this->getReference('C005'));
        $this->addReference('sa18',$sa18);
        $manager->persist($sa18);

        // =============================== //
        // Création des salles d'exemples  //
        // =============================== //

// Création de la salle test1
        $test1 = new Room();
        $test1->setName("test1");
        $test1->setNbComputer("15");
        $test1->setFacing("N");
        $manager->persist($test1);
        $this->addReference('test1',$test1);

// Création de la salle test2
        $test2 = new Room();
        $test2->setName("test2");
        $test2->setNbComputer("15");
        $test2->setFacing("N");
        $manager->persist($test2);
        $this->addReference('test2',$test2);

        // Création de la salle test3
        $test3 = new Room();
        $test3->setName("test3");
        $test3->setNbComputer("15");
        $test3->setFacing("N");
        $manager->persist($test3);
        $this->addReference('test3',$test3);

        // Création de la salle test4
        $test4 = new Room();
        $test4->setName("test4");
        $test4->setNbComputer("15");
        $test4->setFacing("N");
        $manager->persist($test4);
        $this->addReference('test4',$test4);

        // Création de la salle test5
        $test5 = new Room();
        $test5->setName("test5");
        $test5->setNbComputer("15");
        $test5->setFacing("N");
        $manager->persist($test5);
        $this->addReference('test5',$test5);

        // Création de la salle test6
        $test6 = new Room();
        $test6->setName("test6");
        $test6->setNbComputer("15");
        $test6->setFacing("N");
        $manager->persist($test6);
        $this->addReference('test6',$test6);

        // Création de la salle test7
        $test7 = new Room();
        $test7->setName("test7");
        $test7->setNbComputer("15");
        $test7->setFacing("N");
        $manager->persist($test7);
        $this->addReference('test7',$test7);

        // Création de la salle test8
        $test8 = new Room();
        $test8->setName("test8");
        $test8->setNbComputer("15");
        $test8->setFacing("N");
        $manager->persist($test8);
        $this->addReference('test8',$test8);

        // Création de la salle test9
        $test9 = new Room();
        $test9->setName("test9");
        $test9->setNbComputer("15");
        $test9->setFacing("N");
        $manager->persist($test9);
        $this->addReference('test9',$test9);

        // Création de la salle test10
        $test10 = new Room();
        $test10->setName("test10");
        $test10->setNbComputer("15");
        $test10->setFacing("N");
        $manager->persist($test10);
        $this->addReference('test10',$test10);

        // Création de la salle test11
        $test11 = new Room();
        $test11->setName("test11");
        $test11->setNbComputer("15");
        $test11->setFacing("N");
        $manager->persist($test11);
        $this->addReference('test11',$test11);

        // Création de la salle test12
        $test12 = new Room();
        $test12->setName("test12");
        $test12->setNbComputer("15");
        $test12->setFacing("N");
        $manager->persist($test12);
        $this->addReference('test12',$test12);

        // Création de la salle test13
        $test13 = new Room();
        $test13->setName("test13");
        $test13->setNbComputer("15");
        $test13->setFacing("N");
        $manager->persist($test13);
        $this->addReference('test13',$test13);

        // Création de la salle test14
        $test14 = new Room();
        $test14->setName("test14");
        $test14->setNbComputer("15");
        $test14->setFacing("N");
        $manager->persist($test14);
        $this->addReference('test14',$test14);

        // Création de la salle test15
        $test15 = new Room();
        $test15->setName("test15");
        $test15->setNbComputer("15");
        $test15->setFacing("N");
        $manager->persist($test15);
        $this->addReference('test15',$test15);

        // =============================== //
        //   Création des SA d'exemples    //
        // =============================== //

        // SA ex1
        $saex1 = new SA();
        $saex1->setName("SA01");
        $saex1->setState("ACTIF");
        $saex1->setCurrentRoom($this->getReference('test1'));
        $this->addReference('saex1',$saex1);
        $manager->persist($saex1);

        // SA ex2
        $saex2 = new SA();
        $saex2->setName("SA02");
        $saex2->setState("ACTIF");
        $saex2->setCurrentRoom($this->getReference('test2'));
        $this->addReference('saex2',$saex2);
        $manager->persist($saex2);

        // SA ex3
        $saex3 = new SA();
        $saex3->setName("SA03");
        $saex3->setState("A_INSTALLER");
        $saex3->setCurrentRoom($this->getReference('test4'));
        $saex3->setOldRoom($this->getReference('test3'));
        $this->addReference('saex3',$saex3);
        $manager->persist($saex3);

        // SA ex4
        $saex4 = new SA();
        $saex4->setName("SA04");
        $saex4->setState("A_INSTALLER");
        $saex4->setCurrentRoom($this->getReference('test5'));
        $this->addReference('saex4',$saex4);
        $manager->persist($saex4);

        // SA ex5
        $saex5 = new SA();
        $saex5->setName("SA05");
        $saex5->setState("MAINTENANCE");
        $saex5->setCurrentRoom($this->getReference('test6'));
        $this->addReference('saex5',$saex5);
        $manager->persist($saex5);

        // SA ex6
        $saex6 = new SA();
        $saex6->setName("SA06");
        $saex6->setState("MAINTENANCE");
        $saex6->setCurrentRoom($this->getReference('test7'));
        $this->addReference('saex6',$saex6);
        $manager->persist($saex6);

        // SA ex7
        $saex7 = new SA();
        $saex7->setName("SA07");
        $saex7->setState("INACTIF");
        $this->addReference('saex7',$saex7);
        $manager->persist($saex7);

        // SA ex8
        $saex8 = new SA();
        $saex8->setName("SA08");
        $saex8->setState("INACTIF");
        $this->addReference('saex8',$saex8);
        $manager->persist($saex8);

        // ===================================== //
        // Création des Interventions d'exemples //
        // ===================================== //

        //Interventions Installation sur SAex1
        $Inst1 = new Intervention();
        $Inst1->setState("FINIE");
        $Inst1->setSa($this->getReference('saex1'));
        $Inst1->setType_I("INSTALLATION");
        $Inst1->setMessage("Installation du SA1 en test1");
        $Inst1->setReport("Installation effectué sans probleme");
        $Inst1->setStartingDate(new \DateTime());
        $Inst1->setEndingDate(new \DateTime());
        $Inst1->setTechnicien($this->getReference('tech1'));
        $manager->persist($Inst1);

        //Interventions Installation sur SAex2
        $Inst2 = new Intervention();
        $Inst2->setState("FINIE");
        $Inst2->setSa($this->getReference('saex2'));
        $Inst2->setType_I("INSTALLATION");
        $Inst2->setMessage("Installation du SA2 en test2");
        $Inst2->setReport("Installation effectué sans probleme");
        $Inst2->setStartingDate(new \DateTime());
        $Inst2->setEndingDate(new \DateTime());
        $Inst2->setTechnicien($this->getReference('tech1'));
        $manager->persist($Inst2);

        // Intervention Installation sur SAex3
        $Inst3 = new Intervention();
        $Inst3->setState("EN_COURS");
        $Inst3->setSa($this->getReference('saex3'));
        $Inst3->setMessage("Changement du SA3 de la salle test3 à la test4");
        $Inst3->setStartingDate(new \DateTime());
        $Inst3->setType_I('INSTALLATION');
        $manager->persist($Inst3);

        //Intervention Installation sur SAex4
        $Inst4 = new Intervention();
        $Inst4->setState("EN_COURS");
        $Inst4->setSa($this->getReference('saex4'));
        $Inst4->setMessage("Installation du SA4 dans la salle test5");
        $Inst4->setStartingDate(new \DateTime());
        $Inst4->setType_I("INSTALLATION");
        $Inst4->setTechnicien($this->getReference('tech1'));
        $manager->persist($Inst4);

        // Intervention Maintenance sur SAex5
        $Maint1 = new Intervention();
        $Maint1->setState("EN_COURS");
        $Maint1->setSa($this->getReference('saex5'));
        $Maint1->setMessage("Le capteur de CO2 ne remonte plus de données, verifier le capteur et les branchements");
        $Maint1->setStartingDate(new \DateTime());
        $Maint1->setType_I('MAINTENANCE');
        $manager->persist($Maint1);

        //Intervention Maintenance sur SAex6
        $Maint2 = new Intervention();
        $Maint2->setState("EN_COURS");
        $Maint2->setSa($this->getReference('saex6'));
        $Maint2->setMessage("Le capteur d'humidité/température retourne des valeurs anormalement élevé, verifier le capteur et les branchements");
        $Maint2->setStartingDate(new \DateTime());
        $Maint2->setType_I("MAINTENANCE");
        $Maint2->setTechnicien($this->getReference('tech1'));
        $manager->persist($Maint2);

        //Intervention Maintenance sur SAex2
        $Maint3 = new Intervention();
        $Maint3->setState("ANNULEE");
        $Maint3->setSa($this->getReference('saex2'));
        $Maint3->setType_I("MAINTENANCE");
        $Maint3->setMessage("Les données du sa presentent d'enorment incoherences. Il faut verifier les connectiques capteurs.");
        $Maint3->setReport("Impossible de trouver d'ou vienne les incoherences. Retour du SA au depot.");
        $Maint3->setStartingDate(new \DateTime());
        $Maint3->setEndingDate(new \DateTime());
        $Maint3->setTechnicien($this->getReference('tech1'));
        $manager->persist($Maint3);

        $manager->flush();

    }
}
