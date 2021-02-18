<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Kreait\Firebase\Storage;

class FileUploader
{
    private $targetDirectory;
    private $credentials;
    private $slugger;

    public function __construct($targetDirectory, Storage $storage, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->storage = $storage;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);

        }
        catch (CannotWriteFileException $e) {
            throw new \Exception("Problème paramétrage serveur : impossible d'écrire le fichier.");
        }
        catch(ExtensionFileException $e){
          throw new \Exception("Le fichier fourni ne possède pas une extension valide.");
        }

        $bucket = $this->storage->getBucket();

        $uploadOptions = array_filter([
            'name' => $fileName,
            'predefinedAcl' => 'publicRead',
        ]);

        $uploadedFile = $bucket->upload(fopen($this->getFilePath($fileName), 'rb'), $uploadOptions);

        return $uploadedFile->name();
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    protected function getFilePath($fileName)
    {
        return $this->getTargetDirectory()."/".$fileName;
    }
}
?>
