<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{

    /**
     * @var string
     */
    private $uploadsPath;
    /**
     * @var string
     */
    private $uploadsDBPath;

    public function __construct(string $uploadsPath,string  $uploadsDBPath){

        $this->uploadsPath = $uploadsPath;
        $this->uploadsDBPath = $uploadsDBPath;
    }

    public function uploadProfileImage(UploadedFile $uploadedFile, $userID):string{
        $destination = $this->uploadsPath.'/users/'.$userID.'/';
        $newFileName = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME)."-".uniqid().".".$uploadedFile->guessExtension();
        $uploadedFile->move($destination,$newFileName);

        return $this->uploadsDBPath.'/users/'.$userID.'/'.$newFileName;
    }

}