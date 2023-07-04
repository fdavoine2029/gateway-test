<?php

namespace App\Repository\Divalto;

use App\Entity\Divalto\CLI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CLI>
 *
 * @method CLI|null find($id, $lockMode = null, $lockVersion = null)
 * @method CLI|null findOneBy(array $criteria, array $orderBy = null)
 * @method CLI[]    findAll()
 * @method CLI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CLIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CLI::class);
    }

    public function save(CLI $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CLI $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getDivaltoClis($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        CLI_ID,
        DOS,
        TIERS,
        NOM,
        PAY,
        case when (CLI.USERMODH is null or CLI.USERCRDH > CLI.USERMODH) then CLI.USERCRDH else CLI.USERMODH end as REF_IMPORT_DATE
        from CLI
        where TIERS in 
        (
            select MOUV.TIERS
            FROM MOUV JOIN MVTL
            ON MOUV.DOS = MVTL.DOS AND MOUV.ENRNO = MVTL.ENRNO
            where MOUV.TICOD = 'C'
            AND MVTL.DELDT >= GETDATE() - $days
            AND MVTL.REFQTE <> 0
        )
        and DOS = '$dossier'
        AND (CLI.USERMODH > GETDATE() - $days or CLI.USERCRDH > GETDATE() - $days)";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    

//    /**
//     * @return CLI[] Returns an array of CLI objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CLI
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
