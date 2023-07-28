<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblLisageConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblLisageConfig>
 *
 * @method SklblLisageConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblLisageConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblLisageConfig[]    findAll()
 * @method SklblLisageConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblLisageConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblLisageConfig::class);
    }

    public function save(SklblLisageConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblLisageConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return SklblLisageConfig[] Returns an array of SklblLisageConfig objects
     */
    public function getVariablesAtisser($order): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblOrder = :sklblOrder')
            ->andWhere('s.categorie = :categorie')
            ->setParameter('sklblOrder', $order)
            ->setParameter('categorie', 'variable')
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findConf($order,$structure,$variable): ?SklblLisageConfig
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblOrder = :sklblOrder')
            ->andWhere('s.sklblStructure = :name')
            ->andWhere('s.categorie = :categorie')
            ->setParameter('sklblOrder', $order)
            ->setParameter('name', $structure)
            ->setParameter('categorie', $variable)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return SklblLisageConfig[] Returns an array of SklblLisageConfig objects
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

//    public function findOneBySomeField($value): ?SklblLisageConfig
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
