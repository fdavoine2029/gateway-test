<?php

namespace App\Repository\Divalto;

use App\Entity\Divalto\Sklbl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sklbl>
 *
 * @method Sklbl|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sklbl|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sklbl[]    findAll()
 * @method Sklbl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SklblRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sklbl::class);
    }

    public function save(Sklbl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sklbl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getDivaltoEmballages($dossier): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT 
                T033.T033_ID,
                T033.DOS,
                T033.REF,
                T033.EMBQTE,
                T033.VENUN,
                T033.EMBUN,
                LEFT(T032.LIB,CHARINDEX(' ',T032.LIB)-1) AS LIB , 
                ROW_NUMBER() OVER(PARTITION BY T033.REF ORDER BY T033.EMBQTE) AS ORDRE
                FROM T033 JOIN T032 ON T032.DOS =T033.DOS AND T032.EMBUN = T033.EMBUN
                WHERE T033.DOS = '$dossier'";
            
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    public function getDivaltoRubriques($dossier): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT distinct 
                    [MRBQVAL_ID]
                    ,[DOS]
                    ,[ENTITEINDEX]
                    ,[RUBRIQUE]
                    ,[RBQVAL]
                    ,USERCRDH
                    ,USERMODH
                FROM [MRBQVAL]
                WHERE [DOS] = '$dossier'
                and RUBRIQUE like 'FIL%'";
            
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    public function getDivaltoSklblOrders($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT distinct 
                MOUV.[DOS]
                    ,MOUV.CDNO
                    ,[TIERS]
                    ,MOUV.[REF]
                    ,[SREF1]
                    ,[SREF2]
                    ,[TICOD]
                    ,[PICOD]
                    ,cast(sum(CDQTE) as int) as CDQTE
                    ,CDDT
                    ,max_created_at.USERCRDH
                    ,max_updated_at.USERMODH
                FROM [MOUV]
                left join (select DOS, CDNO,REF ,max(USERCRDH) as USERCRDH from [MOUV] group by DOS, CDNO,REF) as max_created_at
                on max_created_at.DOS = MOUV.DOS and max_created_at.CDNO = MOUV.CDNO and max_created_at.REF = MOUV.REF 
                left join (select DOS, CDNO,REF ,max(USERMODH) as USERMODH from [MOUV] group by DOS, CDNO,REF) as max_updated_at
                on max_updated_at.DOS = MOUV.DOS and max_updated_at.CDNO = MOUV.CDNO and max_updated_at.REF = MOUV.REF 
                where TICOD = 'C'
                and MOUV.REF in (SELECT distinct ENTITEINDEX FROM [MRBQVAL] where MRBQVAL.RUBRIQUE = 'FIL_NOM_FICHIER_1')
                and CDDT is not null
                group by MOUV.[DOS]
                    ,MOUV.CDNO
                    ,[TIERS]
                    ,MOUV.[REF]
                    ,[SREF1]
                    ,[SREF2]
                    ,[TICOD]
                    ,[PICOD]
                    ,OFNO
                    ,CDDT
                    ,max_created_at.USERCRDH
                    ,max_updated_at.USERMODH
                order by CDNO DESC";
            
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }



    public function getDivaltoSklblOfs($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "WITH EMBALLAGE AS ( --Requête OFSEGEPAR
        SELECT 
        T033_ID,
        T033.DOS,
        T033.REF,
        T033.EMBQTE,
        T033.VENUN,
        T033.EMBUN,
        LEFT(T032.LIB,CHARINDEX(' ',T032.LIB)-1) AS LIB , 
        ROW_NUMBER() OVER(PARTITION BY T033.REF ORDER BY T033.EMBQTE) AS ORDRE
        FROM T033 JOIN T032 ON T032.DOS =T033.DOS AND T032.EMBUN = T033.EMBUN
        WHERE T033.DOS = CAST(1 AS VARCHAR(8))
        )
        
        SELECT distinct 
        BF.BF_ID
        ,BF.DOS
        ,BF.PINO
        ,RTRIM(BF.REF) AS REF
        ,case when RTRIM(RCL.REFFO) is null THEN '' ELSE RTRIM(RCL.REFFO) end AS REF_CLI
        ,RTRIM(ART.DES) AS DES
        ,EMBALLAGE1.T033_ID as ID_EMBALLAGE1
        ,EMBALLAGE2.T033_ID as ID_EMBALLAGE2
        ,EMBALLAGE3.T033_ID as ID_EMBALLAGE3
        ,EMBALLAGE4.T033_ID as ID_EMBALLAGE4
        ,BF.TIERS
        ,BF.CDNO
        ,BF.USERCRDH
        ,BF.USERMODH
        ,BF.CDQTE AS QTE_CDE
        ,BF.QTELANCEE AS QTE_OF 
        ,BF.STATUS
        ,BF.PREVDEBDH
        ,BF.DEBDH
        ,BF.FINDH
        ,RTRIM(BF.SREF1) AS SREF1
        ,RTRIM(BF.SREF2) AS SREF2
        ,MRBQVAL.MRBQVAL_ID AS ID_RUBRIQUE1 --NOM_FICHIER_1
        ,MRBQVAL2.MRBQVAL_ID AS ID_RUBRIQUE2 --NOM_FICHIER_2
        ,MRBQVAL3.MRBQVAL_ID AS ID_RUBRIQUE3 --NIVEAU_MINI_COMPLET
        ,MRBQVAL4.MRBQVAL_ID AS ID_RUBRIQUE4 --MASQUE
        ,MRBQVAL5.MRBQVAL_ID AS ID_RUBRIQUE5 --FICHIER_REMONTEE
        ,MRBQVAL6.MRBQVAL_ID AS ID_RUBRIQUE6 --'OPTION'
        FROM BF
        LEFT OUTER JOIN ART ON ART.DOS = BF.DOS AND ART.REF = BF.REF
        LEFT OUTER JOIN BH ON BH.DOS = BF.DOS AND BH.PINO = BF.PINO --AND BH.CENTRE = 'PAP'
        LEFT OUTER JOIN RCL ON RCL.DOS = BF.DOS AND RCL.REF = BF.REF AND RCL.TIERS = BF.TIERS
        LEFT OUTER JOIN GTF_T011 ON BF.DOS = GTF_T011.DOS AND ART.CH_CONDIT = GTF_T011.CH_CONDIT
        LEFT OUTER JOIN EMBALLAGE AS EMBALLAGE1 ON EMBALLAGE1.DOS = BF.DOS AND EMBALLAGE1.REF = BF.REF AND EMBALLAGE1.ORDRE = 1
        LEFT OUTER JOIN EMBALLAGE AS EMBALLAGE2 ON EMBALLAGE2.DOS = BF.DOS AND EMBALLAGE2.REF = BF.REF AND EMBALLAGE2.ORDRE = 2
        LEFT OUTER JOIN EMBALLAGE AS EMBALLAGE3 ON EMBALLAGE3.DOS = BF.DOS AND EMBALLAGE3.REF = BF.REF AND EMBALLAGE3.ORDRE = 3
        LEFT OUTER JOIN EMBALLAGE AS EMBALLAGE4 ON EMBALLAGE4.DOS = BF.DOS AND EMBALLAGE4.REF = BF.REF AND EMBALLAGE4.ORDRE = 4
        LEFT OUTER JOIN MRBQVAL ON MRBQVAL.DOS  = BF.DOS AND RTRIM(LTRIM(MRBQVAL.ENTITEINDEX)) = BF.REF AND MRBQVAL.RUBRIQUE = 'FIL_NOM_FICHIER_1'
        LEFT OUTER JOIN MRBQVAL  AS MRBQVAL2  ON MRBQVAL2.DOS  = BF.DOS AND RTRIM(LTRIM(MRBQVAL2.ENTITEINDEX)) = BF.REF AND MRBQVAL2.RUBRIQUE = 'FIL_NOM_FICHIER_2'
        LEFT OUTER JOIN MRBQVAL  AS MRBQVAL3  ON MRBQVAL3.DOS  = BF.DOS AND RTRIM(LTRIM(MRBQVAL3.ENTITEINDEX)) = BF.REF AND MRBQVAL3.RUBRIQUE = 'FIL_NIVEAU_MINI_COMPLET'
        LEFT OUTER JOIN MRBQVAL  AS MRBQVAL4  ON MRBQVAL4.DOS  = BF.DOS AND RTRIM(LTRIM(MRBQVAL4.ENTITEINDEX)) = BF.REF AND MRBQVAL4.RUBRIQUE = 'FIL_MASQUE'
        LEFT OUTER JOIN MRBQVAL  AS MRBQVAL5  ON MRBQVAL5.DOS  = BF.DOS AND RTRIM(LTRIM(MRBQVAL5.ENTITEINDEX)) = BF.REF AND MRBQVAL5.RUBRIQUE = 'FIL_FICHIER_REMONTEE'
        LEFT OUTER JOIN MRBQVAL  AS MRBQVAL6  ON MRBQVAL6.DOS  = BF.DOS AND RTRIM(LTRIM(MRBQVAL6.ENTITEINDEX)) = BF.REF AND MRBQVAL6.RUBRIQUE = 'FIL_OPTION'
        
        WHERE BF.DOS = '$dossier' 
        and MRBQVAL.RBQVAL is not null 
        AND GTF_T011.CH_CONDCOEF > 0 
        AND (BF.USERMODH > GETDATE() - $days or BF.USERCRDH > GETDATE() - $days)
        ORDER BY BF.PINO";
            
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return Sklbl[] Returns an array of Sklbl objects
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

//    public function findOneBySomeField($value): ?Sklbl
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
