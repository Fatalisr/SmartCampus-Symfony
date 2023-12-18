<?php

namespace App\Repository;

use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Intervention>
 *
 * @method Intervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervention[]    findAll()
 * @method Intervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }

    /**
     * @return Intervention[] Returns an array of Intervention objects
     */
    public function findAllInstallations(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.type = :type')
            ->setParameter('type', "INSTALLATION")
            ->andWhere('i.endingDate IS NULL')
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Intervention[] Returns an array of Intervention objects
     */
    public function findAllMaintenances(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.type = :type')
            ->andWhere('i.endingDate IS NULL')
            ->setParameter('type', "MAINTENANCE")
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findInstallationBySAId($sa)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.type = :type')
            ->andWhere('i.sa = :sa')
            ->andWhere('i.endingDate IS NULL')
            ->setParameter('type', "INSTALLATION")
            ->setParameter('sa', $sa)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;

    }
}