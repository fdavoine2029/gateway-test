<?php

namespace App\Service\sklbl;

use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblFx;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\sklblSku;
use App\Repository\Sklbl\sklblSkuRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SklblStep41ServiceHandler  implements MessageHandlerInterface
{


    public function __construct(
        private EntityManagerInterface $entityManager,
        private sklblSkuRepository $sklblSkuRepository)
    {

    }

    public function __invoke(
        SklblStep41Service $sklblFileCustomerService
    ):void
    {
        $id_file = $sklblFileCustomerService->getIdFile();
        $fileRepository = $this->entityManager->getRepository(SklblFiles::class);
        $file = $fileRepository->find($id_file);
        $sklblOf = $file->getSklblOf();
        $sklblOrder = $file->getSklblOrder();
        $sklblSkuRepository = $this->entityManager->getRepository(sklblSku::class);
        $skus = $sklblSkuRepository->findNotGeneratedByFile($file);
        foreach($skus as $sku){
            $indice_sku = 0;
            while($indice_sku < $sku->getProduceQte())
            {
                $fx = new SklblFx();
                $fx->setSklblOrder($sklblOrder);
                $fx->setSklblOf($sklblOf);
                $fx->setSklblFile($file);
                $fx->setSklblSku($sku);
                $fx->setStatus(0);
                $currentDate = new DateTimeImmutable();
                $fx->setUpdatedAt($currentDate);
                $this->entityManager->persist($fx);
                $this->entityManager->flush($fx);
                $indice_sku++;
            }
            $sku->setStatus(2);
            $this->entityManager->persist($sku);
            $this->entityManager->flush($sku);
            
        }
        $file->setStatus(1);
        $this->entityManager->persist($file);
        $this->entityManager->flush($file);
    }
}
