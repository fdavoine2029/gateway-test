<?php

namespace App\Service\sklbl;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\sklblSku;
use App\Repository\Sklbl\SklblFilesRepository;
use App\Repository\Sklbl\SklblOrdersRepository;
use App\Repository\Sklbl\sklblSkuRepository;
use App\Service\sklbl\SklblExcelService;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class SklblStep1ServiceHandler   implements MessageHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SklblOrdersRepository $sklblOrdersRepository,
        private sklblSkuRepository $sklblSkuRepository,
        private SklblFilesRepository $sklblFilesRepository,
        private SluggerInterface $slugger) {}
        
    public function __invoke(
        SklblStep1Service $sklblStep1Service
    ):void
    {
        $orderId = $sklblStep1Service->getOrderId();
        $newFilename = $sklblStep1Service->getFileName();
        $vendorColumn = $sklblStep1Service->getVendorColumn();
        $path = $sklblStep1Service->getPath();
        $idColumn = $sklblStep1Service->getIdColumn();
        $skuColumn = $sklblStep1Service->getSkuColumn();
        $skuTisseColumn = $sklblStep1Service->getSkuTisseColumn();
        $qteColumn = $sklblStep1Service->getQteColumn();
        $deleteSku = $sklblStep1Service->getDeleteSku();
        $ligne = $sklblStep1Service->getLigne();

        $sklblOrder = new SklblOrders();
        $sklblOrdersRepository = $this->entityManager->getRepository(SklblOrders::class);
        $sklblSkuRepository = $this->entityManager->getRepository(sklblSku::class);
        $sklblFilesRepository = $this->entityManager->getRepository(SklblFiles::class);

        $sklblOrder = $sklblOrdersRepository->find($orderId);
        $skulistNonTransfere = $sklblSkuRepository->getSkuNonTransfereList($sklblOrder);
        $sklblFilesNonTransfere = $sklblFilesRepository->getFileNonTransfereList($sklblOrder);
        

        if($deleteSku == 1){
            foreach($skulistNonTransfere as $sku){
                $skuDelete = $sklblSkuRepository->find($sku['id']);
                $this->entityManager->remove($skuDelete);
                $this->entityManager->flush();
            }
            foreach($sklblFilesNonTransfere as $file){
                $this->entityManager->remove($file);
                $this->entityManager->flush();
            }
        }


        $sklblFiles = new SklblFiles();
        $sklblFiles->setSklblOrder($sklblOrder);
        $sklblFiles->setClientFilename($newFilename);
        $sklblFiles->setCategorie('Customer file');
        $sklblFiles->setVendorColumn($vendorColumn);
        $sklblFiles->setSkuColumn($skuColumn);
        $sklblFiles->setQteColumn($qteColumn);
        $sklblFiles->setStatus(0);
        $this->entityManager->persist($sklblFiles);
        $this->entityManager->flush();


        $excelService = new SklblExcelService();
        
        $records = $excelService->integrateCustomerFile($sklblOrder,$path,$vendorColumn,$skuColumn,$qteColumn);
        
        
        // On vérifie la présence d'enregistrement
        $errorEmptyRecord = false;
        if(sizeof($records) == 0){
            $errorEmptyRecord = true;
        }
        $sku_index = 1;

        // On parcourt chaque ligne
        $countUploadSuccess = 0;
        $countUploadErrors = 0;
        if(!$errorEmptyRecord){
            foreach($records as $row){
                if($sku_index >= $ligne){
                    $column_index = 0;
                    $sku = new sklblSku();
                    // On parcourt chaque colonne
                    $nbcolonneErrors = 0;
                    while($column_index < sizeof($row)){
                        
                        // On identifie la lettre de la colonne
                        $column = $excelService->num2alpha($column_index);
                        if($column == $idColumn){
                            $sku->setId($row[$column_index]);
                        }
                        if($column == $skuColumn){
                            $sku->setSku($row[$column_index]);
                        }
                        if($column == $skuTisseColumn){
                            $sku->setSkuTisse($row[$column_index]);
                        }
                        if($column == $vendorColumn){
                            $sku->setVendor($row[$column_index]);
                        }
                        if($column == $qteColumn){
                            try {
                                $sku->setOrderQte(intval($row[$column_index]));
                            } catch (Exception $e) {
                                $nbcolonneErrors++;
                            }
                        }
                        $column_index++;
                    }
                    
                    $sku->setSklblOrder($sklblOrder);
                    $sku->setSklblFile($sklblFiles);
                    if($nbcolonneErrors == 0){
                        try {
                            $this->entityManager->persist($sku);
                            $this->entityManager->flush();
                            $countUploadSuccess++;
                        } catch (Exception $e) {
                            $countUploadErrors++;
                        }
                        
                    }else{
                        $countUploadErrors++;
                    }

                }
                $sku_index++;
            }
        }
        if($errorEmptyRecord){
            //$this->addFlash('danger','Aucune enregistrement détecté, vérifier le fichier.');
        }else{
            if($countUploadErrors == 0){
                //  $this->addFlash('success',$countUploadSuccess. ' enregistrements chargés avec succès');
                
                $sklblFiles->setStatus(0);
                $this->entityManager->persist($sklblFiles);
                $this->entityManager->flush();

                $sklblOrder->setSklblStatus(1);
                $this->entityManager->persist($sklblOrder);
                $this->entityManager->flush();
            }else{
                //  $this->addFlash('danger','Erreur chargement fichier, veuillez vérifier les champs: ' . $countUploadErrors .' erreurs constatées.');
            }
        }


    }

    

}
