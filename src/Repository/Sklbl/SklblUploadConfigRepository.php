<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblUploadConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblUploadConfig>
 *
 * @method SklblUploadConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblUploadConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblUploadConfig[]    findAll()
 * @method SklblUploadConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblUploadConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblUploadConfig::class);
    }

    public function save(SklblUploadConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblUploadConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findColumn($order,$num): ?SklblUploadConfig
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblOrder = :sklblOrder')
            ->andWhere('s.num = :num')
            ->setParameter('sklblOrder', $order)
            ->setParameter('num', $num)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function countColumn($order){
        return $this->createQueryBuilder('u')
        ->select('COUNT(DISTINCT u.id)')
        ->where('u.sklblOrder = :order')
        ->setParameter('order', $order)
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function initStatut($order){
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.status', '?1')
            ->setParameter(1, 0)
            ->where('u.sklblOrder = ?2')
            ->setParameter(2, $order)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findBySklblOrderActive($sklblOrder): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblOrder = :sklblOrder')
            ->setParameter('sklblOrder', $sklblOrder)
            ->andWhere('s.status = :status')
            ->setParameter('status', 1)
            ->orderBy('s.num', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    

//    /**
//     * @return SklblUploadConfig[] Returns an array of SklblUploadConfig objects
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

//    public function findOneBySomeField($value): ?SklblUploadConfig
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
