<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoService
{
    protected FilesystemOperator $sftpStorage;

    public function __construct(FilesystemOperator $sftpStorage)
    {
        $this->sftpStorage=$sftpStorage;
    }

    public function uploadPhoto(UploadedFile $file): string
    {
        $fileName= uniqid('photo_').'.'.$file->guessExtension();
        $remotePath= (string) 'photo_agents/'.$fileName;

        $stream= fopen($file->getPathname(), 'r');
        $this->sftpStorage->writeStream($remotePath, $stream);
        fclose($stream);

        return $remotePath;
    }
}
