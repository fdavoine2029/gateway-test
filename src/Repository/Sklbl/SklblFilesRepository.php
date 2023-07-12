<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblFiles>
 *
 * @method SklblFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblFiles[]    findAll()
 * @method SklblFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblFiles::class);
    }

    public function save(SklblFiles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblFiles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    

    /**
     * @return SklblFiles[] Returns an array of SklblFiles objects
     */
    public function getFileNonTransfereList($order): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblOrder = :sklblOrder')
            ->setParameter('sklblOrder', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFileATransfereList($order): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblOrder = :sklblOrder')
            ->andWhere('s.status = :status')
            ->setParameter('sklblOrder', $order)
            ->setParameter('status', 1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countFilesTraite($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 1)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function countFilesNonTraite($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 0)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    public function findOneBySomeField($value): ?SklblFiles
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
