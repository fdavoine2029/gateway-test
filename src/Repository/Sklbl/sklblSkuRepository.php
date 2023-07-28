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



    public function countSku(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
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
    public function countFxNonTraited(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('SUM(u.produce_qte)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $sklblOrder)
            ->setParameter('status', 1)
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
    public function countFichiers(SklblOrders $sklblOrder){
        return $this->createQueryBuilder('u')
            ->select('count(DISTINCT u.sklblFile)')
            ->where('u.sklblOrder = :orderId')
            ->setParameter('orderId', $sklblOrder)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countProduceByFileQte($sklblFile){
        return $this->createQueryBuilder('u')
            ->select('SUM(u.produce_qte)')
            ->where('u.sklblFile = :sklblFile')
            ->setParameter('sklblFile', $sklblFile)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countSkuNonTraite($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status < :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countSkuTraited($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countSkuAttenteTransfert($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countStep4AGenerer($file){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblFile = :fileId')
            ->andWhere('u.status = :status')
            ->setParameter('fileId', $file)
            ->setParameter('status', 2)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countStep4Genere($file){
        return $this->createQueryBuilder('u')
        ->select('COUNT(DISTINCT u.id)')
        ->where('u.sklblFile = :fileId')
        ->andWhere('u.status = :status')
        ->setParameter('fileId', $file)
        ->setParameter('status', 3)
        ->getQuery()
        ->getSingleScalarResult();
    }

    


    public function getSkuList(SklblOrders $sklblOrder){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select sku.*,file.client_filename,status.name as status
                from sklbl_sku as sku
                left join sklbl_orders as ord
                on ord.id = sku.sklbl_order_id
                left join sklbl_files as file
                on file.id = sku.sklbl_file_id
                left join status
                on status.categorie = 'sklbl_sku' and status.code = sku.status
                where ord.id = ".$sklblOrder->getId()."
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function getSkuNonTransfereList(SklblOrders $sklblOrder){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select sku.*,file.client_filename,status.name as status
                from sklbl_sku as sku
                left join sklbl_orders as ord
                on ord.id = sku.sklbl_order_id
                left join sklbl_files as file
                on file.id = sku.sklbl_file_id
                left join status
                on status.categorie = 'sklbl_file' and status.code = file.status
                where ord.id = ".$sklblOrder->getId()."
                and file.status = 0
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return sklblSku[] Returns an array of sklblSku objects
     */
    public function findStep4AGenerer($file): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.sklblFile = :sklblFile')
            ->andWhere('s.status = :status')
            ->setParameter('sklblFile', $file)
            ->setParameter('status', 2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return sklblSku[] Returns an array of sklblSku objects
     */
    public function findStep42ATransferer($file): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.sklblFile = :sklblFile')
            ->andWhere('s.status = :status')
            ->setParameter('sklblFile', $file)
            ->setParameter('status', 3)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @return sklblSku[] Returns an array of sklblSku objects
     */
    public function getSkuStep1List($order): array
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

    /**
     * @return sklblSku[] Returns an array of sklblSku objects
     */
    public function getSkuStatus1List($order): array
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

    public function updateSkuFileStatut($file,$status){
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.status', '?1')
            ->setParameter(1, $status)
            ->where('u.sklblFile = ?2')
            ->setParameter(2, $file)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

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
