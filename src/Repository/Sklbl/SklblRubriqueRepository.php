<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblRubrique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblRubrique>
 *
 * @method SklblRubrique|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblRubrique|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblRubrique[]    findAll()
 * @method SklblRubrique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblRubriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblRubrique::class);
    }

    public function save(SklblRubrique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblRubrique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SklblRubrique[] Returns an array of SklblRubrique objects
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

//    public function findOneBySomeField($value): ?SklblRubrique
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
