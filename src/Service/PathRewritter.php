<?php

namespace App\Service;

class PathRewritter
{
    private $baseUri;

    public function __construct($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function getFullPicturePath($pictures)
    {
        $pathsArray = [];

        foreach($pictures as $picture)
        {
            $fullPath = $this->baseUri . $picture->getPath(); 
            $pathsArray[$picture->getId()] = $fullPath;
        }

        return $pathsArray;
    }
}