<?php

namespace App\Controller\Sklbl;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sklbl\SklblEmballage;
use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblFx;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\SklblRubrique;
use App\Entity\Sklbl\sklblSku;
use App\Form\Sklbl\SklblFilesFormType;
use App\Form\Sklbl\SklblMajorationFormType;
use App\Message\SendNotification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticlesRepository;
use App\Repository\ClientsRepository;
use App\Repository\Divalto\SklblRepository;
use App\Repository\Sklbl\SklblEmballageRepository;
use App\Repository\Sklbl\SklblFilesRepository;
use App\Repository\Sklbl\SklblFxRepository;
use App\Repository\Sklbl\SklblOfRepository;
use App\Repository\Sklbl\SklblOrdersRepository;
use App\Repository\Sklbl\SklblRubriqueRepository;
use App\Repository\Sklbl\sklblSkuRepository;
use App\Service\sklbl\SklblExcelService;
use App\Service\sklbl\SklblFileCustomerService;
use App\Service\sklbl\SklblStep1Service;
use App\Service\sklbl\SklblStep41Service;
use App\Service\sklbl\SklblStep42Service;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[Route('/sklbl', name: 'sklbl_')]
class ScalabelController extends AbstractController
{
    #[Route('/showOfs/{hide?1}', name: 'index')]
    public function index(SklblOfRepository $sklblOfRepository, int $hide): Response
    {
        $ofs = $sklblOfRepository->getSklblOfs();
        return $this->render('sklbl/scalabel/index.html.twig', [
            'controller_name' => 'ScalabelController',
            'ofs' => $ofs,
            'hide' => $hide
        ]);
    }

    
    


