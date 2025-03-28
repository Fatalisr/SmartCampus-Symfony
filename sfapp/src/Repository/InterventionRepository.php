<?php

namespace App\Repository;

use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
            ->andWhere('i.type_i = :type_i')
            ->andWhere('i.state = :state')
            ->setParameter('type_i', "INSTALLATION")
            ->andWhere('i.endingDate IS NULL')
            ->setParameter('state', "EN_COURS")
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
            ->andWhere('i.type_i = :type_i')
            ->andWhere('i.endingDate IS NULL')
            ->andWhere('i.state = :state')
            ->setParameter('type_i', "MAINTENANCE")
            ->setParameter('state', "EN_COURS")
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findOneById($sa,$type): ?Intervention
    {

        try {
            return $this->createQueryBuilder('i')
                ->andWhere('i.type_i = :type')
                ->andWhere('i.sa = :sa')
                ->andWhere('i.state = EN_COURS')
                ->setParameter('sa', $sa)
                ->setParameter('type', $type)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneBySA($sa)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.sa = :sa')
            ->setParameter('sa', $sa)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySAAndCurrent($sa)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.sa = :sa')
            ->setParameter('sa', $sa)
            ->andWhere('i.state = :state')
            ->setParameter('state', "EN_COURS")
            ->getQuery()
            ->getOneOrNullResult();
    }
}