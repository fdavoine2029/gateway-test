<?php

namespace App\Repository\Divalto;

use App\Entity\Divalto\Divalto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Divalto>
 *
 * @method Divalto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Divalto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Divalto[]    findAll()
 * @method Divalto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DivaltoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Divalto::class);
    }


    public function findArticle($ref): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select top(100) DOS,REF,DES,ABCCOD from ART where REF = '$ref'";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    // Récupération des articles fournisseurs
    public function getArticlesFournisseur($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        ART_ID,
        DOS,
        REF,
        DES,
        ABCCOD,
        GICOD,
        case when (ART.USERMODH is null or ART.USERCRDH > ART.USERMODH) then ART.USERCRDH else ART.USERMODH end as REF_IMPORT_DATE 
        from ART 
        where REF in 
        (
            select MOUV.REF 
            FROM MOUV JOIN MVTL
            ON MOUV.DOS = MVTL.DOS AND MOUV.ENRNO = MVTL.ENRNO
            where MOUV.TICOD = 'F'
            AND MVTL.DELDT >= GETDATE() - $days
            AND MVTL.REFQTE <> 0
        )
        and DOS = '$dossier'
        AND (ART.USERMODH > GETDATE() - $days or ART.USERCRDH > GETDATE() - $days)";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

   // Récupération des articles clients
    public function getArticlesClient($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        ART_ID,
        DOS,
        REF,
        DES,
        ABCCOD,
        GICOD,
        case when (ART.USERMODH is null or ART.USERCRDH > ART.USERMODH) then ART.USERCRDH else ART.USERMODH end as REF_IMPORT_DATE 
        from ART 
        where REF in 
        (
            select MOUV.REF 
            FROM MOUV JOIN MVTL
            ON MOUV.DOS = MVTL.DOS AND MOUV.ENRNO = MVTL.ENRNO
            where MOUV.TICOD = 'C'
            AND MVTL.DELDT >= GETDATE() - $days
            AND MVTL.REFQTE <> 0
        )
        and DOS = '$dossier'
        AND (ART.USERMODH > GETDATE() - $days or ART.USERCRDH > GETDATE() - $days)";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    // Récupération des clients
    public function getClients($dossier,$days): array
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



    // Récupération des fournisseurs
    public function getFournisseurs($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select 
        FOU_ID,
        DOS,
        TIERS,
        NOM,
        PAY,
        TRANSJRNB,
        case when (FOU.USERMODH is null or FOU.USERCRDH > FOU.USERMODH) then FOU.USERCRDH else FOU.USERMODH end as REF_IMPORT_DATE
        from FOU
        where TIERS in 
        (
            select MOUV.TIERS
            FROM MOUV JOIN MVTL
            ON MOUV.DOS = MVTL.DOS AND MOUV.ENRNO = MVTL.ENRNO
            where MOUV.TICOD = 'F'
            AND MVTL.DELDT >= GETDATE() - $days
            AND MVTL.REFQTE <> 0
        )
        and DOS = '$dossier'
        AND (FOU.USERMODH > GETDATE() - $days or FOU.USERCRDH > GETDATE() - $days)";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }



    public function getCommandesFournisseur($dossier,$days): array
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
}
