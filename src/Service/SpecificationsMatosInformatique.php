<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SpecificationsMatosInformatique
{
    protected FilesystemOperator $sftpStorage;

    public function __construct(FilesystemOperator $sftpStorage)
    {
        $this->sftpStorage=$sftpStorage;
    }

    public function uploadSpecMatosInfo(UploadedFile $file): string
    {
        $fileName= uniqid('spec_').'.'.$file->guessExtension();
        $remotePath= (string) 'spec_matos_info/'.$fileName;

        $stream= fopen($file->getPathname(), 'r');
        $this->sftpStorage->writeStream($remotePath, $stream);
        fclose($stream);

        return $remotePath;
    }

    public function getSpecMatosInfoStream(string $path): mixed
    {
        if ($this->sftpStorage->fileExists($path)) {
            return $this->sftpStorage->readStream($path);
        }

        return null;
    }
}
