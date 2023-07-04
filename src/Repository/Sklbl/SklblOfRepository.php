<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblOf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblOf>
 *
 * @method SklblOf|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblOf|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblOf[]    findAll()
 * @method SklblOf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblOfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblOf::class);
    }

    public function save(SklblOf $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblOf $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSklblOfs(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT DISTINCT  *
        FROM
        (
        select 
                sklbl_orders.id as order_id,
                sklbl_orders.order_num,
                cast(sklbl_orders.order_at as date) as order_at,
                sklbl_of.id,
                sklbl_of.code as code_of,
                rub_option.valeur as opt,
                articles.ref,
                articles.designation,
                articles_order.ref AS ref_order,
                articles_order.designation AS designation_order,
                clients.code as code_client,
                clients.name,
                clients_order.code as code_client_order,
                clients_order.name AS name_order,
                sklbl_of.of_status,
                sklbl_orders.sklbl_status as sklbl_order_status,
                status_sklbl_order.name as sklbl_order_status_lib,
                sklbl_of.sklbl_status,
                cast(sklbl_of.planned_at as date) as planned_at,
                sklbl_of.start_at,
                sklbl_of.end_at,
                sklbl_orders.order_qte AS qte_order,
                sklbl_of.order_qte,
                sklbl_of.launched_qte
                from sklbl_orders
                left join sklbl_of on sklbl_of.sklbl_order_id = sklbl_orders.id
                left join sklbl_rubrique as rub_option on rub_option.id = sklbl_of.options_id
                left JOIN articles ON articles.id = sklbl_of.article_id
                LEFT JOIN clients ON clients.id = sklbl_of.client_id
                left JOIN articles AS articles_order ON articles_order.id = sklbl_orders.article_id
                LEFT JOIN clients AS clients_order ON clients_order.id = sklbl_orders.client_id
                LEFT JOIN status as status_sklbl_order ON status_sklbl_order.categorie = 'sklbl' and status_sklbl_order.code = sklbl_orders.sklbl_status
                WHERE (sklbl_of.id is not null or sklbl_orders.status < 4)
                /*UNION ALL
        select 
                sklbl_orders.id as order_id,
                sklbl_orders.order_num,
                sklbl_orders.order_at,
                sklbl_of.id,
                sklbl_of.code as code_of,
                rub_option.valeur as opt,
                articles.ref,
                articles.designation,
                articles_order.ref AS ref_order,
                articles_order.designation AS designation_order,
                clients.code as code_client,
                clients.name,
                clients_order.code as code_client_order,
                clients_order.name AS name_order,
                sklbl_of.of_status,
                sklbl_of.sklbl_status,
                sklbl_of.planned_at,
                sklbl_of.start_at,
                sklbl_of.end_at,
                sklbl_orders.order_qte AS qte_order,
                sklbl_of.order_qte,
                sklbl_of.launched_qte
                from sklbl_orders
                right join sklbl_of on sklbl_of.dossier = sklbl_orders.dossier and sklbl_of.order_num = sklbl_orders.order_num and sklbl_of.article_id = sklbl_orders.article_id
                left join sklbl_rubrique as rub_option on rub_option.id = sklbl_of.options_id
                left JOIN articles ON articles.id = sklbl_of.article_id
                LEFT JOIN clients ON clients.id = sklbl_of.client_id
                left JOIN articles AS articles_order ON articles_order.id = sklbl_orders.article_id
                LEFT JOIN clients AS clients_order ON clients_order.id = sklbl_orders.client_id*/
                ) AS req";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
 
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


//    /**
//     * @return SklblOf[] Returns an array of SklblOf objects
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

//    public function findOneBySomeField($value): ?SklblOf
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