    #[Route('/step_1/{order_id}', name: 'step_1')]
    public function step1(
        Request $request,
        SklblFilesRepository $sklblFilesRepository,
        SklblOrdersRepository $sklblOrdersRepository,
        SklblSkuRepository $sklblSkuRepository,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
        int $order_id): Response
    {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $skulist =$sklblSkuRepository->getSkuList($sklblOrder);
        $skulistNonTransfere = $sklblSkuRepository->getSkuNonTransfereList($sklblOrder);
        $sklblFilesNonTransfere = $sklblFilesRepository->getFileNonTransfereList($sklblOrder);
        $sklblFiles = new SklblFiles();
        $sklblFiles_param = $sklblFilesRepository->findOneBySklblOrder($sklblOrder);
        
        if($sklblFiles_param){
            
            $columnId = $sklblFiles_param->getIdColumn();
            $columnVendor = $sklblFiles_param->getVendorColumn();
            $columnSku = $sklblFiles_param->getSkuColumn();
            $columnSkuTisse = $sklblFiles_param->getSkuTisseColumn();
            $columnQte = $sklblFiles_param->getQteColumn();
            $columnLigne = $sklblFiles_param->getLigne();
        }else{
            $columnId = "";
            $columnVendor = "";
            $columnSku = "";
            $columnSkuTisse = "";
            $columnQte = "";
            $columnLigne = "";
        }
        $form = $this->createForm(SklblFilesFormType::class, $sklblFiles);
        $client = $sklblOrder->getClient();
        $countFaconnier = $sklblSkuRepository->countFaconnier($sklblOrder);
        $countSku = $sklblSkuRepository->countSku($sklblOrder);
        $countQte = $sklblSkuRepository->countQte($sklblOrder);
        $countFichiers = $sklblSkuRepository->countFichiers($sklblOrder);

        // On intercepte la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $clientFilename */
            $clientFile = $form->get('clientFilename')->getData();
            $vendorColumn = $form->get('vendorColumn')->getData();
            $idColumn = $form->get('idColumn')->getData();
            $skuColumn = $form->get('skuColumn')->getData();
            $skuTisseColumn = $form->get('skuTisseColumn')->getData();
            $qteColumn = $form->get('qteColumn')->getData();
            $deleteSku = $form->get('deleteSku')->getData();
            $ligne = $form->get('ligne')->getData();
            /*if($deleteSku){
                $deleteSku = 1;
            }else{
                $deleteSku = 0;
            }

            // On récupère le fichier client
            if ($clientFile) {
                $originalFilename = pathinfo($clientFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$clientFile->guessExtension();
                try {
                    $path = $this->getParameter('sklbl_client_file_directory').'/'.$newFilename;
                    $clientFile->move($this->getParameter('sklbl_client_file_directory'),$newFilename);
                    $bus->dispatch(new SklblStep1Service(
                        $order_id,
                        $newFilename,
                        $path,
                        $vendorColumn,
                        $idColumn,
                        $skuColumn,
                        $skuTisseColumn,
                        $qteColumn,
                        $deleteSku,
                        $ligne
                    ));

                } catch (FileException $e) {
                    $this->addFlash('error','Erreur chargement fichier');
                }
            }
            */



            //Suppression des SKU actuels si demandés
            if($deleteSku){
                foreach($skulistNonTransfere as $sku){
                    $skuDelete = $sklblSkuRepository->find($sku['id']);
                    $entityManager->remove($skuDelete);
                    $entityManager->flush();
                }
                foreach($sklblFilesNonTransfere as $file){
                    $entityManager->remove($file);
                    $entityManager->flush();
                }
            }
  
            
            // On récupère le fichier client
            if ($clientFile) {
                $originalFilename = pathinfo($clientFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$clientFile->guessExtension();

                $sklblFiles->setSklblOrder($sklblOrder);
                $sklblFiles->setClientFilename($newFilename);
                $sklblFiles->setCategorie('Customer file');
                $sklblFiles->setVendorColumn($vendorColumn);
                $sklblFiles->setSkuColumn($skuColumn);
                $sklblFiles->setQteColumn($qteColumn);
                $sklblFiles->setStatus(0);
                $entityManager->persist($sklblFiles);
                $entityManager->flush();

                
                // Move the file to the directory where brochures are stored
                try {
                    $path = $this->getParameter('sklbl_client_file_directory').'/'.$newFilename;
                    $clientFile->move(
                        $this->getParameter('sklbl_client_file_directory'),
                        $newFilename
                    );
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
                                        $sku->setStatus(0);
                                        $entityManager->persist($sku);
                                        $entityManager->flush();
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
                        $this->addFlash('danger','Aucune enregistrement détecté, vérifier le fichier.');
                    }else{
                        if($countUploadErrors == 0){
                            $this->addFlash('success',$countUploadSuccess. ' enregistrements chargés avec succès');
                            
                            $sklblFiles->setStatus(0);
                            $entityManager->persist($sklblFiles);
                            $entityManager->flush();

                            $sklblOrder->setSklblStatus(1);
                            $entityManager->persist($sklblOrder);
                            $entityManager->flush();
                        }else{
                            $this->addFlash('danger','Erreur chargement fichier, veuillez vérifier les champs: ' . $countUploadErrors .' erreurs constatées.');
                        }
                    }

                    
                                

                    
                } catch (FileException $e) {
                    $this->addFlash('error','Erreur chargement fichier');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $sklblFiles->setClientFilename($newFilename);
            }

            // ... persist the $product variable or any other work

            return $this->redirectToRoute('sklbl_step_1', [
                'order_id' => $order_id
            ]);
        }

        return $this->render('sklbl/scalabel/step1.html.twig', [
            'sklblOrder' => $sklblOrder,
            'client' => $client,
            'skus' => $skulist,
            'columnId' => $columnId,
            'columnVendor' => $columnVendor,
            'columnSku' => $columnSku,
            'columnSkuTisse' => $columnSkuTisse,
            'columnQte' => $columnQte,
            'columnLigne' => $columnLigne,
            'nbfaconnier' => $countFaconnier,
            'nbsku' => $countSku,
            'nbqte' => $countQte,
            'nbfichiers' => $countFichiers,
            'fileForm' => $form->createView()
        ]);
    }

