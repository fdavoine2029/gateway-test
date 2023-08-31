<?php

namespace App\Repository\Divalto;

use App\Entity\Divalto\MOUV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MOUV>
 *
 * @method MOUV|null find($id, $lockMode = null, $lockVersion = null)
 * @method MOUV|null findOneBy(array $criteria, array $orderBy = null)
 * @method MOUV[]    findAll()
 * @method MOUV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MOUVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MOUV::class);
    }

    public function save(MOUV $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MOUV $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getDivaltoCmdsFou($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();



        $sql = "select 
                concat(MOUV.[MOUV_ID],MVTL.VTLNO) as MOUV_ID
                ,MOUV.[DOS]
                ,MOUV.[ETB]
                ,MOUV.[REF]
                ,MOUV.[SREF1]
                ,MOUV.[SREF2]
                ,MOUV.[TIERS]
                ,MOUV.[ENRNO]
                ,MOUV.[CDNO]
                ,MOUV.[CDLG]
                ,MOUV.[CDDT]
                ,MOUV.[CDENRNO]
                ,MOUV.[DES]
                ,ENT.SALCOD as SALCOD 
                ,cast(MOUV.CDQTE as int) as CDQTE
                ,cast(MVTL.REFQTE as int) as REFQTE
                ,MOUV.REFUN
                ,MOUV.MONT
                ,MOUV.DEV
                ,MVTL.DELDEMDT
                ,MVTL.DELACCDT
                ,MVTL.DELREPDT
                ,MVTL.DELDT
                ,FOU.PAY
                ,FOU.TRANSJRNB
                ,MVTL.VS_BLMOD
                ,MOUV.[BLNO]
                ,MOUV.[BLLG]
                ,MVTL.SERIE
                ,MVTL.VTLNO
                ,cast(MOUV.BLQTE as int) as BLQTE
                ,MVTL.VS_COMMENTAIRE
                ,MOUV.DEPO
                ,dateadd(
                day,
                case
                    when FOU.PAY IN ('CN', 'TW','PK','ES') and MVTL.VS_BLMOD in ('TNT3','WPX','PANA') then 7
                    when FOU.PAY IN ('CN') and MVTL.VS_BLMOD in ('SCHT','DIMT','LYST') then 31
                    when FOU.PAY IN ('CN') and MVTL.VS_BLMOD in ('SCHA') then 14
                            when FOU.PAY IN ('CN') and MVTL.VS_BLMOD in ('BOLA') then 10
                    --pertinence des 3 lignes suivantes, si on remplit FOU.TRANSJRNB ?
                    when FOU.PAY IN ('CN', 'TW')	    then 56
                    when FOU.PAY IN ('TR')            then 10
                    when FOU.PAY IN ('IT','FR') then 3
                    else FOU.TRANSJRNB end,
                MVTL.DELDT
                ) as DELAY_TRSP
                ,MOUV.USERMODH
                ,MOUV.USERCRDH
            FROM MOUV 
            JOIN MVTL ON MOUV.DOS = MVTL.DOS AND MOUV.ENRNO = MVTL.ENRNO
            JOIN ART ON ART.DOS = MOUV.DOS AND ART.REF = MOUV.REF
            JOIN FOU ON FOU.DOS = MOUV.DOS AND FOU.TIERS= MOUV.TIERS
            LEFT JOIN (SELECT DISTINCT BB.DOS,BB.REFCO,ART.ABCCOD FROM BB JOIN ART ON ART.DOS = BB.DOS AND ART.REF= BB.REF AND ART.ABCCOD = 'A' WHERE (BB.REFCO LIKE 'MIS%' or BB.REFCO LIKE 'SAG%') ) as MISS ON MISS.DOS = MOUV.DOS AND MISS.REFCO = MOUV.REF
            LEFT JOIN (select distinct DOS, PINO, TICOD,PICOD, SALCOD from ENT)  as ENT on MVTL.DOS = ENT.DOS AND ENT.TICOD = MOUV.TICOD AND ENT.PICOD  =MOUV.PICOD  AND ENT.PINO = MOUV.CDNO
            where MOUV.TICOD = 'F'
            and MOUV.DOS = '$dossier'
            AND MVTL.DELDT > GETDATE() - 365
            AND MVTL.REFQTE <> 0
            AND (MOUV.USERMODH > GETDATE() - $days or MOUV.USERCRDH > GETDATE() - $days)
            and cast(MVTL.DELDT as date) < '2200-01-01'
            order by MOUV.[CDNO] desc";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


//    /**
//     * @return MOUV[] Returns an array of MOUV objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MOUV
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
