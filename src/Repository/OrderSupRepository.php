<?php

namespace App\Repository;

use App\Entity\OrderSup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderSup>
 *
 * @method OrderSup|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderSup|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderSup[]    findAll()
 * @method OrderSup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderSupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderSup::class);
    }

    public function save(OrderSup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OrderSup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function selectReceptions($hide,$delay): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        orders.id,
        orders.sync,
        orders.no_ventilation,
        orders.order_num,
        fou.code,
        fou.name,
        art.ref,
        orders.sref1,
        orders.sref2,
        art.designation,
        orders.delivery_note,
        orders.batch_num,
        truncate(orders.order_qte,0) as order_qte,
        truncate(orders.to_deliver_qte,0) as to_deliver_qte,
        truncate(orders.receiv_qte,0) as receiv_qte,
        orders.unit,
        orders.delay,
        orders.delay_trsp,
        orders.status,
        details.status as status2,
        fou.country,
        fou.trspdays,
        details.id as id_receiv,
        details.num_bl_fou,
        details.batch_num as batch_num2,
        details.comment,
        details.qte_recue,
        sta.name as status_lib,
        sta2.name as status2_lib
        from order_sup as orders
        left join fournisseurs as fou on fou.id = orders.fournisseurs_id
        left join articles as art on art.id = orders.articles_id
        left join receiv_sup_details as details on details.order_sup_id = orders.id
        left join status as sta on sta.code = orders.status and sta.categorie = 'reception'
        left join status as sta2 on sta2.code = details.status and sta2.categorie = 'qualite'
        where order_qte > 0
        and orders.status <= :status
        and orders.delay <= :delai
        order by orders.delay desc
        ";
            
        $stmt = $conn->prepare($sql);
        if($hide == 1){
            $resultSet = $stmt->executeQuery(['status' => 2,'delai' => $delay]);
        }else{
            $resultSet = $stmt->executeQuery(['status' => 3,'delai' => $delay]);
        }
        
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function findReceptions($id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        orders.id,
        orders.sync,
        orders.no_ventilation,
        orders.order_num,
        fou.code,
        fou.name,
        art.ref,
        orders.sref1,
        orders.sref2,
        art.designation,
        orders.delivery_note,
        orders.batch_num,
        truncate(orders.order_qte,0) as order_qte,
        truncate(orders.to_deliver_qte,0) as to_deliver_qte,
        truncate(orders.receiv_qte,0) as receiv_qte,
        orders.unit,
        orders.delay,
        orders.delay_trsp,
        orders.status,
        fou.country,
        fou.trspdays,
        details.num_bl_fou,
        details.batch_num as batch_num2,
        details.comment,
        details.qte_recue,
        sta.name as status,
        sta2.name as status2
        from order_sup as orders
        left join fournisseurs as fou on fou.id = orders.fournisseurs_id
        left join articles as art on art.id = orders.articles_id
        where orders.id = :id";
  
        
            
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery(['id' => $id]);
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    public function findOutOfSync(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select distinct 
        id
        from order_sup
        where sync = 0";
  
        
            
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery();
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
}
