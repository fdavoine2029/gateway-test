<?php

namespace App\Controller\Sklbl;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sklbl\SklblEmballage;
use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblFx;
use App\Entity\Sklbl\SklblFx2;
use App\Entity\Sklbl\SklblLogs;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\SklblRubrique;
use App\Entity\Sklbl\sklblSku;
use App\Entity\Sklbl\SklblUploadConfig;
use App\Form\Sklbl\SklblFilesFormType;
use App\Form\Sklbl\SklblMajorationFormType;
use App\Form\Sklbl\SklblUploadConfigFormType;
use App\Message\SendNotification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticlesRepository;
use App\Repository\ClientsRepository;
use App\Repository\Divalto\SklblRepository;
use App\Repository\Sklbl\SklblEmballageRepository;
use App\Repository\Sklbl\SklblFilesRepository;
use App\Repository\Sklbl\SklblFx2Repository;
use App\Repository\Sklbl\SklblFxRepository;
use App\Repository\Sklbl\SklblOfRepository;
use App\Repository\Sklbl\SklblOrdersRepository;
use App\Repository\Sklbl\SklblRubriqueRepository;
use App\Repository\Sklbl\sklblSkuRepository;
use App\Repository\Sklbl\SklblUploadConfigRepository;
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
    public function step_1(
        Request $request,
        SklblFilesRepository $sklblFilesRepository,
        SklblOrdersRepository $sklblOrdersRepository,
        SklblSkuRepository $sklblSkuRepository,
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
        int $order_id): Response
    {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $columns = $sklblUploadConfigRepository->findBySklblOrderActive($sklblOrder);
        $skulist =$sklblSkuRepository->getSkuList($sklblOrder);
        $sklblFiles = new SklblFiles();
        $sklblFiles_param = $sklblFilesRepository->findOneBySklblOrder($sklblOrder);
        
        if($sklblFiles_param){
            $columnLigne = $sklblFiles_param->getLigne();
        }else{
            $columnLigne = "";
        }
        $form = $this->createForm(SklblFilesFormType::class, $sklblFiles);
        $client = $sklblOrder->getClient();
        $countFaconnier = $sklblSkuRepository->countFaconnier($sklblOrder);
        $countSku = $sklblSkuRepository->countSku($sklblOrder);
        $countQte = $sklblSkuRepository->countQte($sklblOrder);
        $files = $sklblFilesRepository->getCustomerOrderFileList($sklblOrder);

        // On intercepte la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $clientFilename */
            $clientFile = $form->get('clientFilename')->getData();
            $ligne = $form->get('ligne')->getData();

            if ($clientFile) {
                $originalFilename = pathinfo($clientFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$clientFile->guessExtension();
                try {
                    $clientFile->move(
                        $this->getParameter('sklbl_fichier_client_a_charger'),
                        $newFilename
                    );
                    $sklblFiles->setSklblOrder($sklblOrder);
                    $sklblFiles->setClientFilename($newFilename);
                    $sklblFiles->setCategorie('Customer file');
                    $sklblFiles->setLigne($ligne);
                    $sklblFiles->setStatus(0);
                    $entityManager->persist($sklblFiles);
                    $entityManager->flush();

                    $sklblOrder->setSklblStatus(1);
                    $entityManager->persist($sklblOrder);
                    $entityManager->flush();

                    $this->addFlash('success','Fichier ' . $newFilename . ' soumis avec succès');
                    return $this->redirectToRoute('sklbl_step_1', [
                        'order_id' => $order_id
                    ]);
                } catch (FileException $e) {
                    $this->addFlash('error','Erreur chargement fichier');
                }
            }

        }


        return $this->render('sklbl/scalabel/step1.html.twig', [
            'sklblOrder' => $sklblOrder,
            'columns' => $columns,
            'client' => $client,
            'skus' => $skulist,
            'columnLigne' => $columnLigne,
            'nbfaconnier' => $countFaconnier,
            'nbsku' => $countSku,
            'nbqte' => $countQte,
            'files' => $files,
            'fileForm' => $form->createView()
        ]);

    }

    




    #[Route('/api/step_1', name: 'api_step_1', methods:['post'] )]
    public function api_step_1(ManagerRegistry $doctrine,
    SklblFilesRepository $sklblFilesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblSkuRepository $sklblSkuRepository,
    SklblUploadConfigRepository $sklblUploadConfigRepository,
    EntityManagerInterface $entityManager,
    Request $request): JsonResponse
    {
        // On liste les commandes en cours
        $orders = $sklblOrdersRepository->getCurrentOrders();
        $log = new SklblLogs();
        foreach($orders as $order){
            // Pour chaque commande on vérifier si des demandes de transfert ont été enregistrées
            // On compte également les fichiers déja transférés pour vérifier à la fin que tous les fichiers sont chargés et ok
            $files = $sklblFilesRepository->getFileAIntegrerList($order);
            $filesDejaChargés = $sklblFilesRepository->getFileATransfereList($order);
            $countFilesACharger = sizeof($files); 

            // On récupère les colonnes
            
            $columnList = $sklblUploadConfigRepository->findBySklblOrderActive($order);

            // Si des demandes de transfert sont détectées
            // Lancement de l'intégration

            if($countFilesACharger > 0){
                // On parcourt chaque fichier à charger
                foreach($files as $file){
                    $filePresent = true;
                    $fileContientEnregistrements = true;
                    $path = $this->getParameter('sklbl_fichier_client_a_charger').'/'.$file->getClientFilename();

                    
                    $ligne = $file->getLigne();
                    $excelService = new SklblExcelService();

                    // On vérifie que le fichier est présent
                    try {
                        $records = $excelService->integrateCustomerFile($order,$path);
                    } catch (Exception $e) {
                        $log->setExecutedAt(new DateTimeImmutable());
                        $log->setJobName('Step_1: Import fichier client');
                        $log->setMessage('Le fichier ' . $file->getClientFilename() . ' est introuvable');
                        $log->setStatus(-1);
                        $entityManager->persist($log);
                        $entityManager->flush();
                        $entityManager->remove($file);
                        $entityManager->flush();
                        $filePresent = false;
                    }


                    // On vérifie que le fichier contient des enregistrements
                    if($filePresent){
                        if(sizeof($records) == 0){
                            $log->setExecutedAt(new DateTimeImmutable());
                            $log->setJobName('Step_1: Import fichier client');
                            $log->setMessage('Le fichier ' . $file->getClientFilename() . ' est vide');
                            $log->setStatus(-1);
                            $entityManager->persist($log);
                            $entityManager->flush();
                            $entityManager->remove($file);
                            $entityManager->flush();
                            $fileContientEnregistrements = false;
                        }
                    }



                    if($filePresent && $fileContientEnregistrements){
                        // On commence le chargement du fichier
                        $log->setExecutedAt(new DateTimeImmutable());
                        $log->setJobName('Step_1: Import fichier client');
                        $log->setMessage('Début de l import de ' . $file->getClientFilename() . '...');
                        $log->setStatus(1);
                        $entityManager->persist($log);
                        $entityManager->flush();


                        $sku_index = 1;
                        $countUploadSuccess = 0;
                        $countUploadErrors = 0;
                        foreach($records as $row){
                            if($sku_index >= $ligne){
                                $column_index = 0;
                                $sku = new sklblSku();
                                // On parcourt chaque colonne
                                $nbcolonneErrors = 0;
                                while($column_index < sizeof($row)){

                                    // On identifie la lettre de la colonne
                                    $column = $excelService->num2alpha($column_index);
                                    foreach($columnList as $paramcol){
                                        if($paramcol->getColumnCsv() == $column){
                                            switch ($paramcol->getNum()) {
                                                case 1:
                                                    $sku->setId($row[$column_index]);
                                                    break;
                                                case 2:
                                                    $sku->setVendor($row[$column_index]);
                                                    break;
                                                case 3:
                                                    $sku->setSku($row[$column_index]);
                                                    break;
                                                case 4:
                                                    $sku->setSkuTisse($row[$column_index]);
                                                    break;
                                                case 5:
                                                    try {
                                                        $sku->setOrderQte(intval($row[$column_index]));
                                                    } catch (Exception $e) {
                                                        $nbcolonneErrors++;
                                                    }
                                                    break;
                                                case 6:
                                                    $sku->setOptData1($row[$column_index]);
                                                    break;
                                                case 7:
                                                    $sku->setOptData2($row[$column_index]);
                                                    break;
                                                case 8:
                                                    $sku->setOptData3($row[$column_index]);
                                                    break;
                                                case 9:
                                                    $sku->setOptData4($row[$column_index]);
                                                    break;
                                                case 10:
                                                    $sku->setOptData5($row[$column_index]);
                                                    break;
                                                case 11:
                                                    $sku->setOptData6($row[$column_index]);
                                                    break;
                                                case 12:
                                                    $sku->setOptData7($row[$column_index]);
                                                    break;
                                                case 12:
                                                    $sku->setOptData8($row[$column_index]);
                                                    break;
                                                case 12:
                                                    $sku->setOptData9($row[$column_index]);
                                                    break;
                                                case 12:
                                                    $sku->setOptData10($row[$column_index]);
                                                    break;
                                            }
                                        }
                                    }
                                    $column_index++;
                                }

                                if($nbcolonneErrors == 0){
                                    try {
                                        $sku->setSklblOrder($order);
                                        $sku->setSklblFile($file);
                                        $sku->setStatus(1);
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
                        if($countUploadErrors == 0){
                            $log = new SklblLogs();
                            $log->setExecutedAt(new DateTimeImmutable());
                            $log->setJobName('Step_1: Import fichier client');
                            $log->setMessage('L import de ' . $file->getClientFilename() . ' a été réalisé avec succès');
                            $log->setStatus(1);
                            $entityManager->persist($log);
                            $entityManager->flush();

                            rename($path, $this->getParameter('sklbl_fichier_client_success').'/'.$file->getClientFilename());
                            $file->setStatus(1);
                            $entityManager->persist($file);
                            $entityManager->flush();
                        }else{
                            $log = new SklblLogs();
                            $log->setExecutedAt(new DateTimeImmutable());
                            $log->setJobName('Step_1: Import fichier client');
                            $log->setMessage('Il y a ' . $countUploadErrors . ' erreur(s) dans l import de  ' . $file->getClientFilename());
                            $log->setStatus(-1);
                            

                            rename($path, $this->getParameter('sklbl_fichier_client_error').'/'.$file->getClientFilename());
                            $file->setStatus(-1);
                            $entityManager->persist($file);
                            $entityManager->flush();
                        }

                    }

                }
            }

            $filesEnErreur = $sklblFilesRepository->countStep1FilesError($order);
            if($filesEnErreur > 0){

                $log = new SklblLogs();
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setJobName('Step_1: Import fichier client');
                $log->setMessage('Step_1: Erreur d import pour la commande '. $order->getOrderNum());
                $log->setStatus(-1);

                $order->setSklblStatus(-1);
                $entityManager->persist($order);
                $entityManager->flush();
            }else{
                $filesSuccess = $sklblFilesRepository->countStep1FilesSuccess($order);
                if($filesSuccess > 0){
                    $log = new SklblLogs();
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setJobName('Step_1: Import fichier client');
                    $log->setMessage('Step_1: Succès import pour la commande '. $order->getOrderNum());
                    $log->setStatus(1);

                    $order->setSklblStatus(2);
                    $entityManager->persist($order);
                    $entityManager->flush();
                }
                
            }

            
        }
        
        return $this->json('Traitement terminé');

    }

    #[Route('/step_1/delete_sku/{file_id}', name: 'delete_sku')]
    public function delete_sku(
        SklblFilesRepository $sklblFilesRepository,
        EntityManagerInterface $entityManager,
        $file_id)
    {
        $file = $sklblFilesRepository->find($file_id);
        $order = $file->getSklblOrder();
        $skus = $file->getSklblSkus();
        foreach($skus as $sku){
            $entityManager->remove($sku);
            $entityManager->flush();
        }
        $entityManager->remove($file);
        $entityManager->flush();

        return $this->redirectToRoute('sklbl_step_1', [
            'order_id' => $order->getId()
        ]);
    }



    #[Route('/step_1/configure_colum/{order_id}/{nb_column}', name: 'configure_colum')]
    public function step_1_conf_column(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        $order_id,
        $nb_column)
    {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $client = $sklblOrder->getClient();
        if($nb_column == 5){
            $nb_column2 = $sklblUploadConfigRepository->countColumn($sklblOrder);
            if($nb_column2 > 5 ){
                $nb_column = $nb_column2;
            }
        }
        

        $column1 = $sklblUploadConfigRepository->findColumn($sklblOrder,1);
        $column2 = $sklblUploadConfigRepository->findColumn($sklblOrder,2);
        $column3 = $sklblUploadConfigRepository->findColumn($sklblOrder,3);
        $column4 = $sklblUploadConfigRepository->findColumn($sklblOrder,4);
        $column5 = $sklblUploadConfigRepository->findColumn($sklblOrder,5);

        if(!$column1){
            $column1 = new SklblUploadConfig();
            $column1->setColumnName('id');
            $column1->setColumnLabel('Identifiant');
        }
        if(!$column2){
            $column2 = new SklblUploadConfig();
            $column2->setColumnName('vendor');
            $column2->setColumnLabel('Façonnier');
        }
        if(!$column3){
            $column3 = new SklblUploadConfig();
            $column3->setColumnName('sku');
            $column3->setColumnLabel('Lot (Sku)');
        }
        if(!$column4){
            $column4 = new SklblUploadConfig();
            $column4->setColumnName('sku_tisse');
            $column4->setColumnLabel('Variable à tisser');
        }
        if(!$column5){
            $column5 = new SklblUploadConfig();
            $column5->setColumnName('order_qte');
            $column5->setColumnLabel('Quantité');
        }

        $items = ['column1' => $column1, 
        'column2' => $column2,
        'column3' => $column3,
        'column4' => $column4,
        'column5' => $column5];

        if($nb_column > 5){
            $ind = 6;
            while($ind < $nb_column + 1){
                $column = $sklblUploadConfigRepository->findColumn($sklblOrder,$ind);
                if(!$column){
                    $column = new SklblUploadConfig();
                    $column->setColumnName('opt_data'.$ind - 5);
                }
                $items['column'.$ind] = $column;
                $ind ++;
            }
        }

        $form = $this->createFormBuilder($items)
        ->add('column1',SklblUploadConfigFormType::class, [
            'data_class' => SklblUploadConfig::class,
        ])
        ->add('column2',SklblUploadConfigFormType::class, [
            'data_class' => SklblUploadConfig::class,
        ])
        ->add('column3',SklblUploadConfigFormType::class, [
            'data_class' => SklblUploadConfig::class,
        ])
        ->add('column4',SklblUploadConfigFormType::class, [
            'data_class' => SklblUploadConfig::class,
        ])
        ->add('column5',SklblUploadConfigFormType::class, [
            'data_class' => SklblUploadConfig::class,
        ]);

        if($nb_column > 5){
            $ind = 6;
            while($ind < $nb_column + 1){
                $form->add('column'.$ind,SklblUploadConfigFormType::class, [
                    'data_class' => SklblUploadConfig::class,
                ]);
                $ind ++;
            }
        }
        
        $form = $form->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $num = 1;
            $allcolumns = $sklblUploadConfigRepository->findBySklblOrder($sklblOrder);
            foreach($allcolumns as $allcolumn){
                $allcolumn->setStatus(0);
                $entityManager->persist($allcolumn);
                $entityManager->flush($allcolumn);
            }

            foreach($form as $record){
                $column = $sklblUploadConfigRepository->findColumn($sklblOrder,$num);
                if(!$column){
                    $column = new SklblUploadConfig();
                }
                
                $column->setSklblOrder($sklblOrder);
                $column->setColumnName($record->get('columnName')->getData());
                $column->setColumnLabel($record->get('columnLabel')->getData());
                $column->setColumnCsv($record->get('columnCsv')->getData());
                $column->setNum($num);
                $column->setStatus(1);
                $column->setLisage($record->get('lisage')->getData());
                $entityManager->persist($column);
                $entityManager->flush($column);
                $num ++;
            }
        }


        return $this->render('sklbl/scalabel/step1_conf.html.twig', [
            'sklblOrder' => $sklblOrder,
            'client' => $client,
            'nbColumn' => $nb_column,
            'form' => $form->createView()
        ]);

    }


    #[Route('/step_2/{order_id}', name: 'step_2')]
    public function step2(
        Request $request,
        SklblFilesRepository $sklblFilesRepository,
        SklblOrdersRepository $sklblOrdersRepository,
        SklblSkuRepository $sklblSkuRepository,
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        int $order_id): Response
    {
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $columnList = $sklblUploadConfigRepository->findBySklblOrderActive($sklblOrder);
        $skulist = $sklblSkuRepository->getSkuList($sklblOrder);
        $skulist2 = $sklblSkuRepository->getSkuStep1List($sklblOrder);
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
                $sku->setStatus(2);
                $entityManager->persist($sku);
                $entityManager->flush($sku);
            }
            $sklblOrder->setQteLimit($qteLimit);
            $sklblOrder->setPercentAboveLimit($percentAboveLimit);
            $sklblOrder->setSklblStatus(3);
            $entityManager->persist($sklblOrder);
            $entityManager->flush($sklblOrder);
            return $this->redirectToRoute('sklbl_step_2', [
                'order_id' => $order_id
            ]);
        }

        return $this->render('sklbl/scalabel/step2.html.twig', [
            'sklblOrder' => $sklblOrder,
            'columns' => $columnList,
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
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        int $order_id): Response
        {
            $sklblOrder = $sklblOrdersRepository->find($order_id);
            $columnList = $sklblUploadConfigRepository->findBySklblOrderActive($sklblOrder);
            $sklblOf = $sklblOfRepository->findOneBySklblOrder($sklblOrder);
            $sklblFiles = $sklblFilesRepository->getStep4Files($sklblOrder);
            $sklblFx = $sklblFxRepository->getFxInTraitement($sklblOrder);
            $article = $sklblOf->getArticle();
            $client  = $sklblOf->getClient();

            $countFilesTraite = $sklblFilesRepository->countFilesTraite($sklblOrder);
            $countFilesNonTraite = $sklblFilesRepository->countFilesNonTraite($sklblOrder);
            $countFilesAttenteTransfert = $sklblFilesRepository->countFileAttenteTransfert($sklblOrder);


            $countSkuNonTraited = $sklblSkuRepository->countSkuNonTraite($sklblOrder);
            $countSkuTraited = $sklblSkuRepository->countSkuTraited($sklblOrder);
            $countSkuAttenteTransfert = $sklblSkuRepository->countSkuAttenteTransfert($sklblOrder);
            
            $countSku = $sklblSkuRepository->countSku($sklblOrder);
            $produceQte = $sklblSkuRepository->countProduceQte($sklblOrder);

            if($countSkuNonTraited == 0 && $countSkuTraited > 0 && $countFilesTraite > 0 && $countFilesNonTraite == 0){
                $activateBtnTransfert = true;
            }else{
                $activateBtnTransfert = false;
            }
            
            return $this->render('sklbl/scalabel/step4.html.twig', [
                'sklblFx' => $sklblFx,
                'sklblOrder' => $sklblOrder,
                'columns' => $columnList,
                'sklblOf' => $sklblOf,
                'sklblFiles' => $sklblFiles,
                'article' => $article,
                'client' => $client,
                'produce_qte' => $produceQte,
                'countFilesNonTraite' => $countFilesNonTraite,
                'countFilesTraite' => $countFilesTraite,
                'countFilesAttenteTransfert' => $countFilesAttenteTransfert,
                'countSkuNonTraited' => $countSkuNonTraited,
                'countSkuTraited' => $countSkuTraited,
                'countSkuAttenteTransfert' => $countSkuAttenteTransfert,
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

        $sklblOrder->setSklblStatus(4);
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
        $sklblFiles = $sklblFilesRepository->getStep4FilesEnAttente($sklblOrder);
        foreach($sklblFiles as $file){
            $file->setStatus(2);
            $entityManager->persist($file);
            $entityManager->flush();
        }
        $sklblOrder->setSklblStatus(5);
        $entityManager->persist($sklblOrder);
        $entityManager->flush();

        return $this->redirectToRoute('sklbl_step_4', [
            'order_id' => $sklblOrder->getId()
        ]);
    }

    #[Route('/api/step_41', name: 'api_step_41', methods:['post'] )]
    public function api_step_41(ManagerRegistry $doctrine,
    SklblFilesRepository $sklblFilesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblSkuRepository $sklblSkuRepository,
    EntityManagerInterface $entityManager,
    Request $request): JsonResponse
    {
        // On récupère
        $orders = $sklblOrdersRepository->getCurrentOrders();
        $log = new SklblLogs();
        foreach($orders as $order){
            // Pour chaque commande on vérifier si des demandes de génération des enregistrements ont été enregistrées
            $files = $sklblFilesRepository->getStep4FileAGenererList($order);
            $countStep4FileAGenerer = sizeof($files); 
            if($countStep4FileAGenerer > 0){
                foreach($files as $file){
                    $skus = $sklblSkuRepository->findStep4AGenerer($file);
                    $of = $file->getSklblOf();
                    foreach($skus as $sku){
                        $indice_sku = 0;
                        while($indice_sku < $sku->getProduceQte())
                        {
                            $fx = new SklblFx();
                            $fx->setSklblOrder($order);
                            $fx->setSklblOf($of);
                            $fx->setSklblFile($file);
                            $fx->setSklblSku($sku);
                            $fx->setStatus(1);
                            $currentDate = new DateTimeImmutable();
                            $fx->setUpdatedAt($currentDate);
                            $entityManager->persist($fx);
                            $entityManager->flush($fx);
                            $indice_sku++;
                        }
                        $sku->setStatus(3);
                        $entityManager->persist($sku);
                        $entityManager->flush($sku);
                        
                    }
                    $countStep4AGenerer = $sklblSkuRepository->countStep4AGenerer($file);
                    $countStep4Genere = $sklblSkuRepository->countStep4Genere($file);
                    if($countStep4AGenerer == 0 and $countStep4Genere > 0){
                        
                        $file->setStatus(3);
                        $entityManager->persist($file);
                        $entityManager->flush($file);

                        $order->setSklblStatus(6);
                        $entityManager->persist($order);
                        $entityManager->flush();

                        $log = new SklblLogs();
                        $log->setExecutedAt(new DateTimeImmutable());
                        $log->setJobName('Step_41: Génération de enregistrements');
                        $log->setMessage('La génération de ' . $file->getClientFilename() . ' a été réalisé avec succès');
                        $log->setStatus(1);
                        $entityManager->persist($log);
                        $entityManager->flush();
                    }else{
                        $file->setStatus(-3);
                        $entityManager->persist($file);
                        $entityManager->flush($file);

                        $log = new SklblLogs();
                        $log->setExecutedAt(new DateTimeImmutable());
                        $log->setJobName('Step_41: Génération de enregistrements');
                        $log->setMessage('Erreur dans la génération de ' . $file->getClientFilename());
                        $log->setStatus(-1);
                        $entityManager->persist($log);
                        $entityManager->flush();
                    }
                    
                }

            }

        }


        return $this->json('Traitement terminé');
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
        $sklblFiles = $sklblFilesRepository->getStep4FilesATransferer($sklblOrder);
        foreach($sklblFiles as $file){
            $file->setStatus(4);
            $entityManager->persist($file);
            $entityManager->flush();
        }

        $sklblOrder->setSklblStatus(7);
        $entityManager->persist($sklblOrder);
        $entityManager->flush();

        return $this->redirectToRoute('sklbl_step_4', [
            'order_id' => $sklblOrder->getId()
        ]);
        
    }

    #[Route('/api/step_42', name: 'api_step_42', methods:['post'] )]
    public function api_step_42(ManagerRegistry $doctrine,
    SklblFilesRepository $sklblFilesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblFxRepository $sklblFxRepository,
    SklblUploadConfigRepository $sklblUploadConfigRepository,
    EntityManagerInterface $entityManager,
    Request $request): JsonResponse
    {
        $orders = $sklblOrdersRepository->getCurrentOrders();
        $log = new SklblLogs();
        $excel = new SklblExcelService();
        foreach($orders as $order){
            // Pour chaque commande on vérifier si des demandes de génération des enregistrements ont été enregistrées
            $files = $sklblFilesRepository->getStep42FilesList($order);
            $columnList = $sklblUploadConfigRepository->findBySklblOrderActive($order);
            $countStep42FilesAGenerer = sizeof($files); 
            $errorFiles = 0;
            if($countStep42FilesAGenerer > 0){
                foreach($files as $file){
                    $fxs = $sklblFxRepository->findStep42ATransferer($file);
                    if(sizeof($fxs) > 0){
                        $filename = $excel->createNewF1($file,$columnList,$this->getParameter('sklbl_f1_directory'));
                        $excel->integrateDataInF1($fxs);
                        $excel->saveF1();
                        if($excel->getLastLine() - 1 != sizeof($fxs)){
                            $errorFiles ++;
                        }else{
                            $file->setStatus(5);
                            $entityManager->persist($file);
                            $entityManager->flush();
                        }

                    }

                }

                if($errorFiles == 0){
                    foreach($files as $file){
                        $skus = $file->getSklblSkus();
                        foreach($skus as $sku){
                            if($sklblFxRepository->updateFxStatut($sku,4)){
                                $sku->setStatus(4);
                                $entityManager->persist($sku);
                                $entityManager->flush();
                            }
                        }
                    }
                }

                // On vérifie que le traitement a été effectué jusqu'au bout
                if($errorFiles == 0){
                    foreach($files as $file){
                        $excel = new SklblExcelService();
                        $nbFxs = $sklblFxRepository->countFxLoaded($file);
                        $filename = $excel->openF1($file,$this->getParameter('sklbl_f1_directory'));
                        $nbRecords = $excel->getLastLine();
                        if($nbFxs == $nbRecords - 1){
                            echo "Succès du chargement";
                            rename($this->getParameter('sklbl_f1_directory').'/'.$filename, $this->getParameter('sklbl_f1_a_transferer_directory').'/'.$filename);
                            $file->setStatus(6);
                            $entityManager->persist($file);
                            $entityManager->flush();

                            $order->setSklblStatus(8);
                            $entityManager->persist($order);
                            $entityManager->flush();
                        }else{
                            echo "Echec du chargement";
                        }
                    }
                }
                

            }

        }

        return $this->json('Traitement terminé');

    }


    #[Route('/api/step_43', name: 'api_step_43', methods:['post'] )]
    public function api_step_43(ManagerRegistry $doctrine,
    SklblFilesRepository $sklblFilesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblOfRepository $sklblOfRepository,
    SklblSkuRepository $sklblSkuRepository,
    SklblFxRepository $sklblFxRepository,
    EntityManagerInterface $entityManager,
    Request $request): JsonResponse
    {
        $orders = $sklblOrdersRepository->getCurrentOrders();
        $log = new SklblLogs();
        $excel = new SklblExcelService();
        
        foreach($orders as $order){
            // Pour chaque commande on vérifier si des demandes de génération des enregistrements ont été enregistrées
            $files = $sklblFilesRepository->getStep43FilesList($order);
            $countStep43FilesAEnvoyer = sizeof($files); 
            $errorFiles = 0;
            if($countStep43FilesAEnvoyer > 0){
                foreach($files as $file){
                    // Appel API Azure et confirmation transmission
                    $fichierTransfere = true;
                    if($fichierTransfere){
                        $sklblSkuRepository->updateSkuFileStatut($file,5);
                        $sklblFxRepository->updateFxFileStatut($file,5);
                        $sklblFxRepository->updateFxFileSentOn($file,new DateTimeImmutable());
                        $filename = $excel->openF1($file,$this->getParameter('sklbl_f1_a_transferer_directory'));
                        rename($this->getParameter('sklbl_f1_a_transferer_directory').'/'.$filename, $this->getParameter('sklbl_f1_transfere_directory').'/'.$filename);
                        $file->setStatus(7);
                        $entityManager->persist($file);
                        $entityManager->flush();

                        $order->setSklblStatus(9);
                        $entityManager->persist($order);
                        $entityManager->flush();

                    }

                }
            }
        }
        return $this->json('Envoi terminé');
    }

    #[Route('/api/step_44', name: 'api_step_44', methods:['post'] )]
    public function api_step_44(ManagerRegistry $doctrine,
    SklblFilesRepository $sklblFilesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblOfRepository $sklblOfRepository,
    SklblSkuRepository $sklblSkuRepository,
    SklblFxRepository $sklblFxRepository,
    SklblFx2Repository $sklblFx2Repository,
    EntityManagerInterface $entityManager,
    Request $request): JsonResponse
    {
        ini_set('max_execution_time', '3600');
     
        // Etape 1: Recherche de fichiers F2 Reçu et copie dans le répertoire en traitement
        $nb_fichiers_recus = 0; 
        $nb_fichiers_en_traitement = 0; 

        $dirRecu = $this->getParameter('sklbl_f2_directory').'/';
        $dirEnTraitement = $this->getParameter('sklbl_f2_en_traitement_directory').'/';
        $dirTraite = $this->getParameter('sklbl_f2_traite_directory').'/';
        $dirArchive = $this->getParameter('sklbl_f2_archive_directory').'/';

        if ($handle = opendir($dirRecu)) {
            while (($fileRecu = readdir($handle)) !== false){
                if (!in_array($fileRecu, array('.', '..')) && !is_dir($dirRecu.$fileRecu)) 
                {
                $nb_fichiers_recus++;
                if (copy($dirRecu.$fileRecu, $dirArchive.$fileRecu)) {
                        echo "Archivage du fichier $fileRecu...\n";
                        if(rename($dirRecu.$fileRecu, $dirEnTraitement.$fileRecu)) {
                            echo "Déplacement du fichier $fileRecu de recu à En_traitement...\n";
                        }
                    }
                }
            }
        }
        // Etape 2: Recherche de fichiers F2 a traiter
        if ($handle = opendir($dirEnTraitement)) {
            while (($fileEnTraitement = readdir($handle)) !== false){
                if (!in_array($fileEnTraitement, array('.', '..')) && !is_dir($dirEnTraitement.$fileEnTraitement)) 
                $nb_fichiers_en_traitement++;
            }
        }



        if($nb_fichiers_en_traitement > 0){
            if ($handle = opendir($dirEnTraitement)) {
                while (($file = readdir($handle)) !== false){
                    if (!in_array($file, array('.', '..')) && !is_dir($dirEnTraitement.$file)){
                        $checkFx2Records = false;
                        $checkFxAssociated = false;
                        $checkFx2Associated = false;
                        $checkFxFx2Associated = false;
                        
                        $excelService = new SklblExcelService();
                        $records = $excelService->step44($dirEnTraitement.$file);
                        $of = $sklblOfRepository->findOneByCode($records[1][2]);
                        $order = $of->getSklblOrder();

                        if($order){
                            $order->setSklblStatus(10);
                            $entityManager->persist($order);
                            $entityManager->flush();
                        }
                        
                        $sklblFile = $sklblFilesRepository->findFx2File($of,$file);
                        if(!$sklblFile){
                            $sklblFile = new SklblFiles();
                            $sklblFile->setClientFilename($file);
                            $sklblFile->setCategorie('fx2');
                            $sklblFile->setSklblOf($of);
                            $sklblFile->setSklblOrder($order);
                            $sklblFile->setStatus(1);
                            $sklblFile->setLigne(0);
                            $entityManager->persist($sklblFile);
                            $entityManager->flush();
                        }
                        
                        
                        if($sklblFile){
                            foreach($records as $row){
                                $fx2 = $sklblFx2Repository->find($row[6]);
                                if(!$fx2){
                                    $fx2 = new SklblFx2();
                                    $fx2->setId($row[6]);
                                    $fx2->setUniqueId($row[6]);
                                    $fx2->setSklblOf($of);
                                    $fx2->setOfNum(intval($row[2]));
                                    $fx2->setSku($row[7]);
                                    $fx2->setSkuTisse($row[8]);
                                    $fx2->setRedirectUrl($row[0]);
                                    $fx2->setCreatedAt(new DateTimeImmutable());
                                    $fx2->setGenScalabelOn(new DateTimeImmutable($row[3]));
                                    $fx2->setSklblFilename($file);
                                    $fx2->setSklblFile($sklblFile);
                                    $fx2->setStatus(5);
                                    $entityManager->persist($fx2);
                                    $entityManager->flush();


                                    $fx = $sklblFxRepository->findFreeFx($fx2);
                                    if($fx){
                                        $fx->setUniqueId($row[6]);
                                        $fx->setRedirectUrl($row[0]);
                                        $fx->setReceivedOn(new DateTimeImmutable());
                                        $fx->setStatus(6);
                                        $entityManager->persist($fx);
                                        $fx2->setStatus(6);
                                        $fx2->setSklblCustfile($fx->getSklblFile());
                                        $fx2->setSklblFx($fx);
                                        $fx2->setDealsOn(new DateTimeImmutable());
                                        $entityManager->persist($fx2);
                                        $entityManager->flush();
                                    }
                                    
                                }else{
                                    $fx = $sklblFxRepository->findFreeFx($fx2);
                                    if($fx){
                                        $fx->setUniqueId($row[6]);
                                        $fx->setRedirectUrl($row[0]);
                                        $fx->setReceivedOn(new DateTimeImmutable());
                                        $fx->setStatus(6);
                                        $entityManager->persist($fx);
                                        $fx2->setStatus(6);
                                        $fx2->setSklblCustfile($fx->getSklblFile());
                                        $fx2->setSklblFx($fx);
                                        $fx2->setDealsOn(new DateTimeImmutable());
                                        $entityManager->persist($fx2);
                                        $entityManager->flush();
                                    }    
                                }
                                
                            }
                            // On compare nbRecords du fichier FX2 avec bdd
                            if($excelService->getLastLine() && $sklblFx2Repository->countFx2Loaded($file)){
                                $checkFx2Records = true;
                                echo "Success: fx2 nb enregistrements identiques\b";
                            }else{
                                echo "Error: fx2 nb enregistrements différents\b";
                            }

                            // On vérifie ensuite que chaque enregistrement de FX2 a été associé
                            if($sklblFx2Repository->countFx2NotAssociated($of) == 0){
                                $checkFx2Associated = true;
                                echo "Success: Tous les fx2 sont associés\b";
                            }else{
                                echo "Error: Certains fx2 ne sont pas associés\b";
                            }

                            // On vérifie ensuite que chaque enregistrement de FX a été associé
                            if($sklblFxRepository->countFxNotAssociated($of) == 0){
                                $checkFxAssociated = true;
                                echo "Success: Tous les fx2 sont associés\b";
                            }else{
                                echo "Error: Certains fx2 ne sont pas associés\b";
                            }

                            // On vérifie enfin que le nombre d'fx2 et le nombre d'FX est identique
                            if($sklblFxRepository->countFxAssociated($of) == $sklblFx2Repository->countFx2Associated($of)){
                                $checkFxFx2Associated = true;
                                echo "Success: Tous les fx2 et fx sont associés\b";
                            }else{
                                echo "Error: Certains fx2 et fx ne sont pas associés\b";
                            }

                            if($checkFx2Records){
                                $sklblFile->setStatus(3);
                                $entityManager->persist($sklblFile);
                                $entityManager->flush();
                                rename($dirEnTraitement.$fileRecu, $dirTraite.$fileRecu);

                                $order->setSklblStatus(11);
                                $entityManager->persist($order);
                                $entityManager->flush();
                            }

                        }

                    }
                        
                }
            }
            return $this->json('F2: enregistrements traités');
        }else{
            return $this->json('F2: Aucun fichier reçu');
        }

        
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
