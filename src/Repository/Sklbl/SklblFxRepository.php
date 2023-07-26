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

    public function countFxAttenteTransfert($order){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOrder = :orderId')
            ->andWhere('u.status = :status')
            ->setParameter('orderId', $order)
            ->setParameter('status', 3)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFxLoaded($file){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblFile = :sklblFile')
            ->andWhere('u.status = :status')
            ->setParameter('sklblFile', $file)
            ->setParameter('status', 4)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function updateFxStatut($sku,$status){
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.status', '?1')
            ->setParameter(1, $status)
            ->where('u.sklblSku = ?2')
            ->setParameter(2, $sku)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function updateFxFileStatut($file,$status){
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

    public function updateFxFileSentOn($file,$senton){
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.sent_on', '?1')
            ->setParameter(1, $senton)
            ->where('u.sklblFile = ?2')
            ->andWhere('u.status = ?3')
            ->setParameter(2, $file)
            ->setParameter(3, 5)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    

    public function getFxInTraitement(SklblOrders $sklblOrder){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        
                sku.*,
                fx.*,
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
   /* public function findStep42ATransferer($sku): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.sklblSku = :sklblSku')
            ->andWhere('s.status = :status')
            ->setParameter('sklblSku', $sku)
            ->setParameter('status', 1)
            ->getQuery()
            ->getResult()
        ;
    }*/


    public function findStep42ATransferer($file): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT 
                trim(ofs.code) AS code,
                trim(art.ref) AS ref,
                trim(ofs.ref_cli) AS ref_cli,
                trim(art.designation) AS designation,
                emb1.qte as qte1,
                emb2.qte as qte2,
                emb3.qte as qte3,
                emb4.qte as qte4,
                ofs.order_qte,
                ofs.launched_qte,
                trim(masque.valeur) AS masque,
                trim(emb1.libelle) AS lib1,
                trim(emb2.libelle) AS lib2,
                trim(emb3.libelle) AS lib3,
                trim(emb4.libelle) AS lib4,
                trim(ofs.sref1) AS sref1,
                trim(ofs.sref2) AS sref2,
                trim(fichier1.valeur) AS fichier1,
                trim(fichier2.valeur) AS fichier2,
                mini.valeur AS mini,
                trim(cli.code) AS code_client,
                trim(sku.sku) AS sku,
                trim(sku.sku_tisse) AS sku_tisse,
                trim(sku.opt_data1) AS opt_data1,
                trim(sku.opt_data2) AS opt_data2,
                trim(sku.opt_data3) AS opt_data3,
                trim(sku.opt_data4) AS opt_data4,
                trim(sku.opt_data5) AS opt_data5,
                trim(sku.opt_data6) AS opt_data6,
                trim(sku.opt_data7) AS opt_data7,
                trim(sku.opt_data8) AS opt_data8,
                trim(sku.opt_data9) AS opt_data9,
                trim(sku.opt_data10) AS opt_data10
                FROM sklbl_fx AS fx
                LEFT JOIN sklbl_of AS ofs ON ofs.id = fx.sklbl_of_id
                LEFT JOIN sklbl_orders AS orders ON orders.id = ofs.sklbl_order_id
                LEFT JOIN clients AS cli ON cli.id = ofs.client_id
                LEFT JOIN sklbl_sku AS sku ON sku.id = fx.sklbl_sku_id
                LEFT JOIN articles AS art ON art.id = ofs.article_id
                LEFT JOIN sklbl_emballage AS emb1 ON emb1.id = ofs.emballage1_id
                LEFT JOIN sklbl_emballage AS emb2 ON emb1.id = ofs.emballage2_id
                LEFT JOIN sklbl_emballage AS emb3 ON emb1.id = ofs.emballage3_id
                LEFT JOIN sklbl_emballage AS emb4 ON emb1.id = ofs.emballage4_id
                LEFT JOIN sklbl_rubrique AS masque ON masque.id = ofs.masque_id
                LEFT JOIN sklbl_rubrique AS fichier1 ON fichier1.id = ofs.fichier1_id
                LEFT JOIN sklbl_rubrique AS fichier2 ON fichier2.id = ofs.fichier2_id
                LEFT JOIN sklbl_rubrique AS mini ON mini.id = ofs.mini_complet_id
            WHERE fx.status = 1
            AND fx.sklbl_file_id = '".$file->getId()."'
        ";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function findFreeFx($fx2): ?SklblFx
    {
        return $this->createQueryBuilder('s')
            ->where('s.status = :status')
            ->andWhere('s.uniqueId is null')
            ->andWhere('s.sklblSku = :sku')
            ->setParameter('sku', $fx2->getSku())
            ->setParameter('status', 5)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    public function countFxNotAssociated($of){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOf = :sklblOf')
            ->andWhere('u.status = :status')
            ->setParameter('sklblOf', $of)
            ->setParameter('status', 5)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFxAssociated($of){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOf = :sklblOf')
            ->andWhere('u.status = :status')
            ->setParameter('sklblOf', $of)
            ->setParameter('status', 6)
            ->getQuery()
            ->getSingleScalarResult();
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
