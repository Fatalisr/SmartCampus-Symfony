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
            ->andWhere('i.type_i = :type_i')
            ->setParameter('type_i', "INSTALLATION")
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
            ->andWhere('i.type_i = :type_i')
            ->andWhere('i.endingDate IS NULL')
            ->setParameter('type_i', "MAINTENANCE")
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findOneById($sa,$type)
    {
        if($type == "maint")
        {
            $conn = $this->getEntityManager()->getConnection();

            $sql = "
            SELECT * FROM Intervention I
            WHERE I.type_i = :type AND I.sa_id = :id AND I.endingDate IS NULL
            ORDER BY p.price ASC
            ";

            $resultSet = $conn->executeQuery($sql, ['type' => "MAINTENANCE",'id' => $sa]);

            // returns an array of arrays (i.e. a raw data set)
            return $resultSet->fetchAllAssociative();
        }
        if($type == "insta")
        {
            $conn = $this->getEntityManager()->getConnection();

            $sql = "
            SELECT * FROM Intervention I
            WHERE I.type_i = :type AND I.sa_id = :id AND I.endingDate IS NULL
            ORDER BY p.price ASC
            ";

            $resultSet = $conn->executeQuery($sql, ['type' => "INSTALLATION",'id' => $sa]);

            // returns an array of arrays (i.e. a raw data set)
            return $resultSet->fetchAllAssociative();

        }

    }
}

/*
 *  return $this->createQueryBuilder('i')
                ->andWhere('i.type = :type')
                ->andWhere('i.sa_id = :sa')
                ->andWhere('i.endingDate IS NULL')
                ->setParameter('type', "INSTALLATION")
                ->setParameter('sa', $sa)
                ->orderBy('i.id', 'DESC')
                ->getQuery()
                ->getResult()
                ;
 */