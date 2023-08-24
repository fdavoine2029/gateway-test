<?php

namespace App\Controller\Sklbl;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sklbl\SklblEmballage;
use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblFx;
use App\Entity\Sklbl\SklblFx2;
use App\Entity\Sklbl\SklblLisageConfig;
use App\Entity\Sklbl\SklblLogs;
use App\Entity\Sklbl\SklblModel;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\SklblRubrique;
use App\Entity\Sklbl\sklblSku;
use App\Entity\Sklbl\SklblUploadConfig;
use App\Form\Sklbl\SklblFilesFormType;
use App\Form\Sklbl\SklblLisageFormType;
use App\Form\Sklbl\SklblMajorationFormType;
use App\Form\Sklbl\SklblUploadConfigForm2Type;
use App\Form\Sklbl\SklblUploadConfigFormType;
use App\Form\Sklbl\SklblUploadModelFormType;
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
use App\Repository\Sklbl\SklblLisageConfigRepository;
use App\Repository\Sklbl\SklblLogsRepository;
use App\Repository\Sklbl\SklblModelRepository;
use App\Repository\Sklbl\SklblOfRepository;
use App\Repository\Sklbl\SklblOrdersRepository;
use App\Repository\Sklbl\SklblRubriqueRepository;
use App\Repository\Sklbl\sklblSkuRepository;
use App\Repository\Sklbl\SklblStructureRepository;
use App\Repository\Sklbl\SklblUploadConfigRepository;
use App\Service\sklbl\SklblExcelService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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



    /*************************************************Affichage de la liste des commandes****************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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

    /*************************************************Affichage des logs*********************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/
    #[Route('/show_logs/{order_id}', name: 'show_logs')]
    public function show_logs(
        SklblLogsRepository $sklblLogsRepository,
        SklblOrdersRepository $sklblOrdersRepository,
        $order_id
    ): Response{
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $logs = $sklblLogsRepository->findBySklblOrder($sklblOrder);
        
        return $this->render('sklbl/scalabel/logs.html.twig', [
            'controller_name' => 'ScalabelController',
            'sklblOrder' => $sklblOrder,
            'logs' => $logs
        ]);
    }

    /*************************************************Page de configuration******************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/


    public function initConf(SklblOrders $sklblOrder
    ,SklblStructureRepository $sklblStructureRepository
    ,EntityManagerInterface $entityManager){
            $excel = new SklblExcelService();
            $order = $sklblStructureRepository->find(1);
            $variable = new SklblUploadConfig();
            $variable->setSklblOrder($sklblOrder);
            $variable->setCategorie('variable');
            $variable->setSklblStructure($order);
            $variable->setLabel('Qté commandée');
            $variable->setFormat('numerique');
            $variable->setUniqueValue(0);
            $variable->setOrderNum(1);

            $variable->setCustomer(1);
            $variable->setF1(0);
            $variable->setCustomerCsv('A');
            $variable->setCustomerCsvNum($excel->alphaNum2('A'));                                                
            $variable->setF2(0);
            $variable->setF3(0);
            $variable->setF4(0);
            $variable->setF5(0);
            $variable->setLisage(0);

            $uniq_id = $sklblStructureRepository->find(2);
            $variable2 = new SklblUploadConfig();
            $variable2->setSklblOrder($sklblOrder);
            $variable2->setCategorie('variable');
            $variable2->setSklblStructure($uniq_id);
            $variable2->setLabel('Id etiquette');
            $variable2->setFormat('texte');
            $variable2->setUniqueValue(1);
            $variable2->setOrderNum(1);

            $variable2->setCustomer(0);
            $variable2->setF1(1);
            $variable2->setF1Csv('A');
            $variable->setF1CsvNum($excel->alphaNum2('A'));    
            $variable2->setF2(0);
            $variable2->setF3(0);
            $variable2->setF4(0);
            $variable2->setF5(0);
            $variable2->setLisage(0);


            $entityManager->persist($variable);
            $entityManager->persist($variable2);
            $entityManager->flush($variable);
            $entityManager->flush($variable2);
            
    }

    #[Route('/step_conf_add_model/{order_id}/{name}', name: 'step_conf_add_model')]
    public function step_conf_add_model(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        SklblStructureRepository $sklblStructureRepository,
        SklblModelRepository $sklblModelRepository, 
        EntityManagerInterface $entityManager,
        $order_id,
        $name){
            try{
                $sklblOrder = $sklblOrdersRepository->find($order_id);
                $nb_column = $sklblUploadConfigRepository->countVariable($sklblOrder);
                $model = $sklblModelRepository->findOneByName($name);
                if(!$model){
                    $model = new SklblModel();
                    $model->setName($name);
                    $entityManager->persist($model);
                    $entityManager->flush($model);

                    $excel = new SklblExcelService();
                    $order = $sklblStructureRepository->find(1);
                    $variable = new SklblUploadConfig();
                    $variable->setCategorie('variable');
                    $variable->setSklblStructure($order);
                    $variable->setLabel('Qté commandée');
                    $variable->setFormat('numerique');
                    $variable->setUniqueValue(0);
                    $variable->setOrderNum(1);
                    $variable->setSklblModel($model);

                    $variable->setCustomer(1);
                    $variable->setF1(0);
                    $variable->setCustomerCsv('A');
                    $variable->setCustomerCsvNum($excel->alphaNum2('A'));                                                
                    $variable->setF2(0);
                    $variable->setF3(0);
                    $variable->setF4(0);
                    $variable->setF5(0);
                    $variable->setLisage(0);



                    $uniq_id = $sklblStructureRepository->find(2);
                    $variable2 = new SklblUploadConfig();
                    $variable2->setCategorie('variable');
                    $variable2->setSklblStructure($uniq_id);
                    $variable2->setLabel('Id etiquette');
                    $variable2->setFormat('texte');
                    $variable2->setUniqueValue(1);
                    $variable2->setOrderNum(1);
                    $variable2->setSklblModel($model);
                    $variable2->setCustomer(0);
                    $variable2->setF1(1);
                    $variable2->setF1Csv('A');
                    $variable->setF1CsvNum($excel->alphaNum2('A'));    
                    $variable2->setF2(0);
                    $variable2->setF3(0);
                    $variable2->setF4(0);
                    $variable2->setF5(0);
                    $variable2->setLisage(0);


                    $entityManager->persist($variable);
                    $entityManager->persist($variable2);
                    $entityManager->flush($variable);
                    $entityManager->flush($variable2);
                }
                $this->addFlash('success','Modèle ajouté avec succès');

                //************ */ Debut ajout log*****************
                $log = new SklblLogs();
                $log->setJobName('Configuration: Ajout modèle');
                $log->setMode('Manuel');
                $log->setMessage('Ajout du modèle '.$name);
                $log->setSklblOrder($sklblOrder);
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setProgress(100);
                $log->setStatus(1);
                $log->setUser($this->getUser());
                $entityManager->persist($log);
                $entityManager->flush($log);
                //************ */ Fin ajout log*****************

                return $this->redirectToRoute('sklbl_step_conf', [
                    'order_id' => $order_id,
                    'model_id' => $model->getId(),
                    'nbColumn' => $nb_column,
                    'action' => 'none'
                ]);
            } catch (Exception $e) {
                $this->addFlash('error','Erreur lors de l\'ajout du modèle');

                //************ */ Debut ajout log*****************
                $log = new SklblLogs();
                $log->setJobName('Configuration: Ajout modèle');
                $log->setMode('Manuel');
                $log->setMessage('Ajout du modèle '.$name);
                $log->setSklblOrder($sklblOrder);
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setProgress(0);
                $log->setStatus(0);
                $log->setUser($this->getUser());
                $entityManager->persist($log);
                $entityManager->flush($log);
                //************ */ Fin ajout log*****************

                return $this->redirectToRoute('sklbl_step_conf', [
                    'order_id' => $order_id,
                    'nbColumn' => $nb_column,
                    'action' => 'none'
                ]);
            }

    }

    #[Route('/step_conf_delete_model/{order_id}/{id}', name: 'step_conf_delete_model')]
    public function step_conf_delete_model(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        SklblModelRepository $sklblModelRepository, 
        EntityManagerInterface $entityManager,
        $order_id,
        $id){
            try{
                $sklblOrder = $sklblOrdersRepository->find($order_id);
                $nb_column = $sklblUploadConfigRepository->countVariable($sklblOrder);
                $model = $sklblModelRepository->find($id);
                $name = $model->getName();
                if($model){
                    $configs = $sklblUploadConfigRepository->findBySklblModel($model);
                    foreach($configs as $config){
                        $entityManager->remove($config);
                        $entityManager->flush($config);
                    }
                    $entityManager->remove($model);
                    $entityManager->flush($model);
                }
                $this->addFlash('success','Modèle supprimé succès');

                //************ */ Debut ajout log*****************
                $log = new SklblLogs();
                $log->setJobName('Configuration: Suppression modèle');
                $log->setMode('Manuel');
                $log->setMessage('Suppression du modèle '.$name);
                $log->setSklblOrder($sklblOrder);
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setProgress(100);
                $log->setStatus(1);
                $log->setUser($this->getUser());
                $entityManager->persist($log);
                $entityManager->flush($log);
                //************ */ Fin ajout log*****************

                return $this->redirectToRoute('sklbl_step_conf', [
                    'order_id' => $order_id,
                    'nbColumn' => $nb_column,
                    'action' => 'none'
                ]);
            } catch (Exception $e) {
                $this->addFlash('error','Erreur lors de la suppression du modèle');

                //************ */ Debut ajout log*****************
                $log = new SklblLogs();
                $log->setJobName('Configuration: Suppression modèle');
                $log->setMode('Manuel');
                $log->setMessage('Ajout du modèle '.$name);
                $log->setSklblOrder($sklblOrder);
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setProgress(0);
                $log->setStatus(0);
                $log->setUser($this->getUser());
                $entityManager->persist($log);
                $entityManager->flush($log);
                //************ */ Fin ajout log*****************

                return $this->redirectToRoute('sklbl_step_conf', [
                    'order_id' => $order_id,
                    'nbColumn' => $nb_column,
                    'action' => 'none'
                ]);
            }

    }

    #[Route('/step_conf_save_model/{order_id}/{id}', name: 'step_conf_save_model')]
    public function step_conf_save_model(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        SklblModelRepository $sklblModelRepository, 
        EntityManagerInterface $entityManager,
        $order_id,
        $id){
            try{
                $sklblOrder = $sklblOrdersRepository->find($order_id);
                $nb_column = $sklblUploadConfigRepository->countVariable($sklblOrder);
                $model = $sklblModelRepository->find($id);
                $name = $model->getName();
                if($model){
                    // On supprime l'ancienne config
                    $old_configs = $sklblUploadConfigRepository->findBySklblModel($model);
                    foreach($old_configs as $old_config){
                        $entityManager->remove($old_config);
                        $entityManager->flush($old_config);
                    }


                    // On sauvegarde le nouveau modèle

                    $new_configs = $sklblUploadConfigRepository->findBySklblOrder($order_id);
                    foreach($new_configs as $new_config){

                        $variable = new SklblUploadConfig();
                        $variable->setCategorie($new_config->getCategorie());
                        $variable->setSklblStructure($new_config->getSklblStructure());
                        $variable->setLabel($new_config->getLabel());
                        $variable->setFormat($new_config->getFormat());
                        $variable->setUniqueValue($new_config->getUniqueValue());
                        $variable->setOrderNum($new_config->getOrderNum());

                        $variable->setCustomer($new_config->getCustomer());
                        $variable->setF1($new_config->getF1());   
                        $variable->setF2($new_config->getF2());
                        $variable->setF3($new_config->getF3());
                        $variable->setF4($new_config->getF4());
                        $variable->setF5($new_config->getF5());
                        $variable->setLisage($new_config->getLisage());

                        $variable->setCustomerCsv($new_config->getCustomerCsv());
                        $variable->setCustomerCsvNum($new_config->getCustomerCsvNum()); 
                        $variable->setF1Csv($new_config->getF1Csv());
                        $variable->setF1CsvNum($new_config->getF1CsvNum()); 
                        $variable->setF2Csv($new_config->getF2Csv());
                        $variable->setF2CsvNum($new_config->getF2CsvNum()); 
                        $variable->setF3Csv($new_config->getF3Csv());
                        $variable->setF3CsvNum($new_config->getF3CsvNum()); 
                        $variable->setF4Csv($new_config->getF4Csv());
                        $variable->setF4CsvNum($new_config->getF4CsvNum()); 
                        $variable->setF5Csv($new_config->getF5Csv());
                        $variable->setF5CsvNum($new_config->getF5CsvNum()); 
                        $variable->setLisageCsv($new_config->getLisageCsv());
                        $variable->setLisageCsvNum($new_config->getLisageCsvNum());

                        $variable->setSklblModel($model);
                        $entityManager->persist($variable);
                        $entityManager->flush($variable);

                    }

                }
                $this->addFlash('success','Modèle sauvegardé avec succès');

                //************ */ Debut ajout log*****************
                $log = new SklblLogs();
                $log->setJobName('Configuration: Sauvegarde modèle');
                $log->setMode('Manuel');
                $log->setMessage('Sauvegarde du modèle '.$name);
                $log->setSklblOrder($sklblOrder);
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setProgress(100);
                $log->setStatus(1);
                $log->setUser($this->getUser());
                $entityManager->persist($log);
                $entityManager->flush($log);
                //************ */ Fin ajout log*****************

                return $this->redirectToRoute('sklbl_step_conf', [
                    'order_id' => $order_id,
                    'nbColumn' => $nb_column,
                    'action' => 'none'
                ]);
            } catch (Exception $e) {
                $this->addFlash('error','Erreur lors de la sauvegarde du modèle');

                //************ */ Debut ajout log*****************
                $log = new SklblLogs();
                $log->setJobName('Configuration: Sauvegarde modèle');
                $log->setMode('Manuel');
                $log->setMessage('Sauvegarde du modèle '.$name);
                $log->setSklblOrder($sklblOrder);
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setProgress(0);
                $log->setStatus(0);
                $log->setUser($this->getUser());
                $entityManager->persist($log);
                $entityManager->flush($log);
                //************ */ Fin ajout log*****************

                return $this->redirectToRoute('sklbl_step_conf', [
                    'order_id' => $order_id,
                    'nbColumn' => $nb_column,
                    'action' => 'none'
                ]);
            }
    }

    #[Route('/step_conf/{order_id}/{action}/{model_id?0}/{load_model?0}', name: 'step_conf')]
    public function step_conf(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblUploadConfigRepository $sklblUploadConfigRepository,
        SklblStructureRepository $sklblStructureRepository,
        SklblModelRepository $sklblModelRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        $order_id,
        $model_id,
        $load_model,
        $action)
    {
        $excel = new SklblExcelService();
        $sklblOrder = $sklblOrdersRepository->find($order_id);
        $client = $sklblOrder->getClient();
        $orderNum = $sklblOrder->getOrderNum();
        // Si aucune variable n'est enregistrée, on initialise la conf
        
        $nb_column = $sklblUploadConfigRepository->countVariable($sklblOrder);
        if($nb_column == 0){
            $this->initConf($sklblOrder,$sklblStructureRepository,$entityManager);
            $nb_column++;
        }

        //Si on charge un modèle
        if($load_model == 1){
            $model = $sklblModelRepository->find($model_id);
            $variables = $sklblUploadConfigRepository->getModelVariables($model);
            $ind = 1;
            foreach($variables as $variable){
                $items['variable'.$ind] = $variable;
                $ind ++;
            }
            $nb_column = $ind;
        }else{
            // sinon on prend la conf de la commande
            if($nb_column > 0){
                $variables = $sklblUploadConfigRepository->getVariables($sklblOrder);
                $ind = 1;
                foreach($variables as $variable){
                    $items['variable'.$ind] = $variable;
                    $ind ++;
                }
                $nb_column = $ind;
            }
        }

        
        

        if($action == 'add' || ($action == 'none') && $nb_column == 0){
            $nb_column ++;
            $variable = new SklblUploadConfig();
            $items['variable'.$nb_column] = $variable;  
        }

        //Construction du formulaire de modèle
        if($model_id > 0){
            $model = $sklblModelRepository->find($model_id);
            $repo = $sklblUploadConfigRepository->findOneBySklblModel($model);
        }else{
            $repo = new SklblUploadConfig();
        }
        
        $formModel = $this->createForm(SklblUploadModelFormType::class, $repo);


        //Construction du formulaire de la structure des champs
     
        
        $form = $this->createFormBuilder($items);
        $numvar = 1;
        foreach($items as $item){
            $form->add('variable'.$numvar,SklblUploadConfigFormType::class, [
                'data_class' => SklblUploadConfig::class,
            ]);
            $numvar ++;
        }
        $form = $form->getForm();


        //Construction du formulaire de l'association de F1 et F2
        $variablesAssoc = $sklblUploadConfigRepository->findAssoc($sklblOrder);
        if(!$variablesAssoc){
            $variablesAssoc = new SklblUploadConfig();
        }
        $form2 = $this->createForm(SklblUploadConfigForm2Type::class, $variablesAssoc);


        
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try{

                    $num = 1;
                    $old_configs = $sklblUploadConfigRepository->findBySklblOrder($sklblOrder);
                    foreach($old_configs as $old_config){
                        if($old_config->getCategorie() == 'variable'){
                            $entityManager->remove($old_config);
                            $entityManager->flush($old_config);
                        }
                        
                    }

                    foreach($form as $record){
                        if(!$record->get('delete')->isClicked()){

                            $variable = new SklblUploadConfig();
                            $variable->setSklblOrder($sklblOrder);
                            $variable->setCategorie('variable');
                            $variable->setSklblStructure($record->get('sklblStructure')->getData());
                            $variable->setLabel($record->get('label')->getData());
                            $variable->setFormat($record->get('format')->getData());
                            $variable->setUniqueValue($record->get('uniqueValue')->getData());
                            $variable->setOrderNum($num);

                            $variable->setCustomer($record->get('customer')->getData());
                            $variable->setF1($record->get('f1')->getData());   
                            $variable->setF2($record->get('f2')->getData());
                            $variable->setF3($record->get('f3')->getData());
                            $variable->setF4($record->get('f4')->getData());
                            $variable->setF5($record->get('f5')->getData());
                            $variable->setLisage($record->get('lisage')->getData());

                            $variable->setCustomerCsv($record->get('customerCsv')->getData());
                            $variable->setCustomerCsvNum($excel->alphaNum2($record->get('customerCsv')->getData())); 
                            $variable->setF1Csv($record->get('f1Csv')->getData());
                            $variable->setF1CsvNum($excel->alphaNum2($record->get('f1Csv')->getData())); 
                            $variable->setF2Csv($record->get('f2Csv')->getData());
                            $variable->setF2CsvNum($excel->alphaNum2($record->get('f2Csv')->getData())); 
                            $variable->setF3Csv($record->get('f3Csv')->getData());
                            $variable->setF3CsvNum($excel->alphaNum2($record->get('f3Csv')->getData())); 
                            $variable->setF4Csv($record->get('f4Csv')->getData());
                            $variable->setF4CsvNum($excel->alphaNum2($record->get('f4Csv')->getData())); 
                            $variable->setF5Csv($record->get('f5Csv')->getData());
                            $variable->setF5CsvNum($excel->alphaNum2($record->get('f5Csv')->getData())); 
                            $variable->setLisageCsv($record->get('lisageCsv')->getData());
                            $variable->setLisageCsvNum($excel->alphaNum2($record->get('lisageCsv')->getData()));

                            $entityManager->persist($variable);
                            $entityManager->flush($variable);
                            
                        }
                        $num ++;

                        
                    }

                    if($sklblOrder->getSklblStatus() < 1){
                        $sklblOrder->setSklblStatus(1);
                        $entityManager->persist($sklblOrder);
                        $entityManager->flush($sklblOrder);
                    }
                    
                    $this->addFlash('success','Configuration de la commande ' .$sklblOrder->getOrderNum() . ' sauvegardée avec succès');

                    //************ */ Debut ajout log*****************
                    $log = new SklblLogs();
                    $log->setJobName('Configuration: Enregistrement configuration');
                    $log->setMode('Manuel');
                    $log->setMessage('Enregistrement de la configuration de la commande '.$orderNum);
                    $log->setSklblOrder($sklblOrder);
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setProgress(100);
                    $log->setStatus(1);
                    $log->setUser($this->getUser());
                    $entityManager->persist($log);
                    $entityManager->flush($log);
                    //************ */ Fin ajout log*****************

                    if($request->request->get('conf_submit') == 'add'){
                        return $this->redirectToRoute('sklbl_step_conf', [
                            'order_id' => $order_id,
                            'nbColumn' => $nb_column,
                            'action' => 'add'
                        ]);
                        
                    }else{
                        return $this->redirectToRoute('sklbl_step_conf', [
                            'order_id' => $order_id,
                            'nbColumn' => $nb_column,
                            'action' => 'none'
                        ]);
                    }
                } catch (Exception $e) {
                    $this->addFlash('error','Erreur lors de la sauvegarde du modèle');
    
                    //************ */ Debut ajout log*****************
                    $log = new SklblLogs();
                    $log->setJobName('Configuration: Enregistrement configuration');
                    $log->setMode('Manuel');
                    $log->setMessage('Enregistrement de la configuration de la commande '.$orderNum);
                    $log->setSklblOrder($sklblOrder);
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setProgress(0);
                    $log->setStatus(0);
                    $log->setUser($this->getUser());
                    $entityManager->persist($log);
                    $entityManager->flush($log);
                    //************ */ Fin ajout log*****************
    
                    return $this->redirectToRoute('sklbl_step_conf', [
                        'order_id' => $order_id,
                        'nbColumn' => $nb_column,
                        'action' => 'none'
                    ]);
                }


            }
            
            $form2->handleRequest($request);
            if ($form2->isSubmitted() && $form2->isValid()) {
                try{
                    $variablesAssoc = $sklblUploadConfigRepository->findAssoc($sklblOrder);
                    if(!$variablesAssoc){
                        $variablesAssoc = new SklblUploadConfig();
                    }
                    $structure = $form2->get('id')->getData();

                    $variablesAssoc->setSklblOrder($sklblOrder);
                    $variablesAssoc->setCategorie('assoc');
                    $variablesAssoc->setSklblStructure($structure->getSklblStructure());
                    $variablesAssoc->setValue($structure->getSklblStructure()->getName());
                    $variablesAssoc->setLabel($structure->getLabel());
                    $variablesAssoc->setFormat('text');
                    $variablesAssoc->setOrderNum(1);
                    $variablesAssoc->setCustomer(0);
                    $variablesAssoc->setF1(0);   
                    $variablesAssoc->setF2(0);   
                    $variablesAssoc->setF3(0);   
                    $variablesAssoc->setF4(0);   
                    $variablesAssoc->setF5(0);   
                    $variablesAssoc->setLisage(0);   
                    $variablesAssoc->setUniqueValue(0);
                    $entityManager->persist($variablesAssoc);
                    $entityManager->flush($variablesAssoc);


                    $this->addFlash('success','Association F1/F2 sauvegardée');

                    //************ */ Debut ajout log*****************
                    $log = new SklblLogs();
                    $log->setJobName('Configuration: Enregistrement configuration');
                    $log->setMode('Manuel');
                    $log->setMessage('Enregistrement de l\'association F1/F2 de la commande '.$orderNum);
                    $log->setSklblOrder($sklblOrder);
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setProgress(100);
                    $log->setStatus(1);
                    $log->setUser($this->getUser());
                    $entityManager->persist($log);
                    $entityManager->flush($log);
                    //************ */ Fin ajout log*****************
                    return $this->redirectToRoute('sklbl_step_conf', [
                        'order_id' => $order_id,
                        'nbColumn' => $nb_column,
                        'action' => 'none'
                    ]);
                } catch (Exception $e) {
                    $this->addFlash('error','Erreur lors de l\'association F1/F2 ');
    
                    //************ */ Debut ajout log*****************
                    $log = new SklblLogs();
                    $log->setJobName('Configuration: Enregistrement configuration');
                    $log->setMode('Manuel');
                    $log->setMessage('Enregistrement de l\'association F1/F2 de la commande '.$orderNum);
                    $log->setSklblOrder($sklblOrder);
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setProgress(0);
                    $log->setStatus(0);
                    $log->setUser($this->getUser());
                    $entityManager->persist($log);
                    $entityManager->flush($log);
                    //************ */ Fin ajout log*****************
    
                    return $this->redirectToRoute('sklbl_step_conf', [
                        'order_id' => $order_id,
                        'nbColumn' => $nb_column,
                        'action' => 'none'
                    ]);
                }

            }


        return $this->render('sklbl/scalabel/step_conf.html.twig', [
            'sklblOrder' => $sklblOrder,
            'client' => $client,
            'formModel' => $formModel->createView(),
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'nbColumn' => $nb_column
        ]);
        
        

    }

    /**************************************Etape1: Chargement du fichier client**************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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
        $columns = $sklblUploadConfigRepository->findCustomerVariables($sklblOrder);
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
                    if($sklblOrder->getSklblStatus() < 1){
                        $sklblOrder->setSklblStatus(1);
                        $entityManager->persist($sklblOrder);
                        $entityManager->flush();
                    }
                    

                    $this->addFlash('success','Fichier ' . $newFilename . ' soumis avec succès');

                    //************ */ Debut ajout log*****************
                    $log = new SklblLogs();
                    $log->setJobName('step_1: Demande de chargement fichier client');
                    $log->setMode('Manuel');
                    $log->setMessage('Succès de la demande de chargement du fichier '.$newFilename);
                    $log->setSklblOrder($sklblOrder);
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setProgress(0);
                    $log->setStatus(1);
                    $log->setUser($this->getUser());
                    $entityManager->persist($log);
                    $entityManager->flush($log);
                    //************ */ Fin ajout log*****************

                    return $this->redirectToRoute('sklbl_step_1', [
                        'order_id' => $order_id
                    ]);
                } catch (FileException $e) {
                    $this->addFlash('error','Erreur chargement fichier');

                    //************ */ Debut ajout log*****************
                    $log = new SklblLogs();
                    $log->setJobName('step_1: Demande de chargement fichier client');
                    $log->setMode('Manuel');
                    $log->setMessage('Erreur demande de chargement du fichier '.$newFilename);
                    $log->setSklblOrder($sklblOrder);
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setProgress(0);
                    $log->setStatus(0);
                    $log->setUser($this->getUser());
                    $entityManager->persist($log);
                    $entityManager->flush($log);
                    //************ */ Fin ajout log*****************

                    return $this->redirectToRoute('sklbl_step_1', [
                        'order_id' => $order_id
                    ]);

                }
            }

        }


        return $this->render('sklbl/scalabel/step1.html.twig', [
            'sklblOrder' => $sklblOrder,
            'columns' => $columns,
            'client' => $client,
            'skus' => $skulist,
            'columnLigne' => $columnLigne,
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
        //************ */ Fin ajout log*****************
        foreach($orders as $order){
            // Pour chaque commande on vérifier si des demandes de transfert ont été enregistrées
            // On compte également les fichiers déja transférés pour vérifier à la fin que tous les fichiers sont chargés et ok
            $files = $sklblFilesRepository->getFileAIntegrerList($order);
            $countFilesACharger = sizeof($files); 

            // On récupère les colonnes
            
            $columnList = $sklblUploadConfigRepository->findCustomerVariables($order);

            // Si des demandes de transfert sont détectées
            // Lancement de l'intégration

            if($countFilesACharger > 0){
                // On parcourt chaque fichier à charger
                foreach($files as $file){
                    // Initialistion du log
                    $log = new SklblLogs();
                    $log->setJobName('step_1: Chargement du fichier client');
                    $log->setMode('Auto');
                    $log->setMessage('Chargement du fichier '.$file->getClientFilename());
                    $log->setSklblOrder($order);
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setProgress(0);
                    $log->setStatus(2);
                    $log->setUser($this->getUser());
                    $entityManager->persist($log);
                    $entityManager->flush($log);

                    $filePresent = true;
                    $fileContientEnregistrements = true;
                    $path = $this->getParameter('sklbl_fichier_client_a_charger').'/'.$file->getClientFilename();

                    
                    $ligne = $file->getLigne();
                    $excelService = new SklblExcelService();

                    // On vérifie que le fichier est présent
                    try {
                        $records = $excelService->integrateCustomerFile($order,$path);
                    } catch (Exception $e) {
                        $log->setMessage('Erreur de chargement du fichier '.$file->getClientFilename().'. Le fichier est absent.');
                        $log->setStatus(0);
                        $entityManager->persist($log);
                        $entityManager->flush();
                        $entityManager->remove($file);
                        $entityManager->flush();
                        $filePresent = false;
                    }


                    // On vérifie que le fichier contient des enregistrements
                    if($filePresent){
                        if(sizeof($records) == 0){
                            $log->setMessage('Erreur de chargement du fichier '.$file->getClientFilename(). '. Le fichier est vide.' );
                            $log->setStatus(0);
                            $entityManager->persist($log);
                            $entityManager->flush();
                            $entityManager->remove($file);
                            $entityManager->flush();
                            $fileContientEnregistrements = false;
                        }
                    }



                    if($filePresent && $fileContientEnregistrements){
                        // On commence le chargement du fichier
                        $log->setMessage('Début import de ' . $file->getClientFilename() . '...');
                        $log->setStatus(2);
                        $entityManager->persist($log);
                        $entityManager->flush();


                        $sku_index = 1;
                        $countUploadSuccess = 0;
                        $countUploadErrors = 0;

                        /*
                        
                        On en est là. Tu prend le total de row que tu divises par 10.
                        Ensuite tu compares le n° de la ligne à ce Num (que tu additionnes pour le prochain step)
                        Tu en déduit l'avancement par rapport au total tous les 10%
                        
                        
                        */





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
                                        if($paramcol->getCustomerCsv() == $column){
                                            switch ($paramcol->getSklblStructure()->getName()) {
                                                case 'order_qte':
                                                    try {
                                                        $sku->setOrderQte(intval($row[$column_index]));
                                                    } catch (Exception $e) {
                                                        $nbcolonneErrors++;
                                                    }
                                                    break;
                                                default:
                                                    $sku->setDataColumn($paramcol->getSklblStructure()->getName(),$row[$column_index]);
                                            
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
                           /* $log = new SklblLogs();
                            $log->setExecutedAt(new DateTimeImmutable());
                            $log->setJobName('Step_1: Import fichier client');
                            $log->setMessage('L import de ' . $file->getClientFilename() . ' a été réalisé avec succès');
                            $log->setStatus(1);
                            $entityManager->persist($log);
                            $entityManager->flush();*/

                            rename($path, $this->getParameter('sklbl_fichier_client_success').'/'.$file->getClientFilename());
                            $file->setStatus(1);
                            $entityManager->persist($file);
                            $entityManager->flush();
                        }else{
                          /*  $log = new SklblLogs();
                            $log->setExecutedAt(new DateTimeImmutable());
                            $log->setJobName('Step_1: Import fichier client');
                            $log->setMessage('Il y a ' . $countUploadErrors . ' erreur(s) dans l import de  ' . $file->getClientFilename());
                            $log->setStatus(-1);*/
                            

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

                /*$log = new SklblLogs();
                $log->setExecutedAt(new DateTimeImmutable());
                $log->setJobName('Step_1: Import fichier client');
                $log->setMessage('Step_1: Erreur d import pour la commande '. $order->getOrderNum());
                $log->setStatus(-1);*/

                $order->setSklblStatus(-1);
                $entityManager->persist($order);
                $entityManager->flush();
            }else{
                $filesSuccess = $sklblFilesRepository->countStep1FilesSuccess($order);
                if($filesSuccess > 0){
                    /*$log = new SklblLogs();
                    $log->setExecutedAt(new DateTimeImmutable());
                    $log->setJobName('Step_1: Import fichier client');
                    $log->setMessage('Step_1: Succès import pour la commande '. $order->getOrderNum());
                    $log->setStatus(1);*/
                    if($order->getSklblStatus() < 2){
                        $order->setSklblStatus(2);
                        $entityManager->persist($order);
                        $entityManager->flush();
                    }
                    
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



       /**************************************Etape2: Calcul des quantités**************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/


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
        $columnList = $sklblUploadConfigRepository->findCustomerVariables($order_id);
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
            
            if($sklblOrder->getSklblStatus() < 3){
                $sklblOrder->setSklblStatus(3);
            }
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


     /**************************************Etape3: On rappatrie l'OF**************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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
        if($sklblOrder->getSklblStatus() < 4){
            $sklblOrder->setSklblStatus(4);
            $entityManager->persist($sklblOrder);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('sklbl_step_4', [
            'order_id' => $order_id
        ]);
    }


     /**************************************Etape4: On Génère le F1 **********************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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
            $columnList = $sklblUploadConfigRepository->findF1F2Variables($sklblOrder);
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
                'count_sku' => $countSku
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
        if($sklblOrder->getSklblStatus() < 5){
            $sklblOrder->setSklblStatus(5);
            $entityManager->persist($sklblOrder);
            $entityManager->flush();
        }


        return $this->redirectToRoute('sklbl_step_4', [
            'order_id' => $sklblOrder->getId()
        ]);
    }

    #[Route('/api/step_41', name: 'api_step_41', methods:['post'] )]
    public function api_step_41(ManagerRegistry $doctrine,
    SklblFilesRepository $sklblFilesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblSkuRepository $sklblSkuRepository,
    SklblUploadConfigRepository $sklblUploadConfigRepository,
    EntityManagerInterface $entityManager,
    Request $request): JsonResponse
    {
        // On récupère
        $orders = $sklblOrdersRepository->getCurrentOrders();
        $log = new SklblLogs();
        foreach($orders as $order){
            // On vérifier la configuration pour la commande

            // Pour chaque commande on vérifier si des demandes de génération des enregistrements ont été enregistrées
            $files = $sklblFilesRepository->getStep4FileAGenererList($order);
            $columnList = $sklblUploadConfigRepository->findFx1VariablesToGenerate($order);
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

                            foreach($columnList as $paramcol){
                                $fx->setDataColumn($paramcol->getSklblStructure()->getName(),$sku->getDataColumn($paramcol->getSklblStructure()->getName()));
                            }
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



      /**************************************Etape42: On génère le CSV de F1 ****************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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
             $columnList = $sklblUploadConfigRepository->findF1Variables($order);
             $countStep42FilesAGenerer = sizeof($files); 
             $errorFiles = 0;
             if($countStep42FilesAGenerer > 0){
                 foreach($files as $file){
                    $fxs = $sklblFxRepository->findStep42ATransferer($file,$columnList);
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
                             if( $order->getSklblStatus() < 8){
                                $order->setSklblStatus(8);
                                $entityManager->persist($order);
                                $entityManager->flush();
                            }
                             
                         }else{
                             echo "Echec du chargement";
                         }
                     }
                 }
                 
 
             }
 
         }
 
         return $this->json('Traitement terminé');
 
     }


      /**************************************Etape43: On demande le transfert Azure **********************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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
        if( $sklblOrder->getSklblStatus() < 7){
            $sklblOrder->setSklblStatus(7);
            $entityManager->persist($sklblOrder);
            $entityManager->flush();
        }
        
 
         return $this->redirectToRoute('sklbl_step_4', [
             'order_id' => $sklblOrder->getId()
         ]);
         
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

                        if( $order->getSklblStatus() < 9){
                            $order->setSklblStatus(9);
                            $entityManager->persist($order);
                            $entityManager->flush();
                        }

                        

                    }

                }
            }
        }
        return $this->json('Envoi terminé');
    }



      /**************************************Etape44: Attente reception Azure et intégration ************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

    #[Route('/api/step_44', name: 'api_step_44', methods:['post'] )]
    public function api_step_44(ManagerRegistry $doctrine,
    SklblFilesRepository $sklblFilesRepository,
    SklblOrdersRepository $sklblOrdersRepository,
    SklblOfRepository $sklblOfRepository,
    SklblSkuRepository $sklblSkuRepository,
    SklblFxRepository $sklblFxRepository,
    SklblFx2Repository $sklblFx2Repository,
    SklblStructureRepository $sklblStructureRepository,
    SklblUploadConfigRepository $sklblUploadConfigRepository,
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
                        $checkOf = false; 
                        $checkOrder = false; 
                        $checkFx2Records = false;
                        $checkFxAssociated = false;
                        $checkFx2Associated = false;
                        $checkFxFx2Associated = false;
                        
                        $excelService = new SklblExcelService();
                        $records = $excelService->step44($dirEnTraitement.$file);
                        $of = $sklblOfRepository->findOneByCode(substr($file,3,6));

                        // On contrôle que le fichier est bien associé à un OF et à une commande
                        if($of){
                            $checkOf = true; 
                            $order = $of->getSklblOrder();
                            if($order){
                                $checkOrder = true; 
                                $order->setSklblStatus(10);
                                $entityManager->persist($order);
                                $entityManager->flush();

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

                            }
                        }
                        // Etape de réception des enregistrements de F2 dans la base de données
                        if($checkOf && $checkOrder){
                            
                            $keyAssoc = $sklblUploadConfigRepository->findAssoc($order);
                            $structure = $sklblStructureRepository->findOneByName('unique_id');
                            $uniqIdCol = $sklblUploadConfigRepository->findUniqIdF2($order,$structure);
                            $colsToLoad = $sklblUploadConfigRepository->findF2Variables($order);
                            if($uniqIdCol){
                                foreach($records as $row){
                                    // On récupère la colonne idantifiante de l'étiquette
                                    $fx2 = $sklblFx2Repository->find($row[$uniqIdCol->getF2CsvNum() - 1]);
                                    if(!$fx2){
                                        $fx2 = new SklblFx2();
                                        $fx2->setId($row[$uniqIdCol->getF2CsvNum() - 1]);
                                        $fx2->setUniqueId($row[$uniqIdCol->getF2CsvNum() - 1]);
                                        $fx2->setSklblOf($of);
                                        $fx2->setCreatedAt(new DateTimeImmutable());
                                        $fx2->setGenScalabelOn(new DateTimeImmutable());
                                        $fx2->setSklblFilename($file);
                                        $fx2->setSklblFile($sklblFile);
                                        $fx2->setStatus(5);
                                        foreach($colsToLoad as $col){
                                            $fx2->setDataColumn($col->getSklblStructure()->getName(),$row[$col->getF2CsvNum() - 1]);
                                        }

                                        $entityManager->persist($fx2);
                                        $entityManager->flush();
    
    
                                        /*$fxtab = $sklblFxRepository->findFreeFx($fx2,$keyAssoc);
                                        if(sizeof($fxtab) == 1){
                                            $fx = $sklblFxRepository->find($fxtab[0]['id']);
                                            if($fx){
                                                $fx->setUniqueId($row[$uniqIdCol->getF2CsvNum() - 1]);
                                                $fx->setReceivedOn(new DateTimeImmutable());
                                                foreach($colsToLoad as $col){
                                                    $fx->setDataColumn($col->getSklblStructure()->getName(),$row[$col->getF2CsvNum() - 1]);
                                                }
                                                $fx->setStatus(6);
                                                $entityManager->persist($fx);
                                                $fx2->setStatus(6);
                                                $fx2->setSklblCustfile($fx->getSklblFile());
                                                $fx2->setSklblFx($fx);
                                                $fx2->setDealsOn(new DateTimeImmutable());
                                                $entityManager->persist($fx2);
                                                $entityManager->flush();
                                            }
                                        }*/
                                        
                                        
                                    }/*else{
                                        $fxtab = $sklblFxRepository->findFreeFx($fx2,$keyAssoc);
                                        if(sizeof($fxtab) == 1){
                                            $fx = $sklblFxRepository->find($fxtab[0]['id']);
                                            if($fx){
                                                $fx->setUniqueId($row[$uniqIdCol->getF2CsvNum() - 1]);
                                                $fx->setReceivedOn(new DateTimeImmutable());
                                                foreach($colsToLoad as $col){
                                                    $fx->setDataColumn($col->getSklblStructure()->getName(),$row[$col->getF2CsvNum() - 1]);
                                                }
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
                                    }*/

                                }
                                
                            }

                        }

                        // On compare nbRecords du fichier FX2 avec bdd. Si le nombre d'enregistrement est identique on est ok
                        if($excelService->getLastLine() && $sklblFx2Repository->countFx2Loaded($file)){
                            $checkFx2Records = true;
                            echo "Success: fx2 nb enregistrements identiques\b";
                        }else{
                            echo "Error: fx2 nb enregistrements différents\b";
                        }


                        // On rapproche chaque enregistrement F2 avec F1





                        

                       /* // On vérifie ensuite que chaque enregistrement de FX2 a été associé
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
                        }*/

                        if($checkFx2Records){
                            $sklblFile->setStatus(3);
                            $entityManager->persist($sklblFile);
                            $entityManager->flush();
                            if($fileRecu){
                                rename($dirEnTraitement.$fileRecu, $dirTraite.$fileRecu);
                            }
                            if( $order->getSklblStatus() < 11){
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


     /**************************************Etape45: Rapprochement de F1 et F2 *************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

     #[Route('/api/step_45', name: 'api_step_45', methods:['post'] )]
     public function api_step_45(ManagerRegistry $doctrine,
     SklblFilesRepository $sklblFilesRepository,
     SklblOrdersRepository $sklblOrdersRepository,
     SklblOfRepository $sklblOfRepository,
     SklblSkuRepository $sklblSkuRepository,
     SklblFxRepository $sklblFxRepository,
     SklblFx2Repository $sklblFx2Repository,
     SklblStructureRepository $sklblStructureRepository,
     SklblUploadConfigRepository $sklblUploadConfigRepository,
     EntityManagerInterface $entityManager,
     Request $request): JsonResponse
     {
 
        ini_set('max_execution_time', '3600');

        // Etape 1: On recherche les fichiers F2 réceptionnés
        $f2Files = $sklblFilesRepository->getF2FilesReceived();
        foreach($f2Files as $f2File){
            // Etape2: On identifie la commande et la clé d'association de la configuration
            $order = $f2File->getSklblOrder();
            $keyAssoc = $sklblUploadConfigRepository->findAssoc($order);
            $colsToLoad = $sklblUploadConfigRepository->findF2SupVariables($order);

            // Etape3: On met à jour le status de la commande et du fichier F2 comme étant en cour de rapprochement
            $order->setSklblStatus(12);
            $f2File->setStatus(4);
            $entityManager->persist($order);
            $entityManager->persist($f2File);
            $entityManager->flush();

            // Etape4: On récupère les enregistrements fx2 non rapprochés du fichier
            $fx2s = $sklblFx2Repository->findFx2sNotAssociated($f2File);
            foreach($fx2s as $fx2){
                $fxtab = $sklblFxRepository->findFreeFx($fx2,$keyAssoc);
                if(sizeof($fxtab) == 1){
                    $fx = $sklblFxRepository->find($fxtab[0]['id']);
                    // Etape5: On rapproche fx2 et fx
                    if($fx){
                        $fx->setUniqueId($fx2->getUniqueId());
                        $fx->setReceivedOn(new DateTimeImmutable());
                        foreach($colsToLoad as $col){
                            $fx->setDataColumn($col->getSklblStructure()->getName(),$fx2->getDataColumn($col->getSklblStructure()->getName()));
                        }
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
            /* // On vérifie ensuite que chaque enregistrement de FX2 a été associé
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
                if($fileRecu){
                    rename($dirEnTraitement.$fileRecu, $dirTraite.$fileRecu);
                }

                $order->setSklblStatus(11);
                $entityManager->persist($order);
                $entityManager->flush();
            }*/
        }
        return $this->json('F2: enregistrements traités');
 
     }



    #[Route('/step_5/{order_id}/{nb_column}', name: 'step_5')]
    public function step_5(
        SklblOrdersRepository $sklblOrdersRepository,
        SklblOfRepository $sklblOfRepository,
        SklblFilesRepository $sklblFilesRepository,
        SklblSkuRepository $sklblSkuRepository,
        SklblFxRepository $sklblFxRepository,
        SklblLisageConfigRepository $sklblLisageConfigRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        int $order_id,
        int $nb_column): Response
        {
            $sklblOrder = $sklblOrdersRepository->find($order_id);
            $variables = $sklblLisageConfigRepository->getVariablesAtisser($sklblOrder);
            $items = array();
            if(sizeof($variables) > 0){
                $ind = 1;
                foreach($variables as $variable){
                    $items['variable'.$ind] = $variable;
                    $ind ++;
                }
                if($nb_column > $ind){
                    $variable = new SklblLisageConfig();
                    $variable->setCategorie('variable');
                    $variable->setNum($ind);
                    $items['variable'.$ind] = new SklblLisageConfig();
                }
            }else{
                $ind = 1;
                while($ind <= $nb_column){
                    $variable = new SklblLisageConfig();
                    $variable->setCategorie('variable');
                    $variable->setNum($ind);
                    $items['variable'.$ind] = new SklblLisageConfig();
                    $ind ++;
                }
                
            }


            $form = $this->createFormBuilder($items);
            $numvar = 1;
            foreach($items as $item){
                $form->add('variable'.$numvar,SklblLisageFormType::class, [
                    'data_class' => SklblLisageConfig::class,
                ]);
                $numvar ++;
            }
            $form = $form->getForm();
            

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $num = 1;
                
                foreach($form as $record){
                    $variable = $sklblLisageConfigRepository->findConf($sklblOrder,$record->get('sklblStructure')->getData(),'variable');
                    if(!$variable){
                        $variable = new SklblLisageConfig();
                    }
                    $variable->setSklblOrder($sklblOrder);
                    $variable->setSklblStructure($record->get('sklblStructure')->getData());
                    $variable->setName($record->get('sklblStructure')->getData()->getName());
                    $variable->setLabel($record->get('label')->getData());
                    $variable->setCategorie('variable');
                    $variable->setNum($record->get('num')->getData());
                    $variable->setFormat($record->get('format')->getData());
                    $variable->setValue($record->get('value')->getData());

                    $entityManager->persist($variable);
                    $entityManager->flush($variable);
                    $num ++;
                }
                if($request->request->get('lisage_submit_button') == 'add'){
                    return $this->redirectToRoute('sklbl_step_5', [
                        'order_id' => $order_id,
                        'nb_column' => $nb_column + 1
                    ]);
                    
                }
                if($request->request->get('lisage_submit_button') == 'delete'){
                    return $this->redirectToRoute('sklbl_step_5', [
                        'order_id' => $order_id,
                        'nb_column' => $nb_column - 1
                    ]);
                    
                }
            }


            return $this->render('sklbl/scalabel/step5.html.twig', [
                'sklblOrder' => $sklblOrder,
                'variableExist' => 1,
                'form' => $form->createView(),
                'nbColumn' => $nb_column
            ]);
            
            return $this->render('sklbl/scalabel/step5.html.twig', [
                'sklblOrder' => $sklblOrder,
                'variableExist' => 0,
                'nbColumn' => $nb_column
            ]);
            
    }

    

    

    


    

    #[Route('/conf_reception/{of_id}', name: 'conf_reception')]
    public function conf_reception(
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

        
        if( $sklblOrder->getSklblStatus() < 12){
            $sklblOrder->setSklblStatus(12);
            $entityManager->persist($sklblOrder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sklbl_step_5', [
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

 
    /*************************************************Import des emballages********** ********************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/


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


    /*************************************************Import des rubriques********** ********************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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

     /*************************************************Import des commandes Scalabel ********************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/

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


    /*************************************************Import des OFs Scalabel **************************************
     *                                                                                                              *
     *                                                                                                              *
     ****************************************************************************************************************/


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
