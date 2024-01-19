<?php

namespace App\Repository;

use App\Entity\PropertyClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PropertyClass>
 *
 * @method PropertyClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyClass[]    findAll()
 * @method PropertyClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyClass::class);
    }

//    /**
//     * @return PropertyClass[] Returns an array of PropertyClass objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PropertyClass
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
