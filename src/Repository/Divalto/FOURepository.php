<?php

namespace App\Repository\Divalto;

use App\Entity\Divalto\FOU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FOU>
 *
 * @method FOU|null find($id, $lockMode = null, $lockVersion = null)
 * @method FOU|null findOneBy(array $criteria, array $orderBy = null)
 * @method FOU[]    findAll()
 * @method FOU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FOURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FOU::class);
    }

    public function save(FOU $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FOU $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getDivaltoFous($dossier,$days): array
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

}
