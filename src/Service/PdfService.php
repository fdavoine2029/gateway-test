<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $dompdf;

    public function __construct() {
        $this->dompdf = new Dompdf();

    }


    public function showPdfFile($html){
        $this->dompdf->loadHtml($html);
        //$this->dompdf->setPaper('A4', 'landscape');
        
        $customPaper = array(0,0,300,150);
        $this->dompdf->setPaper($customPaper);
        
        $this->dompdf->render();
        $this->dompdf->stream("",array("Attachment" => false));
        exit(0);

    }


    public function generateBinaryPdf($html){
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->output();
    }

}
