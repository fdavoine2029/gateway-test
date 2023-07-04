<?php

namespace App\Repository\Divalto;

use App\Entity\Divalto\ART;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ART>
 *
 * @method ART|null find($id, $lockMode = null, $lockVersion = null)
 * @method ART|null findOneBy(array $criteria, array $orderBy = null)
 * @method ART[]    findAll()
 * @method ART[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ARTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ART::class);
    }

    public function save(ART $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ART $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDivaltoArts($ref): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select top(100) DOS,REF,DES,ABCCOD from ART where REF = '$ref'";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function getDivaltoFArts($dossier,$days): array
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


    public function getDivaltoCArts($dossier,$days): array
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
}
