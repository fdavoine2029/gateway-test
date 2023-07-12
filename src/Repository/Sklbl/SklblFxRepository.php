<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblFx;
use App\Entity\Sklbl\SklblOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblFx>
 *
 * @method SklblFx|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblFx|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblFx[]    findAll()
 * @method SklblFx[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblFxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblFx::class);
    }

    public function save(SklblFx $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblFx $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function countFxTraited($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 0)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getFxInTraitement(SklblOrders $sklblOrder){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        
                sku.sku,
                sku.sku_tisse,
                fx.unique_id,
                fx.redirect_url,
                fx.sent_on,
                fx.received_on,
                status.name as status
                from sklbl_fx as fx
                left join sklbl_sku as sku
                on sku.id = fx.sklbl_sku_id
                left join sklbl_orders as ord
                on ord.id = sku.sklbl_order_id
                left join sklbl_files as file
                on file.id = sku.sklbl_file_id
                left join status
                on status.categorie = 'sklbl_fx' and status.code = fx.status
                where ord.id = ".$sklblOrder->getId()."
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function searchSklblSku($value): ?SklblFx
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblSku = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return SklblFx[] Returns an array of sklblFx objects
     */
    public function getFxATransfereListBySku($sku): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.sklblSku = :sklblSku')
            ->andWhere('s.status = :status')
            ->setParameter('sklblSku', $sku)
            ->setParameter('status', 0)
            ->getQuery()
            ->getResult()
        ;
    }


//    /**
//     * @return SklblFx[] Returns an array of SklblFx objects
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

    
}
