<?php

namespace App\Repository;

use App\Entity\QualityCtrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QualityCtrl>
 *
 * @method QualityCtrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method QualityCtrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method QualityCtrl[]    findAll()
 * @method QualityCtrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QualityCtrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QualityCtrl::class);
    }

    public function save(QualityCtrl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QualityCtrl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return QualityCtrl[] Returns an array of QualityCtrl objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QualityCtrl
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
