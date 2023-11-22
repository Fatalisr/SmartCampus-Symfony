<?php

// tests/Repository/ProductRepositoryTest.php

namespace App\Tests\Repository;

use App\Entity\SA;
use App\Entity\Room;
use App\Repository\SARepository;
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


        $products = $this->entityManager
        ->getRepository(SA::class)
        ->findAllPlanAction();

        $this->assertCount(3,$products);
    }

    public function testFindAllInactive(): void
    {

        $products = $this->entityManager
            ->getRepository(SA::class)
            ->findAllInactive();

        $this->assertCount(1,$products);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}