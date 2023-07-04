<?php

namespace App\Repository\Divalto;

use App\Entity\Divalto\BF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BF>
 *
 * @method BF|null find($id, $lockMode = null, $lockVersion = null)
 * @method BF|null findOneBy(array $criteria, array $orderBy = null)
 * @method BF[]    findAll()
 * @method BF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BF::class);
    }

    public function save(BF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getDivaltoOfsSklbl($dossier,$days): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select
                BF_ID,
                BF.DOS,
                BF.PINO,
                BF.LIBOF,
                BF.REF,
                BF.SREF1,
                BF.SREF2,
                RCL.REFFO,
                BF.CDNO,
                BF.CDQTE,
                BF.QTELANCEE,
                BF.TIERS,
                BF.PREVDEBDH,
                BF.DEBDH,
                BF.FINDH,
                GTF_T011.CH_CONDCOEF,
                BH.STATUS,
                BF.USERMODH,
                BF.USERCRDH
                FROM BF
                LEFT OUTER JOIN ART ON ART.DOS = BF.DOS AND ART.REF = BF.REF
                LEFT OUTER JOIN BH ON BH.DOS = BF.DOS AND BH.PINO = BF.PINO --AND BH.CENTRE = 'PAP'
                LEFT OUTER JOIN RCL ON RCL.DOS = BF.DOS AND RCL.REF = BF.REF AND RCL.TIERS = BF.TIERS
                LEFT OUTER JOIN GTF_T011 ON BF.DOS = GTF_T011.DOS AND ART.CH_CONDIT = GTF_T011.CH_CONDIT
                WHERE BF.DOS = '$dossier'
                order by PINO DESC";
            
        $stmt = $conn->prepare($sql);
        //$resultSet = $stmt->executeQuery(['price' => $price]);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

}
