<?php

namespace App\Service;

use App\Entity\Orders;
use App\Entity\OrderSup;
use App\Entity\ReceivSupDetails;
use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelService
{



    public function generateMouv(ReceivSupDetails $receivSupDetails,OrderSup $order){
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $order = $receivSupDetails->getOrderSup();
        $fournisseur = $order->getFournisseur();
        $article = $order->getArticle();
        

        // Création des en-têtes
        $activeWorksheet->setCellValue('A3', 'FICHE');
        $activeWorksheet->setCellValue('B3', 'DOSSIER');
        $activeWorksheet->setCellValue('C3', 'ETABLISSEMENT');
        $activeWorksheet->setCellValue('D3', 'REF_PIECE');
        $activeWorksheet->setCellValue('E3', 'TYPE_TIERS');
        $activeWorksheet->setCellValue('F3', 'TYPE_PIECE');
        $activeWorksheet->setCellValue('G3', 'TYPE_PIECE_FINALE');
        $activeWorksheet->setCellValue('H3', 'NO_PIECE');
        $activeWorksheet->setCellValue('I3', 'NO_PIECE_FINALE');
        $activeWorksheet->setCellValue('J3', 'CODE_TIERS');
        $activeWorksheet->setCellValue('K3', 'CODE_OP');
        $activeWorksheet->setCellValue('L3', 'DEPOT');
        $activeWorksheet->setCellValue('M3', 'ENRNO');
        $activeWorksheet->setCellValue('N3', 'NO_SOUS_LIGNE');
        $activeWorksheet->setCellValue('O3', 'DESIGNATION');
        $activeWorksheet->setCellValue('P3', 'REF_FOURNISSEUR');
        $activeWorksheet->setCellValue('Q3', 'QUANTITE');
        $activeWorksheet->setCellValue('R3', 'VTLNO');
        $activeWorksheet->setCellValue('S3', 'EMPLACEMENT');
        $activeWorksheet->setCellValue('T3', 'SERIE');
        $activeWorksheet->setCellValue('U3', 'QUANTITE_VTL');
        $activeWorksheet->setCellValue('V3', 'ERREUR');

        // Création des libellés de lignes
        $activeWorksheet->setCellValue('A4', 'IPAR');
        $activeWorksheet->setCellValue('A5', 'ENT');
        $activeWorksheet->setCellValue('A6', 'MOUV');
        $activeWorksheet->setCellValue('A7', 'MVTL');

        // Données des paramètres
        $activeWorksheet->setCellValue('B4', $order->getDossier());
        $activeWorksheet->setCellValue('C4', $order->getEtablissement());
        $activeWorksheet->setCellValue('D4', $receivSupDetails->getNumBlFou()); //ref Pièce
        $activeWorksheet->setCellValue('E4', 'F');
        $activeWorksheet->setCellValue('F4', '2');
        $activeWorksheet->setCellValue('G4', '3');
        $activeWorksheet->setCellValue('H4', $order->getOrderNum());//no_piece
        $activeWorksheet->setCellValue('I4', '');//no_piece_finale


        // Données des paramètres
        $activeWorksheet->setCellValue('J5', $fournisseur->getCode());
        $activeWorksheet->setCellValue('K5', 'F');
        $activeWorksheet->setCellValue('L5', '1');

        // Données Mouv
        $activeWorksheet->setCellValue('M6', $order->getRecordNum());
        $activeWorksheet->setCellValue('N6', $order->getOrderLine());
        $activeWorksheet->setCellValue('O6', $order->getDesignation());
        $activeWorksheet->setCellValue('P6', $receivSupDetails->getNumBlFou());//ref fournisseur
        $activeWorksheet->setCellValue('Q6', $receivSupDetails->getQteRecue());

        // Données Mvtl
        $activeWorksheet->setCellValue('R7', $order->getNoVentilation()); //N° de ventilation
        $activeWorksheet->setCellValue('S7', 'S01HR'); // Emplacement
        $spreadsheet->getActiveSheet()
            ->getCell('T7')
            ->setValueExplicit(
                $receivSupDetails->getBatchNum(),
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
            );//N° de lot
        $activeWorksheet->setCellValue('U7', $receivSupDetails->getQteRecue()); // Qte ventilation

        $writer = new Xlsx($spreadsheet);

        $source_file = 'FA_'.$order->getOrderNum()."-".date('YmdHis').".xlsx";
        $writer->save("/var/www/gateway/remote/".$source_file);

        $ftp_server = 'hera';
        $ftp_user_name = 'edintitest';
        $ftp_user_pass = 'eDuj@uj422PJU72036dzq&';
        $ftp = ftp_connect($ftp_server);
        $login_result = ftp_login($ftp, $ftp_user_name, $ftp_user_pass);
        $destination_file = $source_file;

        // Vérification de la connexion
        if ((!$ftp) || (!$login_result)) {
            echo "La connexion FTP a échoué !";
            echo "Tentative de connexion au serveur $ftp_server pour l'utilisateur $ftp_user_name";
            exit;
        } else {
            echo "Connexion au serveur $ftp_server, pour l'utilisateur $ftp_user_name";
        }

        // Chargement d'un fichier
        ftp_pasv($ftp,true);
        $upload = ftp_put($ftp, '/'.$destination_file, "/var/www/gateway/remote/".$source_file, FTP_BINARY);

        // Vérification du status du chargement
        if (!$upload) {
            echo "Le chargement FTP a échoué!";
        } else {
            echo "Chargement de $source_file vers $ftp_server en tant que $destination_file";
        }

        // Fermeture de la connexion FTP
        ftp_close($ftp);
    }



}