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

    

}
