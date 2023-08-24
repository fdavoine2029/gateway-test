<?php

namespace App\Repository\Sklbl;

use App\Entity\Sklbl\SklblFx2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SklblFx2>
 *
 * @method SklblFx2|null find($id, $lockMode = null, $lockVersion = null)
 * @method SklblFx2|null findOneBy(array $criteria, array $orderBy = null)
 * @method SklblFx2[]    findAll()
 * @method SklblFx2[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblFx2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SklblFx2::class);
    }

    public function save(SklblFx2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SklblFx2 $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFx2($value): ?SklblFx2
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.uniqueId = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function countFx2Loaded($file){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblFilename = :sklblFilename')
            ->andWhere('u.status = :status')
            ->setParameter('sklblFilename', $file)
            ->setParameter('status', 5)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFx2NotAssociated($of){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOf = :sklblOf')
            ->andWhere('u.status = :status')
            ->setParameter('sklblOf', $of)
            ->setParameter('status', 5)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFx2Associated($of){
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.id)')
            ->where('u.sklblOf = :sklblOf')
            ->andWhere('u.status = :status')
            ->setParameter('sklblOf', $of)
            ->setParameter('status', 6)
            ->getQuery()
            ->getSingleScalarResult();
    }





    /**
     * @return SklblFx2[] Returns an array of SklblFx2 objects
     */
    public function findFx2sNotAssociated($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sklblFile = :sklblFile')
            ->andWhere('s.SklblFx is null')
            ->setParameter('sklblFile', $value)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?SklblFx2
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
