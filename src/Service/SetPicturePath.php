<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\PictureUploader;

class SetPicturePath
{
    public function getFullPath($picture)
    {
        $eachPicture = [];

        foreach($picture as $onePicture)
        {
            $eachPicture['picture'] = $onePicture->getPath();

            $fullPath = "/uploads/pictures/" . $eachPicture['picture']; 

            $onePicture->setPath($fullPath);
        }

        return $picture; 
    }
}