    #[Route('/step_2/{order_id}', name: 'step_2')]
    public function step2(
        Request $request,
        SklblFilesRepository $sklblFilesRepository,
        SklblOrdersRepository $sklblOrdersRepository,
        SklblSkuRepository $sklblSkuRepository,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        int $order_id): Response
    {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $skulist = $sklblSkuRepository->getSkuList($sklblOrder);
        $skulist2 = $sklblSkuRepository->getSkuStatus0List($sklblOrder);
        $countSku = $sklblSkuRepository->countSku($sklblOrder);
        $countQte = $sklblSkuRepository->countQte($sklblOrder);
        $countProduceQte = $sklblSkuRepository->countProduceQte($sklblOrder); 
        $countOffQte = $sklblSkuRepository->countOffQte($sklblOrder); 
        if($countProduceQte > 0){
            $percentDechet = $countOffQte * 100 / $countProduceQte;
        }else{
            $percentDechet = 0;
        }
        
        $form = $this->createForm(SklblMajorationFormType::class, $sklblOrder);
        // On intercepte la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $qteLimit = $form->get('qteLimit')->getData();
            $percentAboveLimit = $form->get('percentAboveLimit')->getData();
            foreach($skulist2 as $sku){
                if(round($sku->getOrderQte() * $percentAboveLimit / 100) < 20){
                    $sku->setProduceQte($sku->getOrderQte() + $qteLimit);
                    $sku->setOffQte($sku->getProduceQte() - $sku->getOrderQte());
                }else{
                    $offQte = round($sku->getOrderQte() * $percentAboveLimit / 100);
                    $sku->setOffQte($offQte);
                    $sku->setProduceQte($sku->getOrderQte() + $offQte);
                }
                $sku->setStatus(1);
                $entityManager->persist($sku);
                $entityManager->flush($sku);
            }
            $sklblOrder->setQteLimit($qteLimit);
            $sklblOrder->setPercentAboveLimit($percentAboveLimit);
            $sklblOrder->setSklblStatus(2);
            $entityManager->persist($sklblOrder);
            $entityManager->flush($sklblOrder);
            return $this->redirectToRoute('sklbl_step_2', [
                'order_id' => $order_id
            ]);
        }

