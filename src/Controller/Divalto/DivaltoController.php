<?php

namespace App\Controller\Divalto;

use App\Entity\Articles;
use App\Entity\Clients;
use App\Entity\Divalto\ART;
use App\Entity\Fournisseurs;
use App\Entity\OrderSup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticlesRepository;
use App\Repository\ClientsRepository;
use App\Repository\Divalto\ARTRepository;
use App\Repository\Divalto\CLIRepository;
use App\Repository\Divalto\DivaltoRepository;
use App\Repository\Divalto\FOURepository;
use App\Repository\Divalto\MOUVRepository;
use App\Repository\FournisseursRepository;
use App\Repository\OrderSupRepository;
use App\Repository\ReceivSupDetailsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api', name: 'api_')]
class DivaltoController extends AbstractController
{
    #[Route('/articles', name: 'articles_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $articles = $doctrine
            ->getRepository(ART::class)
            ->findAll();
   
        $data = [];
   
        foreach ($articles as $article) {
           $data[] = [
               'DOS' => $article->getDOS(),
               'REF' => $article->getREF(),
               'DES' => $article->getDES(),
           ];
        }
   
        return $this->json($data);
    }

    #[Route('/articles/show/{ref}', name: 'articles_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine,DivaltoRepository $divalto, string $ref): JsonResponse
    {
        $article = $divalto->findArticle($ref);
        if (!$article) {
            return $this->json('No article found for ref ' . $ref, 404);
        }
        return $this->json($article);
    }

    #[Route('/articlesF/import/{dossier}/{days}', name: 'articlesF_import', methods:['post'] )]
    public function articlesF_import(ManagerRegistry $doctrine,
    DivaltoRepository $divalto,
    EntityManagerInterface $entityManager, 
    ArticlesRepository $articlesRepository,
    string $dossier,
    int $days): JsonResponse
    {
        $articles = $divalto->getArticlesFournisseur($dossier,$days);
        $currentDate = new DateTimeImmutable();
        $count = 0;
        foreach ($articles as $Divaltoarticle) {
            $import = false;
            
            $ref_import_date = new DateTimeImmutable($Divaltoarticle['REF_IMPORT_DATE']);
            $article = $articlesRepository->find($Divaltoarticle['ART_ID']);
            if($article){
                if($ref_import_date > $article->getUpdatedAt()){
                    $import = true;
                }
            }else{
                $import = true;
            }

            if($import){
                if(!$article){
                    $article = new Articles();
                    $article->setId($Divaltoarticle['ART_ID']);
                }
                $article->setDossier($Divaltoarticle['DOS']);     
                $article->setRef($Divaltoarticle['REF']);
                $article->setDesignation($Divaltoarticle['DES']);
                $article->setAbccod($Divaltoarticle['ABCCOD']);
                if($Divaltoarticle['GICOD'] == 3){
                    $article->setLot(1);
                }else{
                    $article->setLot(0);
                }
                $article->setCreatedAt($currentDate);
                $article->setUpdatedAt($currentDate);
                $entityManager->persist($article);
                $entityManager->flush();
                $count ++;
            }
        }
        return $this->json($count . ' articles importés');
    }


    #[Route('/articlesC/import/{dossier}/{days}', name: 'articlesC_import', methods:['post'] )]
    public function articlesC_import(ManagerRegistry $doctrine,
    DivaltoRepository $divalto,
    EntityManagerInterface $entityManager, 
    ArticlesRepository $articlesRepository,
    string $dossier,
    int $days): JsonResponse
    {
        $articles = $divalto->getArticlesClient($dossier,$days);
        $currentDate = new DateTimeImmutable();
        $count = 0;
        foreach ($articles as $Divaltoarticle) {
            $import = false;
            
            $ref_import_date = new DateTimeImmutable($Divaltoarticle['REF_IMPORT_DATE']);
            $article = $articlesRepository->find($Divaltoarticle['ART_ID']);
            if($article){
                if($ref_import_date > $article->getUpdatedAt()){
                    $import = true;
                }
            }else{
                $import = true;
            }

            if($import){
                if(!$article){
                    $article = new Articles();
                    $article->setId($Divaltoarticle['ART_ID']);
                }
                $article->setDossier($Divaltoarticle['DOS']);     
                $article->setRef($Divaltoarticle['REF']);
                $article->setDesignation($Divaltoarticle['DES']);
                $article->setAbccod($Divaltoarticle['ABCCOD']);
                if($Divaltoarticle['GICOD'] == 3){
                    $article->setLot(1);
                }else{
                    $article->setLot(0);
                }
                $article->setCreatedAt($currentDate);
                $article->setUpdatedAt($currentDate);
                $entityManager->persist($article);
                $entityManager->flush();
                $count ++;
            }
        }
        return $this->json($count . ' articles importés');
    }


    #[Route('/clients/import/{dossier}/{days}', name: 'clients_import', methods:['post'] )]
    public function clients_import(ManagerRegistry $doctrine,
    DivaltoRepository $divalto,
    EntityManagerInterface $entityManager, 
    ClientsRepository $clientsRepository,
    string $dossier,
    int $days): JsonResponse
    {
        $clients = $divalto->getClients($dossier,$days);
        $currentDate = new DateTimeImmutable();
        $count = 0;
        foreach ($clients as $DivaltoCli) {
            $import = false;
            $ref_import_date = new DateTimeImmutable($DivaltoCli['REF_IMPORT_DATE']);
            $client = $clientsRepository->find($DivaltoCli['CLI_ID']);
            if($client){
                if($ref_import_date > $client->getUpdatedAt()){
                    $import = true;
                }
            }else{
                $import = true;
            }
            if($import){
                if(!$client){
                    $client = new Clients();
                    $client->setId($DivaltoCli['CLI_ID']);
                }
                $client->setDossier($DivaltoCli['DOS']);     
                $client->setCode($DivaltoCli['TIERS']);
                $client->setName($DivaltoCli['NOM']);
                $client->setCountry($DivaltoCli['PAY']);
                $client->setCreatedAt($currentDate);
                $client->setUpdatedAt($currentDate);
                $entityManager->persist($client);
                $entityManager->flush();
                $count ++;
            }
        }
        return $this->json($count . ' clients importés');
    }




    #[Route('/fournisseurs/import/{dossier}/{days}', name: 'fournisseurs_import', methods:['post'] )]
    public function fournisseurs_import(ManagerRegistry $doctrine,
    DivaltoRepository $divalto, 
    EntityManagerInterface $entityManager, 
    FournisseursRepository $fournisseursRepository,
    string $dossier,
    int $days): JsonResponse
    {
        $fournisseurs = $divalto->getFournisseurs($dossier,$days);
        $currentDate = new DateTimeImmutable();
        $count = 0;
        foreach ($fournisseurs as $DivaltoFou) {
            $import = false;
            $ref_import_date = new DateTimeImmutable($DivaltoFou['REF_IMPORT_DATE']);
            $fournisseur = $fournisseursRepository->find($DivaltoFou['FOU_ID']);
            if($fournisseur){
                if($ref_import_date > $fournisseur->getUpdatedAt()){
                    $import = true;
                }
            }else{
                $import = true;
            }
            if($import){
                if(!$fournisseur){
                    $fournisseur = new Fournisseurs();
                    $fournisseur->setId($DivaltoFou['FOU_ID']);
                }
                $fournisseur->setDossier($DivaltoFou['DOS']);     
                $fournisseur->setCode($DivaltoFou['TIERS']);
                $fournisseur->setName($DivaltoFou['NOM']);
                $fournisseur->setCountry($DivaltoFou['PAY']);
                $fournisseur->setTrspdays($DivaltoFou['TRANSJRNB']);
                $fournisseur->setCreatedAt($currentDate);
                $fournisseur->setUpdatedAt($currentDate);
                $entityManager->persist($fournisseur);
                $entityManager->flush();
                $count ++;
            }
        }
        return $this->json($count . ' fournisseurs importés');
    }


    #[Route('/commandes_fournisseurs/import/{dossier}/{days}', name: 'commandes_fournisseurs_import', methods:['post'] )]
    public function commandes_fournisseurs_import(ManagerRegistry $doctrine,
    DivaltoRepository $divalto,  
    EntityManagerInterface $entityManager, 
    FournisseursRepository $fournisseursRepository,
    ArticlesRepository $articlesRepository,
    OrderSupRepository $orderSupRepository,
    ReceivSupDetailsRepository $receivRepository,
    string $dossier,
    int $days): JsonResponse
    {
        $outOfSync = $orderSupRepository->findOutOfSync();
        $commandes = $divalto->getCommandesFournisseur($dossier,$days);
        $currentDate = new DateTimeImmutable();
        $count = 0;
        foreach ($commandes as $DivaltoCmd) {
            // Mise à jour des commandes
            $import_cmd = false;
            $created_at = new DateTimeImmutable($DivaltoCmd['USERCRDH']);
            $udpated_at = new DateTimeImmutable($DivaltoCmd['USERMODH']);
            $commande = $orderSupRepository->find($DivaltoCmd['MOUV_ID']);
            if($commande){
                if($commande->getUpdatedAt() < $udpated_at){
                    $import_cmd = true;
                }
            }else{
                $import_cmd = true;
            }
            if($import_cmd){
                if(!$commande){
                    $commande = new OrderSup();
                    $commande->setId($DivaltoCmd['MOUV_ID']);
                    $commande->setSync(1);
                }
                $article = $articlesRepository->findOneByRef($DivaltoCmd['REF']);
                $fournisseur = $fournisseursRepository->findOneByCode($DivaltoCmd['TIERS']);
                if($article && $fournisseur){
                    $commande->setArticle($article);
                    $commande->setFournisseur($fournisseur);
                    $commande->setDossier($DivaltoCmd['DOS']);
                    $commande->setEtablissement($DivaltoCmd['ETB']);
                    $commande->setOrderNum($DivaltoCmd['CDNO']);
                    $commande->setNoVentilation($DivaltoCmd['VTLNO']);
                    $commande->setOrderLine($DivaltoCmd['CDLG']);
                    $commande->setRecordNum($DivaltoCmd['ENRNO']);
                    $commande->setOrderDate(new DateTimeImmutable($DivaltoCmd['CDDT']));
                    $commande->setDesignation($DivaltoCmd['DES']);
                    if(!$DivaltoCmd['SALCOD']){
                        $salcod = '';
                    }else{
                        $salcod = $DivaltoCmd['SALCOD'];
                    }
                    $commande->setBuyer($salcod);
                    $commande->setOrderQte($DivaltoCmd['CDQTE']);
                    $commande->setToDeliverQte($DivaltoCmd['REFQTE']);
                    $commande->setUnit($DivaltoCmd['REFUN']);
                    $commande->setAmount($DivaltoCmd['MONT']);
                    $commande->setCurrency($DivaltoCmd['DEV']);
                    $commande->setSref1($DivaltoCmd['SREF1']);
                    $commande->setSref2($DivaltoCmd['SREF2']);
                    $commande->setDelay(new DateTimeImmutable($DivaltoCmd['DELDT']));
                    $commande->setTrans($DivaltoCmd['TRANSJRNB']);
                    $commande->setDeliveryPlace($DivaltoCmd['DEPO']);
                    $commande->setDeliveryNote($DivaltoCmd['BLNO']);
                    $commande->setBlLine($DivaltoCmd['BLLG']);
                    $commande->setBatchNum($DivaltoCmd['SERIE']);
                    $commande->setReceivQte($DivaltoCmd['BLQTE']);
                    $commande->setComment($DivaltoCmd['VS_COMMENTAIRE']);
                    if($DivaltoCmd['BLQTE'] < $DivaltoCmd['CDQTE']){
                            if($DivaltoCmd['BLQTE'] == 0){
                                $commande->setStatus(0);
                            }else{
                                $commande->setStatus(1);
                            }
                    }else{
                        $commande->setStatus(3);
                    }
                    $commande->setBlmod($DivaltoCmd['VS_BLMOD']);
                    $commande->setDelayTrsp(new DateTimeImmutable($DivaltoCmd['DELAY_TRSP']));
                    $commande->setCreatedAt(new DateTimeImmutable($DivaltoCmd['USERCRDH']));
                    $commande->setUpdatedAt(new DateTimeImmutable($DivaltoCmd['USERMODH']));
                    $receiv = $receivRepository->find($DivaltoCmd['MOUV_ID']);
                    $commande->setSync(1);
                    $entityManager->persist($commande);
                    $entityManager->flush();
                    $count ++;
                }
                
            }
        }
        return $this->json($count . ' commandes fournisseurs importés');
    }

    


}
