<?php

namespace App\Tests;

use App\Entity\SA;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SARepositoryTest extends KernelTestCase
{
    private \Doctrine\ORM\EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

    $this->entityManager = $kernel->getContainer()
        ->get('doctrine')
        ->getManager();
    }

    public function testFindAllPlanAction(): void
    {
        $sa = new SA();
        $sa->setName("Test_SA1");
        $sa->setState("ACTIF");
        $this->entityManager->persist($sa);

        $sa1 = new SA();
        $sa1->setName("Test_SA1");
        $sa1->setState("ACTIF");
        $this->entityManager->persist($sa1);

        $sa2 = new SA();
        $sa2->setName("Test_SA1");
        $sa2->setState("INACTIF");
        $this->entityManager->persist($sa2);

        $this->entityManager->flush();
        $SAs = $this->entityManager
        ->getRepository(SA::class)
        ->findAllPlanAction();
        $this->assertCount(2,$SAs);

        // Delete the entry in the database to avoid conflict with the tests
        $this->entityManager->beginTransaction(); // Begin a transaction
        $this->entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA1')->execute();
        $this->entityManager->commit();
    }

    public function testFindAllInactive(): void
    {
        $sa = new SA();
        $sa->setName("Test_SA1");
        $sa->setState("ACTIF");
        $this->entityManager->persist($sa);

        $sa1 = new SA();
        $sa1->setName("Test_SA1");
        $sa1->setState("ACTIF");
        $this->entityManager->persist($sa1);

        $sa2 = new SA();
        $sa2->setName("Test_SA1");
        $sa2->setState("INACTIF");
        $this->entityManager->persist($sa2);

        $this->entityManager->flush();
        $SAs = $this->entityManager
            ->getRepository(SA::class)
            ->findAllInactive();
        $this->assertCount(1,$SAs);

        // Delete the entry in the database to avoid conflict with the tests
        $this->entityManager->beginTransaction(); // Begin a transaction
        $this->entityManager->createQuery("DELETE FROM App\Entity\SA SA WHERE SA.name=:nom")->setParameter('nom','Test_SA1')->execute();
        $this->entityManager->commit();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}