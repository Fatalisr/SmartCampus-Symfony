<?php

namespace App\Repository;

use App\Entity\SA;
use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SA>
 *
 * @method SA|null find($id, $lockMode = null, $lockVersion = null)
 * @method SA|null findOneBy(array $criteria, array $orderBy = null)
 * @method SA[]    findAll()
 * @method SA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SA::class);
    }

    public function findAllPlanAction(): array
    {
        return $this->createQueryBuilder('s')
            ->orWhere("s.state != :state")
            ->setParameter('state',  "INACTIF")
            ->orderBy('s.state', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findAllActif(): array
    {
        return $this->createQueryBuilder('s')
            ->orWhere("s.state = :state")
            ->setParameter('state',  "ACTIF")
            ->orderBy('s.state', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAllMaintenance(): array
    {
        return $this->createQueryBuilder('s')
            ->orWhere("s.state = :state")
            ->setParameter('state',  "MAINTENANCE")
            ->orderBy('s.state', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAllInstaller(): array
    {
        return $this->createQueryBuilder('s')
            ->orWhere("s.state = :state")
            ->setParameter('state',  "A_INSTALLER")
            ->orderBy('s.state', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllInactive(): array
    {
        return $this->createQueryBuilder('s')
            ->orWhere("s.state = :state")
            ->setParameter('state',  "INACTIF")
            ->orderBy('s.state', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findMaintenanceBySAId($sa)
    {
        return $this->createQueryBuilder('s')
            ->select('i')
            ->from('App\Entity\Intervention', 'i')
            ->where('i.sa = :sa and i.endingDate is null')
            ->setParameter('sa', $sa)
            ->getQuery()
            ->getOneOrNullResult();

    }

//    /**
//     * @return SA[] Returns an array of SA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SA
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
