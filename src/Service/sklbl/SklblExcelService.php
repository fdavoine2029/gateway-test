<?php

namespace App\Service\sklbl;

use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblFx;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\sklblSku;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SklblExcelService
{

    private $filename;
    private $spreadsheet;
    private $activeWorkSheet;
    private $writer;
    private $reader;

    private $row = 2;

    public function integrateCustomerFile(SklblOrders $sklblOrder,$path): array
    {
        

        
        # Create a new Xls Reader
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        // Tell the reader to only read the data. Ignore formatting etc.
        $reader->setReadDataOnly(true);

        // Read the spreadsheet file.
        $spreadsheet = $reader->load($path);

        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        $data = $sheet->toArray();

        return $data;
    }

    public function num2alpha($n)
    {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r;
        return $r;
    }

    public function openF1(SklblFiles $file,$param){
        $of = $file->getSklblOf();
        $order = $file->getSklblOrder();
        $this->filename = $param .'/F1_'. $of->getCode().'-'.$order->getOrderNum().'-'.$file->getId().'.csv';
        $filename2 = '/F1_'. $of->getCode().'-'.$order->getOrderNum().'-'.$file->getId().'.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $this->spreadsheet = $reader->load($this->filename);
        $sheet = $this->spreadsheet->getSheet($this->spreadsheet->getFirstSheetIndex());
        return $filename2;
    }


    public function createNewF1(SklblFiles $file,$columnList,$param){
        $of = $file->getSklblOf();
        $order = $file->getSklblOrder();
        $this->filename = $param .'/F1_'. $of->getCode().'-'.$order->getOrderNum().'-'.$file->getId().'.csv';
        $this->spreadsheet = new Spreadsheet();
        $this->activeWorkSheet = $this->spreadsheet->getActiveSheet();
        $this->activeWorkSheet->setCellValue('A1','OF');
        $this->activeWorkSheet->setCellValue('B1','REF-NTI-PTJ');
        $this->activeWorkSheet->setCellValue('C1','REF-CLIENT-ETIQUETTE');
        $this->activeWorkSheet->setCellValue('D1','DESIGNATION-NTI-ETIQUETTE');
        $this->activeWorkSheet->setCellValue('E1','QTY-N1-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('F1','QTY-N2-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('G1','QTY-N3-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('H1','QTY-N4-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('I1','QTY-COMMANDE');
        $this->activeWorkSheet->setCellValue('J1','QTY-OF');
        $this->activeWorkSheet->setCellValue('K1','FORMAT-PAPADE');
        $this->activeWorkSheet->setCellValue('L1','TYPE-EDI');
        $this->activeWorkSheet->setCellValue('M1','LABEL-N1-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('N1','LABEL-N2-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('O1','LABEL-N3-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('P1','LABEL-N4-EMBALLAGE');
        $this->activeWorkSheet->setCellValue('Q1','SREF1');
        $this->activeWorkSheet->setCellValue('R1','SREF2');
        $this->activeWorkSheet->setCellValue('S1','NOM-PRE-LISTE-BLANCHE-1');
        $this->activeWorkSheet->setCellValue('T1','NOM-PRE-LISTE-BLANCHE-2');
        $this->activeWorkSheet->setCellValue('U1','QTY-MIN-LIVRABLE');
        $this->activeWorkSheet->setCellValue('V1','CODE-CLIENT');
        $this->activeWorkSheet->setCellValue('W1','SKU');
        $this->activeWorkSheet->setCellValue('X1','SKU_TISSE');
        foreach($columnList as $column){
            switch ($column->getNum()) {
                case 6:
                    $this->activeWorkSheet->setCellValue('Y1',$column->getColumnLabel());
                    break;
                case 7:
                    $this->activeWorkSheet->setCellValue('Z1',$column->getColumnLabel());
                    break;
                case 8:
                    $this->activeWorkSheet->setCellValue('AA1',$column->getColumnLabel());
                    break;
                case 9:
                    $this->activeWorkSheet->setCellValue('AB1',$column->getColumnLabel());
                    break;
                case 10:
                    $this->activeWorkSheet->setCellValue('AC1',$column->getColumnLabel());
                    break;
                case 11:
                    $this->activeWorkSheet->setCellValue('AD1',$column->getColumnLabel());
                    break;
                case 12:
                    $this->activeWorkSheet->setCellValue('AE1',$column->getColumnLabel());
                    break;
                case 13:
                    $this->activeWorkSheet->setCellValue('AF1',$column->getColumnLabel());
                    break;
                case 14:
                    $this->activeWorkSheet->setCellValue('AG1',$column->getColumnLabel());
                    break;
                case 15:
                    $this->activeWorkSheet->setCellValue('AH1',$column->getColumnLabel());
                    break;
            }

        }
        $this->writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($this->spreadsheet);
        $this->writer->setDelimiter(';');
        $this->writer->setEnclosure('');
        $this->writer->save($this->filename);
        return $this->filename;
    }

    public function step44($file){
        
        # Create a new Xls Reader
        $this->filename = $file;
        $this->reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        

        // Tell the reader to only read the data. Ignore formatting etc.
        $this->reader->setReadDataOnly(false);

        // Read the spreadsheet file.
        $this->spreadsheet = $this->reader->load($file);
        $this->writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($this->spreadsheet);

        $this->activeWorkSheet = $this->spreadsheet->getSheet($this->spreadsheet->getFirstSheetIndex());
        $data = $this->activeWorkSheet->toArray();
        return $data;

    }


    public function integrateDataInF1(
        array $fxs
    )
    {
        $highestRow = $this->spreadsheet->getActiveSheet()->getHighestRow();
        $this->activeWorkSheet->fromArray($fxs, NULL, 'A'.$highestRow + 1 );
    
    }

    public function getLastLine()
    {
        $highestRow = $this->spreadsheet->getActiveSheet()->getHighestRow();
        return $highestRow;
    
    }

    public function deleteRow($row)
    {
        $this->activeWorkSheet->removeRow($row);
        $this->writer->save($this->filename);
    
    }


    public function  saveF1(){
        $this->writer->save($this->filename);
    }


}
