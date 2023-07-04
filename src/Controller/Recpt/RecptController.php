<?php

namespace App\Controller\Recpt;

use App\Entity\QualityCtrl;
use App\Entity\ReceivSupDetails;
use App\Form\Recpt\receivSupDetailsFormType;
use App\Repository\ArticlesRepository;
use App\Repository\FournisseursRepository;
use App\Repository\OrderSupRepository;
use App\Repository\ReceivSupDetailsRepository;
use App\Service\ExcelService;
use App\Service\PdfService;
use App\Service\QrCodeService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;


#[Route('/recpt', name: 'recpt_')]
class RecptController extends AbstractController
{
    #[Route('/show/{hide?1}/{delay?aucun}', name: 'index')]
    public function index(OrderSupRepository $orderSupRepository,int $hide,string $delay): Response
    {
        if($delay == "aucun"){
            $date = new DateTime('now');
            $delay = $date->format('Y-m-d');
        }
        return $this->render('recpt/recpt/index.html.twig', [
            'receptions' => $orderSupRepository->selectReceptions($hide,$delay),
            'hide' => $hide,
            'delay' => $delay
        ]);
    }   

    #[Route('/add/{id}/{id_receiv?0}', name: 'details')]
    public function details(
        Request $request, 
        ReceivSupDetailsRepository $receivSupDetailsRepository,
        OrderSupRepository $orderSupRepository,
        ArticlesRepository $articlesRepository,
        FournisseursRepository $fournisseursRepository,
        QrCodeService $qrCode,
        EntityManagerInterface $em,
        ExcelService $excel,
        KernelInterface $kernel,
        int $id,
        int $id_receiv):Response
    {
        $order = $orderSupRepository->find($id);
        $receivSupDetails = $receivSupDetailsRepository->find($id_receiv);
        if(!$receivSupDetails){
            $receivSupDetails = new ReceivSupDetails();
        }
        $article = $articlesRepository->find($order->getArticle());
        $fournisseur = $fournisseursRepository->find($order->getFournisseur());
        $lot = strval($order->getOrderNum()) . strval($order->getId());

        $form = $this->createForm(receivSupDetailsFormType::class, $receivSupDetails);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($article->getAbccod() == "A"){
                $receivSupDetails->setStatus(2);
            }

            $currentDate = new DateTimeImmutable();
            $receivSupDetails->setCreatedAt($currentDate);
            $receivSupDetails->setUpdatedAt($currentDate);
            $order->setSync(0);
            $order->setStatus(2);
            $em->persist($receivSupDetails);
            $em->persist($order);
            $em->flush();

            // On génére le mouvement seulement si la marchandise est conforme
            if($receivSupDetails->getStatus() == 1){
                $excel->generateMouv($receivSupDetails,$order);
            }else{
                $qualityCtrl = new QualityCtrl();
                $qualityCtrl->setOrderSup($order);
                
            }
            
            
            
            /*$application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'Divalto:Import-order_sup',
                // (optional) define the value of command arguments
                'dossier' => 1,
                'jalon' => 1,
                // (optional) pass options to the command
            ]);

            // You can use NullOutput() if you don't need the output
            $output = new BufferedOutput();
            $application->run($input, $output);*/

            $this->addFlash('success','Réception enregistrée avec succès');
            return $this->redirectToRoute('recpt_index');
            
        }



        // Génération du QRCode;
        $qrCodes = [];
        $qrCodes = $qrCode->generateQrCode($lot);

        //return $this->render('recpt/recpt/reception.html.twig',compact('receivSup','order','article','fournisseur'),'registrationForm' => $form->createView(),);
        return $this->render('recpt/recpt/reception.html.twig',[
            'order' => $order,
            'reception' => $receivSupDetails,
            'article' => $article,
            'fournisseur' => $fournisseur,
            'qrcode' => $qrCodes, 
            'lot' => $lot,
            'receivForm' => $form->createView()
        ]);
    }

    #[Route('/generatePdf/{ref}/{lot}/{orderNum}/{numBl}', name: 'generatePdf')]
    public function generatePdf(PdfService $pdf,QrCodeService $qrCode,string $ref,string $lot,string $orderNum,string $numBl){
        // Génération du QRCode;
        $qrCodes = [];
        $qrCodes = $qrCode->generateQrCode($lot);
        $html = $this->render('recpt/recpt/papade.html.twig',[
            'ref' => $ref,
            'lot' => $lot,
            'orderNum' => $orderNum,
            'numBl' => $numBl,
            'qrcode' => $qrCodes
        ])->getContent();
        $pdf->showPdfFile($html);
    }

}
