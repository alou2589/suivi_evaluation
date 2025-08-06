<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocAdminService
{
    protected FilesystemOperator $sftpStorage;

    public function __construct(FilesystemOperator $sftpStorage)
    {
        $this->sftpStorage=$sftpStorage;
    }

    public function uploadDocument(UploadedFile $file): string
    {
        $fileName= uniqid('doc_').'.'.$file->guessExtension();
        $remotePath= (string) 'documents/'.$fileName;

        $stream= fopen($file->getPathname(), 'r');
        $this->sftpStorage->writeStream($remotePath, $stream);
        fclose($stream);

        return $remotePath;
    }

        public function getDocumentStream(string $path): mixed
    {
        if ($this->sftpStorage->fileExists($path)) {
            return $this->sftpStorage->readStream($path);
        }

        return null;
    }
}
