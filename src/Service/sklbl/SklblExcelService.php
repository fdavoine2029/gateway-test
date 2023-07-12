<?php

namespace App\Service\sklbl;

use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblOf;
use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\sklblSku;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SklblExcelService
{



    public function integrateCustomerFile(SklblOrders $sklblOrder,$path,$vendorColumn,$skuColumn,$qteColumn): array
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


    public function createNewF1(SklblFiles $file,$param){
        $of = $file->getSklblOf();
        $order = $file->getSklblOrder();
        $filename = $param .'/'. $of->getCode().'-'.$order->getOrderNum().'-'.$file->getId().'-f1.csv';
        $spreadsheet = new Spreadsheet();
        $activeWorkSheet = $spreadsheet->getActiveSheet();
        $activeWorkSheet->setCellValue('A1','Hello World');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->save($filename);
        return $filename;
    }


}
