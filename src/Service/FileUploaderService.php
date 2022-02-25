<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {   //Récupérer le nom du fichier Tmp
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //Creer une url de fichier valide
        $safeFilename = $this->slugger->slug($originalFilename);
        //Crée un nouveau nom
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        //Copie le fichier tmp dans notre répertoire définitig
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
