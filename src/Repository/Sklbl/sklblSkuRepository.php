<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\sklblSku;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<sklblSku>
 *
 * @method sklblSku|null find($id, $lockMode = null, $lockVersion = null)
 * @method sklblSku|null findOneBy(array $criteria, array $orderBy = null)
 * @method sklblSku[]    findAll()
 * @method sklblSku[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class sklblSkuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, sklblSku::class);
    }

    public function save(sklblSku $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(sklblSku $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countFaconnier(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.vendor)')
            ->where('u.sklblOrder = :orderId')
            ->setParameter('orderId', $sklblOrder)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countSku(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.sku)')
            ->where('u.sklblOrder = :orderId')
            ->setParameter('orderId', $sklblOrder)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countQte(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('SUM(u.order_qte)')
            ->where('u.sklblOrder = :orderId')
            ->setParameter('orderId', $sklblOrder)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countProduceQte(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('SUM(u.produce_qte)')
            ->where('u.sklblOrder = :orderId')
            ->setParameter('orderId', $sklblOrder)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countOffQte(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('SUM(u.off_qte)')
            ->where('u.sklblOrder = :orderId')
            ->setParameter('orderId', $sklblOrder)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return sklblSku[] Returns an array of sklblSku objects
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

//    public function findOneBySomeField($value): ?sklblSku
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
