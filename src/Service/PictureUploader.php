<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PictureUploader
{
    private $targetDirectory;
    private $slugger;
    private $baseUri;

    public function __construct($targetDirectory, SluggerInterface $slugger, $baseUri)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->baseUri = $baseUri;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $fullFileName = $this->baseUri . $fileName;

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            return $e->getMessage();
        }

        return $fullFileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
