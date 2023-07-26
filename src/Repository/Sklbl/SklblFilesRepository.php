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



    public function getStep4Files($order){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT files.id,
        files.client_filename,
        files.categorie,
        files.status,
        total_sku.nb AS total_sku,
        case when genere_sku.nb IS NULL then 0 ELSE genere_sku.nb end AS genere_sku,
        case when pret_transfert_sku.nb IS NULL then 0 ELSE pret_transfert_sku.nb end AS pret_au_transfert_sku,
        case when transfere_sku.nb IS NULL then 0 ELSE transfere_sku.nb end AS transfere_sku,
        case when rapproche_fx.nb IS NULL then 0 ELSE rapproche_fx.nb end AS rapproche_fx,
        case when receptionne_fx2.nb IS NULL then 0 ELSE receptionne_fx2.nb end AS receptionne_fx2,
        case when rapproche_fx2.nb IS NULL then 0 ELSE rapproche_fx2.nb end AS rapproche_fx2
        FROM sklbl_files AS files
        LEFT JOIN (SELECT sklbl_file_id, sum(produce_qte) AS nb FROM sklbl_sku GROUP BY sklbl_file_id ) AS total_sku
        ON total_sku.sklbl_file_id = files.id 
        LEFT JOIN (SELECT sklbl_file_id, sum(produce_qte) AS nb FROM sklbl_sku where status in (3,4,5,6) GROUP BY sklbl_file_id ) AS genere_sku
        ON genere_sku.sklbl_file_id = files.id 
        LEFT JOIN (SELECT sklbl_file_id, sum(produce_qte) AS nb FROM sklbl_sku where status = 4 GROUP BY sklbl_file_id ) AS pret_transfert_sku
        ON pret_transfert_sku.sklbl_file_id = files.id 
        LEFT JOIN (SELECT sklbl_file_id, sum(produce_qte) AS nb FROM sklbl_sku where status = 5 GROUP BY sklbl_file_id ) AS transfere_sku
        ON transfere_sku.sklbl_file_id = files.id 
        LEFT JOIN (SELECT sklbl_file_id,COUNT(id) AS nb FROM sklbl_fx where status IN (6) GROUP BY sklbl_file_id ) AS rapproche_fx
        ON rapproche_fx.sklbl_file_id = files.id
        LEFT JOIN (SELECT sklbl_file_id,COUNT(id) AS nb FROM sklbl_fx2 where status IN (5,6) GROUP BY sklbl_file_id ) AS receptionne_fx2
        ON receptionne_fx2.sklbl_file_id = files.id
        LEFT JOIN (SELECT sklbl_file_id,COUNT(id) AS nb FROM sklbl_fx2 where status IN (6) GROUP BY sklbl_file_id ) AS rapproche_fx2
        ON rapproche_fx2.sklbl_file_id = files.id
        where files.sklbl_order_id = ". $order->getId()."";


        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    

    /**
     * @return SklblFiles[] Returns an array of SklblFiles objects
     */
    public function getStep4FilesEnAttente($order): array
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
     * @return SklblFiles[] Returns an array of SklblFiles objects
     */
    public function getStep4FilesATransferer($order): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblOrder = :sklblOrder')
            ->andWhere('s.status = :status')
            ->setParameter('sklblOrder', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getResult()
        ;
    }


    public function getCustomerOrderFileList($order): array
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.sklblOrder = :sklblOrder')
        ->andWhere('s.status <= :status')
            ->setParameter('sklblOrder', $order)
            ->setParameter('status', 1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFileAIntegrerList($order): array
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.sklblOrder = :sklblOrder')
        ->andWhere('s.status = :status')
            ->setParameter('sklblOrder', $order)
            ->setParameter('status', 0)
            ->getQuery()
            ->getResult()
        ;
    }

    

    public function getStep4FileAGenererList($order): array
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.sklblOrder = :sklblOrder')
        ->andWhere('s.status = :status')
            ->setParameter('sklblOrder', $order)
            ->setParameter('status', 2)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getStep42FilesList($order): array
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.sklblOrder = :sklblOrder')
        ->andWhere('s.status >= :status1')
        ->andWhere('s.status <= :status2')
            ->setParameter('sklblOrder', $order)
            ->setParameter('status1', 4)
            ->setParameter('status2', 5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getStep43FilesList($order): array
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.sklblOrder = :sklblOrder')
        ->andWhere('s.status >= :status1')
        ->andWhere('s.status <= :status2')
            ->setParameter('sklblOrder', $order)
            ->setParameter('status1', 6)
            ->setParameter('status2', 7)
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

    public function countStep1FilesError($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', -1)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countStep1FilesSuccess($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 1)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countStep1FilesEnAttente($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 0)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFilesTraite($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function countFilesNonTraite($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status < :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFileAttenteTransfert($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findFx2File($of,$filename): ?SklblFiles
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.categorie = :categorie')
            ->andWhere('s.sklblOf = :of')
            ->andWhere('s.clientFilename = :file')
            ->setParameter('of', $of)
            ->setParameter('file', $filename)
            ->setParameter('categorie', 'fx2')
            ->getQuery()
            ->getOneOrNullResult()
        ;
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
