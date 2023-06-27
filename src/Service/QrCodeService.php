<?php

namespace App\Service;

use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;

class QrCodeService
{

    public function generateQrCode($lot):array
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($lot)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
 
        $qrCodes = [];
        $qrCodes['img'] = $writer->write($qrCode)->getDataUri();
        $qrCodes['simple'] = $writer->write(
                                $qrCode,
                                null
                            )->getDataUri();
        return $qrCodes;
    }
}
