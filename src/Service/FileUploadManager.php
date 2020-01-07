<?php
namespace App\Service;

use Symfony\Component\Form\Form;

class FileUploadManager
{
    private $targetFolderPath;

    public function __construct($targetFolderPath)
    {
        $this->targetFolderPath = $targetFolderPath;
    }

    public function upload(Form $formFile, int $userId)
    {
        // $formFile ici est un objet de la classe Form qui contient les données du champs «avatar»
        // On utilise getData() pour obtenir un objet de la classe UploadedFile
        
        $file = $formFile->getData();
        
        // On sait que si aucun fichier n'a été envoyé, $file sera null
        if ($file !== null) {
            $filmenameToStore = $userId . '.' . $file->getClientOriginalExtension();
            $imageFile = $file->move($this->targetFolderPath, $filmenameToStore);
            // $imageFile est un objet de la classe File qui représente le nouveau fichier créé et déplacé après l'upload
            // On utilise sa méthode ->getPathname() pour obtenir le chemin relatif du fichier et le retourner

            return $imageFile->getPathname();
        } else {
            return null;
        }
    }
}