<?php

namespace App\Service\FileUploadService;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadServiceImpl implements FileUploadServiceInterface
{
    private $docsDirectory;

    public function __construct(ParameterBagInterface $params)
    {
        $this->docsDirectory = $params->get('docs_directory');
    }

    public function uploadFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $file->move($this->docsDirectory, $newFilename);

        return $newFilename;
    }
}
