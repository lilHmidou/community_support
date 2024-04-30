<?php

namespace App\Service\FileUploadService;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadServiceInterface
{
    public function uploadFile(UploadedFile $file): string;
}