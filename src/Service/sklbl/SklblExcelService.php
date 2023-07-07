<?php

namespace App\Service\sklbl;

use App\Entity\Sklbl\SklblOrders;
use App\Entity\Sklbl\sklblSku;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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

}
