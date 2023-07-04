<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblEmballage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblEmballage>
 *
 * @method SklblEmballage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblEmballage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblEmballage[]    findAll()
 * @method SklblEmballage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblEmballageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblEmballage::class);
    }

    public function save(SklblEmballage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblEmballage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SklblEmballage[] Returns an array of SklblEmballage objects
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

//    public function findOneBySomeField($value): ?SklblEmballage
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
