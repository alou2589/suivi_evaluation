<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MaintenanceService
{
    protected FilesystemOperator $sftpStorage;

    public function __construct(FilesystemOperator $sftpStorage)
    {
        $this->sftpStorage=$sftpStorage;
    }

    public function uploadFicheMaintenanceInfo(UploadedFile $file): string
    {
        $fileName= uniqid('fiche_').'.'.$file->guessExtension();
        $remotePath= (string) 'fiches_maintenance/'.$fileName;

        $stream= fopen($file->getPathname(), 'r');
        $this->sftpStorage->writeStream($remotePath, $stream);
        fclose($stream);

        return $remotePath;
    }

    public function getMaintenanceInfoStream(string $path): mixed
    {
        if ($this->sftpStorage->fileExists($path)) {
            return $this->sftpStorage->readStream($path);
        }

        return null;
    }
}
