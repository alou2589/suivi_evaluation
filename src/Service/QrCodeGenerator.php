<?php

namespace App\Service;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\RoundBlockSizeMode;
use App\Service\AesEncryptDecrypt;

class QrCodeGenerator
{

    protected $aesEncryptDecrypt;

    public function __construct(AesEncryptDecrypt $aesEncryptDecrypt)
    {
        $this->aesEncryptDecrypt = $aesEncryptDecrypt;
    }

    public function generateQrCode($recherche, $nom_qr): string
    {
        $url = "info_perso/";
        $path = dirname(__DIR__, 2) . '/public/assets/';
        $result = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: ($this->aesEncryptDecrypt->encrypt((string)$url . $recherche)),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            backgroundColor: new Color(0, 153, 51),
            logoPath:(string) $path.'img/logo.png',
            logoResizeToHeight: 100,
            logoResizeToWidth: 100,
            logoPunchoutBackground: true,
        )->build();
        $namePng =(string) $nom_qr .'.png';
        $result->saveToFile(path: (string) $path.'qr_codes/'.$namePng);
        return $result->getDataUri();
    }
}

