<?php

namespace App\Service;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\RoundBlockSizeMode;
use App\Service\AesEncryptDecrypt;
use League\Flysystem\FilesystemOperator;

class QrCodeGenerator
{

    protected AesEncryptDecrypt $aesEncryptDecrypt;
    protected FilesystemOperator $filesystemOperator;

    public function __construct(AesEncryptDecrypt $aesEncryptDecrypt, FilesystemOperator $sftpStorage)
    {
        $this->aesEncryptDecrypt = $aesEncryptDecrypt;
        $this->filesystemOperator = $sftpStorage;
    }

    public function generateQrCode(string $content, string $nom_qr): string
    {
        $fileName= $nom_qr.uniqid('', true).'.png';
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $path = dirname(__DIR__, 2) . '/public/assets/';
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: ((string)$content),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            backgroundColor: new Color(0, 153, 51),
            logoPath:(string) $path.'img/logo.png',
            logoResizeToHeight: 100,
            logoResizeToWidth: 100,
        );
        $result = $builder->build();
        $result->saveToFile($tempPath);
        $stream= fopen($tempPath, 'r');
        $remotePath=(string) 'qr_codes/'.$fileName;
        $this->filesystemOperator->writeStream($remotePath, $stream);
        fclose($stream);
        unlink($tempPath); // Delete the temporary file

        return $remotePath;
    }

    public function getPublicStreamUrl(string $remotePath): ?string
    {
        return "/admin/info/perso/qr_code?path=?".urlencode($remotePath);
    }
}

