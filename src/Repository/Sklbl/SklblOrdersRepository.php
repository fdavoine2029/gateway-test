<?php

namespace App\Repository\Sklbl;

use App\Entity\Articles;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Proxies\__CG__\App\Entity\Sklbl\OfsSklbl;

/**
 * @extends ServiceEntityRepository<SklblOrders>
 *
 * @method SklblOrders|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblOrders|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblOrders[]    findAll()
 * @method SklblOrders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblOrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblOrders::class);
    }

    public function save(SklblOrders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblOrders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOrder(Array $order,Articles $article): array|null
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.dossier = :dossier')
            ->andWhere('s.order_num = :order_num')
            ->andWhere('s.article = :article_id')
            ->andWhere('s.order_at = :order_at')
            ->setParameter('dossier', $order['DOS'])
            ->setParameter('order_num', $order['CDNO'])
            ->setParameter('article_id', $article)
            ->setParameter('order_at', $order['CDDT'])
            ->getQuery()
            ->getResult()
        ;
    }

    public function identifyOrder(SklblOf $ofSqlbl): array|null
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.dossier = :dossier')
            ->andWhere('s.order_num = :order_num')
            ->andWhere('s.article = :article_id')
            ->setParameter('dossier', $ofSqlbl->getDossier())
            ->setParameter('order_num', $ofSqlbl->getOrderNum())
            ->setParameter('article_id', $ofSqlbl->getArticle())
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return SklblOrders[] Returns an array of SklblOrders objects
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

//    public function findOneBySomeField($value): ?SklblOrders
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
