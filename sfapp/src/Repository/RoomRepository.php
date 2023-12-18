<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 *
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * @return Room[] Returns an array of Room objects
     */
    public function getRoomFloor($floor){
        $query = $this->createQueryBuilder('r')
            ->where('SUBSTRING(r.name, 2, 1) = :etage')
            ->setParameter('etage', $floor)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return Room[] Returns an array of Room objects
     */
    public function getRoomRDC(){
        $query = $this->createQueryBuilder('r')
            ->where('SUBSTRING(r.name, 2, 1) = :etage')
            ->setParameter('etage', '0')
            ->getQuery();

        return $query->getResult();
    }




//    /**
//     * @return Room[] Returns an array of Room objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Room
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