        return $this->render('sklbl/scalabel/step2.html.twig', [
            'sklblOrder' => $sklblOrder,
            'skus' => $skulist,
            'nbsku' => $countSku,
            'nbqte' => $countQte,
            'nbProduceqte' => $countProduceQte,
            'nbOffqte' => $countOffQte,
            'percentDechet' => round($percentDechet, 2),
            'majForm' => $form->createView()
        ]);
    }


    #[Route('/step_3/{order_id}', name: 'step_3')]
    public function step3(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblOfRepository $sklblOfRepository,
        SklblSkuRepository $sklblSkuRepository,
        ArticlesRepository $articlesRepository,
        ClientsRepository $clientsRepository,
        int $order_id): Response
    {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $sklblOf = $sklblOfRepository->findOneBySklblOrder($sklblOrder);
        
        if(!$sklblOf){
            $sklblOf = new SklblOf();
            $article = null;
            $client  = null;
            $planned = null;
            $produceQte = null;
            $launchedQte = null;
            $orderQte = null;
            $cond1 = null;
            $cond2 = null;
            $cond3 = null;
            $papade = null;
            $fichier1 = null;
            $mini = null;
            $exist = false;
        }else{
            $article = $sklblOf->getArticle();
            $client  = $sklblOf->getClient();
            $planned = $sklblOf->getPlannedAt()->format('d/m/Y');
            $produceQte = $sklblSkuRepository->countProduceQte($sklblOrder); 
            $launchedQte = intval($sklblOf->getLaunchedQte());
            $orderQte = intval($sklblOf->getOrderQte());
            $cond1 = $sklblOf->getEmballage1();
            $cond2 = $sklblOf->getEmballage2();
            $cond3 = $sklblOf->getEmballage3();
            $papade = $sklblOf->getMasque();
            $fichier1 = $sklblOf->getFichier1();
            $mini = $sklblOf->getMiniComplet();
            $exist = true;
        }
        return $this->render('sklbl/scalabel/step3.html.twig', [
            'sklblOrder' => $sklblOrder,
            'sklblOf' => $sklblOf,
            'article' => $article,
            'client' => $client,
            'planned_at' => $planned,
            'order_qte' => $orderQte,
            'produce_qte' => $produceQte,
            'launched_qte' => $launchedQte,
            'cond1' => $cond1,
            'cond2' => $cond2,
            'cond3' => $cond3,
            'papade' => $papade,
            'fichier1' => $fichier1,
            'mini' => $mini,
            'exist' => $exist
        ]);
    }


    #[Route('/step_4/{order_id}', name: 'step_4')]
    public function step_4(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblOfRepository $sklblOfRepository,
        SklblFilesRepository $sklblFilesRepository,
        SklblSkuRepository $sklblSkuRepository,
        SklblFxRepository $sklblFxRepository,
        int $order_id): Response
        {
            $sklblOrder = $sklblOrdersRepository->find($order_id);
            $sklblOf = $sklblOfRepository->findOneBySklblOrder($sklblOrder);
            $sklblFiles = $sklblFilesRepository->getFileNonTransfereList($sklblOrder);
            $sklblFx = $sklblFxRepository->getFxInTraitement($sklblOrder);
            $article = $sklblOf->getArticle();
            $client  = $sklblOf->getClient();

            $countFilesTraite = $sklblFilesRepository->countFilesTraite($sklblOrder);
            $countFilesNonTraite = $sklblFilesRepository->countFilesNonTraite($sklblOrder);


            $countSkuNonTraited = $sklblSkuRepository->countSkuNonTraite($sklblOrder);
            $countSkuTraited = $sklblSkuRepository->countSkuTraited($sklblOrder);

            $countFxNonTraited = $sklblSkuRepository->countFxNonTraited($sklblOrder);
            $countFxTraited = $sklblFxRepository->countFxTraited($sklblOrder);
            
            $countSku = $sklblSkuRepository->countSku($sklblOrder);
            $produceQte = $sklblSkuRepository->countProduceQte($sklblOrder);

            if($countFxNonTraited == 0 && $countFxTraited > 0 && $countFilesTraite > 0 && $countFilesNonTraite == 0){
                $activateBtnTransfert = true;
            }else{
                $activateBtnTransfert = false;
            }
            
            //dd($sklblFx);
            return $this->render('sklbl/scalabel/step4.html.twig', [
                'sklblFx' => $sklblFx,
                'sklblOrder' => $sklblOrder,
                'sklblOf' => $sklblOf,
                'sklblFiles' => $sklblFiles,
                'article' => $article,
                'client' => $client,
                'produce_qte' => $produceQte,
                'countFilesNonTraite' => $countFilesNonTraite,
                'countFilesTraite' => $countFilesTraite,
                'countSkuNonTraited' => $countSkuNonTraited,
                'countSkuTraited' => $countSkuTraited,
                'countFxNonTraited' => $countFxNonTraited,
                'countFxTraited' => $countFxTraited,
                'count_sku' => $countSku,
                'activateBtnTransfert' => $activateBtnTransfert
            ]);
    }

    #[Route('/import_ofs/{order_id}', name: 'import_ofs')]
    public function import_ofs(
        SklblOrdersRepository $sklblOrdersRepository,
        KernelInterface $kernel,
        int $order_id): Response
        {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
                'command' => 'Sklbl:Import-ofs',
                'dossier' => $sklblOrder->getDossier(),
                'jalon' => 10,
            ]);

            // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);
        return $this->redirectToRoute('sklbl_step_3', [
            'order_id' => $order_id
        ]);
    }


    #[Route('/confirmer_of/{order_id}/{of_id}', name: 'confirmer_of')]
    public function confirmer_of(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblOfRepository $sklblOfRepository,
        SklblFilesRepository $sklblFilesRepository,
        EntityManagerInterface $entityManager,
        int $order_id,
        int $of_id): Response
        {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $sklblOf = $sklblOfRepository->find($of_id);
        $sklblFiles = $sklblFilesRepository->getFileNonTransfereList($sklblOrder);
        foreach($sklblFiles as $sklblFile){
            $sklblFile->setSklblOf($sklblOf);
            $entityManager->persist($sklblFile);
        }

        $sklblOrder->setSklblStatus(3);
        $entityManager->persist($sklblOrder);
        $entityManager->flush();
        return $this->redirectToRoute('sklbl_step_4', [
            'order_id' => $order_id
        ]);
    }

    #[Route('/generate_f1/{of_id}', name: 'generate_f1')]
    public function generate_f1(
        SklblOfRepository $sklblOfRepository,
        SklblSkuRepository $sklblSkuRepository,
        SklblFilesRepository $sklblFilesRepository,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
        int $of_id): Response
        {
        $sklblOf = $sklblOfRepository->find($of_id);
        $sklblOrder = $sklblOf->getSklblOrder();
        $sklblFiles = $sklblFilesRepository->getFileNonTransfereList($sklblOrder);
       
        foreach($sklblFiles as $file){
            $bus->dispatch(new SklblStep41Service($file->getId()));
        }  

        return $this->redirectToRoute('sklbl_step_4', [
            'order_id' => $sklblOrder->getId()
        ]);
    }


    #[Route('/ask_transfert/{of_id}', name: 'ask_transfert')]
    public function ask_transfert(
        SklblOfRepository $sklblOfRepository,
        SklblSkuRepository $sklblSkuRepository,
        SklblFilesRepository $sklblFilesRepository,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
        int $of_id): Response
        {
        $sklblOf = $sklblOfRepository->find($of_id);
        $sklblOrder = $sklblOf->getSklblOrder();
        $sklblFiles = $sklblFilesRepository->getFileATransfereList($sklblOrder);

        foreach($sklblFiles as $fileU){
            // Debut du futur bus
            $sklblStep42Service = new SklblStep42Service($fileU->getId());
            $fileRepository = $entityManager->getRepository(SklblFiles::class);
            $file = $fileRepository->find($sklblStep42Service->getIdFile());
            $sklblSkuRepository = $entityManager->getRepository(sklblSku::class);
            $skus = $sklblSkuRepository->getSkuATransfereListByFile($file);
            $sklblFxRepository = $entityManager->getRepository(sklblFx::class);
            $excel = new SklblExcelService();
            $filename = $excel->createNewF1($file,$this->getParameter('sklbl_scalabel_f1_directory'));

            dd($filename);
           /* if(sizeof($skus) > 0){
                foreach($skus as $sku){
                    $fxs =  $sklblFxRepository->getFxATransfereListBySku($sku);
                    if(sizeof($fxs) > 0){
                        foreach($fxs as $fx){

                        }
                    }
                }
            }*/


            // Fin du futur bus
        }  

        return $this->redirectToRoute('sklbl_step_4', [
            'order_id' => $sklblOrder->getId()
        ]);
    }

    #[Route('/generate_f1_test', name: 'generate_f1_test')]
    public function generate_f1_test(Request $request, MessageBusInterface $bus)
    {
        // ...

        // SendNotificationHandler sera automatiquement appelé
        $context = array();
        $bus->dispatch(new SendNotification(
            'no-reply@neyret.com',
            'fdavoine@neyret.com',
            'Mail test',
            'test',
            'test',
            $context
        ));
        return $this->redirectToRoute('sklbl_index');
    }

 



    #[Route('/api/emballage/import/{dossier}', name: 'emballage_import', methods:['post'] )]
    public function emballage_import(ManagerRegistry $doctrine,
    SklblRepository $sklblRepository,
    SklblEmballageRepository $sklblEmballageRepository, 
    EntityManagerInterface $entityManager, 
    ArticlesRepository $articlesRepository,
    string $dossier,
    Request $request): JsonResponse
    {
        $count = 0;
        $emballages = $sklblRepository->getDivaltoEmballages($dossier);
        foreach($emballages as $DivaltoEmballage){
            $emballage = $sklblEmballageRepository->find($DivaltoEmballage['T033_ID']);
            $article = $articlesRepository->findOneByRef($DivaltoEmballage['REF']);
            if($article){
                if(!$emballage){
                    $emballage = new SklblEmballage();
                    $emballage->setId($DivaltoEmballage['T033_ID']);
                }
                $emballage->setDossier($DivaltoEmballage['DOS']);
                $emballage->setCode($DivaltoEmballage['EMBUN']);
                $emballage->setLibelle($DivaltoEmballage['LIB']);
                $emballage->setArticle($article);
                $emballage->setQte($DivaltoEmballage['EMBQTE']);
                $emballage->setUnite($DivaltoEmballage['VENUN']);
                $emballage->setOrdre($DivaltoEmballage['ORDRE']);
                $entityManager->persist($emballage);
                $entityManager->flush();
                $count ++;
            }

        }
        return $this->json($count . ' Emballages importés');
    }

    #[Route('/api/rubrique/import/{dossier}', name: 'rubrique_import', methods:['post'] )]
    public function rubrique_import(ManagerRegistry $doctrine,
    SklblRepository $sklblRepository,
    SklblRubriqueRepository $sklblRubriqueRepository, 
    EntityManagerInterface $entityManager, 
    string $dossier,
    Request $request): JsonResponse
    {
        $count = 0;
        $rubriques = $sklblRepository->getDivaltoRubriques($dossier);
        foreach($rubriques as $DivaltoRubrique){
            $rubrique = $sklblRubriqueRepository->find($DivaltoRubrique['MRBQVAL_ID']);

            if(!$rubrique){
                $rubrique = new SklblRubrique();
                $rubrique->setId($DivaltoRubrique['MRBQVAL_ID']);
            }
            $rubrique->setDossier($DivaltoRubrique['DOS']);
            $rubrique->setEntite($DivaltoRubrique['ENTITEINDEX']);
            $rubrique->setRubrique($DivaltoRubrique['RUBRIQUE']);
            $rubrique->setValeur($DivaltoRubrique['RBQVAL']);
            $rubrique->setCreatedAt(new DateTimeImmutable($DivaltoRubrique['USERCRDH']));
            $rubrique->setUpdatedAt(new DateTimeImmutable($DivaltoRubrique['USERMODH']));
            $entityManager->persist($rubrique);
            $entityManager->flush();
            $count ++;
            

        }
        return $this->json($count . ' Rubriques importées');
    }


    #[Route('/api/orders/import/{dossier}/{days}', name: 'ordersSklbl_import', methods:['post'] )]
    public function orders_import(ManagerRegistry $doctrine,
    SklblRepository $sklblRepository,
    EntityManagerInterface $entityManager, 
    ClientsRepository $clientsRepository,
    ArticlesRepository $articlesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    string $dossier,
    int $days,
    Request $request): JsonResponse
    {
        $orders = $sklblRepository->getDivaltoSklblOrders($dossier,$days);
        $currentDate = new DateTimeImmutable();
        $count = 0;
        foreach($orders as $order){
            $import_order = false;
            $currentDate = new DateTimeImmutable();
            $created_at = new DateTimeImmutable($order['USERCRDH']);
            $udpated_at = new DateTimeImmutable($order['USERMODH']);
            $article = $articlesRepository->findOneByRef($order['REF']);
            $client = $clientsRepository->findOneByCode($order['TIERS']);
            $sklblOrders = $sklblOrdersRepository->findOrder($order,$article);
            if(sizeof($sklblOrders) > 0){
                $sklblOrder = $sklblOrders[0];
                //if($sklblOrder->getUpdatedAt() < $udpated_at){
                    $import_order = true;
                //}
                echo "<br/>Trouvé";
                
            }else{
                $import_order = true;
                $sklblOrder = new SklblOrders();
                $sklblOrder->setSklblStatus(0);
            }

            if($import_order){
                if($article && $client){
                    $sklblOrder->setDossier($order['DOS']);
                    $sklblOrder->setOrderNum($order['CDNO']);
                    $sklblOrder->setOrderAt(new DateTimeImmutable($order['CDDT']));
                    $sklblOrder->setArticle($article);
                    $sklblOrder->setClient($client);
                    $sklblOrder->setSref1($order['SREF1']);
                    $sklblOrder->setSref2($order['SREF2']);
                    $sklblOrder->setOrderQte($order['CDQTE']);
                    $sklblOrder->setCreatedAt($created_at);
                    $sklblOrder->setUpdatedAt($udpated_at);
                    $sklblOrder->setStatus($order['PICOD']);
                    $entityManager->persist($sklblOrder);
                    $entityManager->flush();
                    $count ++;
                }
            }
        
        }
        return $this->json($count . ' Ofs Scalabel importés');
    }


    #[Route('/api/ofs/import/{dossier}/{days}', name: 'ofsSklbl_import', methods:['post'] )]
    public function ofs_import(ManagerRegistry $doctrine,
    SklblRepository $sklblRepository,
    EntityManagerInterface $entityManager, 
    ClientsRepository $clientsRepository,
    ArticlesRepository $articlesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblOfRepository $sklblOfRepository,
    SklblRubriqueRepository $sklblRubriqueRepository,
    SklblEmballageRepository $sklblEmballageRepository,
    string $dossier,
    int $days,
    Request $request): JsonResponse
    {
        $ofs = $sklblRepository->getDivaltoSklblOfs($dossier,$days);
        $currentDate = new DateTimeImmutable();
        $count = 0;
        foreach($ofs as $of){
            $import_of = false;
            $created_at = new DateTimeImmutable($of['USERCRDH']);
            $udpated_at = new DateTimeImmutable($of['USERMODH']);
            $ofSqlbl = $sklblOfRepository->find($of['BF_ID']);
            if($ofSqlbl){
                $import_of = true;
               /* if($ofSqlbl->getUpdatedAt() < $udpated_at){
                    $import_of = true;
                }*/
            }else{
                $import_of = true;
            }
            if($import_of){
                if(!$ofSqlbl){
                    $ofSqlbl = new SklblOf();
                    $ofSqlbl->setId($of['BF_ID']);
                    $ofSqlbl->setSync(1);
                }
                $article = $articlesRepository->findOneByRef($of['REF']);
                $client = $clientsRepository->findOneByCode($of['TIERS']);
                if($article && $client){
                    $ofSqlbl->setDossier($of['DOS']);
                    $ofSqlbl->setArticle($article);
                    $ofSqlbl->setClient($client);
                    $ofSqlbl->setRefCli($of['REF_CLI']);
                    $ofSqlbl->setCode($of['PINO']);
                    $ofSqlbl->setSref1($of['SREF1']);
                    $ofSqlbl->setSref2($of['SREF2']);
                    $ofSqlbl->setOrderQte($of['QTE_CDE']);
                    $ofSqlbl->setLaunchedQte($of['QTE_OF']);
                    $ofSqlbl->setCreatedAt(new DateTimeImmutable($of['USERCRDH']));
                    $ofSqlbl->setUpdatedAt(new DateTimeImmutable($of['USERMODH']));
                    $ofSqlbl->setSync(1);
                    if($of['ID_EMBALLAGE1']){
                        $ofSqlbl->setEmballage1($sklblEmballageRepository->find($of['ID_EMBALLAGE1']));
                    }
                    if($of['ID_EMBALLAGE2']){
                        $ofSqlbl->setEmballage2($sklblEmballageRepository->find($of['ID_EMBALLAGE2']));
                    }
                    if($of['ID_EMBALLAGE3']){
                        $ofSqlbl->setEmballage3($sklblEmballageRepository->find($of['ID_EMBALLAGE3']));
                    }
                    if($of['ID_EMBALLAGE4']){
                        $ofSqlbl->setEmballage4($sklblEmballageRepository->find($of['ID_EMBALLAGE4']));
                    }
                    if($of['ID_RUBRIQUE1']){
                        $ofSqlbl->setFichier1($sklblRubriqueRepository->find($of['ID_RUBRIQUE1']));
                    }
                    if($of['ID_RUBRIQUE2']){
                        $ofSqlbl->setFichier2($sklblRubriqueRepository->find($of['ID_RUBRIQUE2']));
                    }
                    if($of['ID_RUBRIQUE3']){
                        $ofSqlbl->setMiniComplet($sklblRubriqueRepository->find($of['ID_RUBRIQUE3']));
                    }
                    if($of['ID_RUBRIQUE4']){
                        $ofSqlbl->setMasque($sklblRubriqueRepository->find($of['ID_RUBRIQUE4']));
                    }
                    if($of['ID_RUBRIQUE5']){
                        $ofSqlbl->setFichierRetour($sklblRubriqueRepository->find($of['ID_RUBRIQUE5']));
                    }
                    if($of['ID_RUBRIQUE6']){
                        $ofSqlbl->setOptions($sklblRubriqueRepository->find($of['ID_RUBRIQUE6']));
                    }
                    $ofSqlbl->setOfStatus($of['STATUS']);
                    $ofSqlbl->setOrderNum($of['CDNO']);
                    $order = $sklblOrdersRepository->identifyOrder($ofSqlbl);
                    if(sizeof($order) > 0){
                        $ofSqlbl->setSklblOrder($order[0]);
                    }
                    
                    $ofSqlbl->setSklblStatus(1);
                    
                    $ofSqlbl->setPlannedAt(new DateTimeImmutable($of['PREVDEBDH']));
                    $ofSqlbl->setStartAt(new DateTimeImmutable($of['DEBDH']));
                    $ofSqlbl->setEndAt(new DateTimeImmutable($of['FINDH']));

                    $entityManager->persist($ofSqlbl);
                    $entityManager->flush();
                    $count ++;
                }
            }

        }
        return $this->json($count . ' Ofs Scalabel importés');
    }


    protected function getParameter(string $name): array|bool|string|int|float|\UnitEnum|null
    {
        if (!$this->container->has('parameter_bag')) {
            throw new ServiceNotFoundException('parameter_bag.', null, null, [], sprintf('The "%s::getParameter()" method is missing a parameter bag to work properly. Did you forget to register your controller as a service subscriber? This can be fixed either by using autoconfiguration or by manually wiring a "parameter_bag" in the service locator passed to the controller.', static::class));
        }

        return $this->container->get('parameter_bag')->get($name);
    }






}
