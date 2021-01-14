<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class UploaderHelper
{

    /**
     * @var string
     */
    public $uploadsPath;

    /**
     * @var string
     */

    public $publicPath;
    /**
     * @var string
     */
    private $uploadsDBPath;

    public function __construct(string $uploadsPath,string  $uploadsDBPath, string $publicPath){

        $this->uploadsPath = $uploadsPath;
        $this->publicPath = $publicPath;
        $this->uploadsDBPath = $uploadsDBPath;
    }

    public function uploadProfileImage(UploadedFile $uploadedFile, $userID):string{
        $destination = $this->uploadsPath.'/users/'.$userID.'/';
        $newFileName = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME)."-".uniqid().".".$uploadedFile->guessExtension();
        $uploadedFile->move($destination,$newFileName);

        return $this->uploadsDBPath.'/users/'.$userID.'/'.$newFileName;
    }

    public function uploadAjaxFile(UploadedFile $uploadedFile,$uploadPathFromAjax):string{
        $destination = $this->uploadsPath.$uploadPathFromAjax.'/';
        $newFileName = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME)."-".uniqid().".".$uploadedFile->guessExtension();
        $uploadedFile->move($destination,$newFileName);
        //dd($uploadPathFromAjax."/".$newFileName);
        return $newFileName;
    }

    public function uploadImportPreisFile(UploadedFile $uploadedFile){
        $destination = $this->uploadsDBPath.'/tmpupload/';
        $newFileName = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME).".".$uploadedFile->guessExtension();
        $uploadedFile->move($destination,$newFileName);
        return $newFileName;
    }

    public function uploadFacilityFile(UploadedFile $uploadedFile,$uploadPathFromAjax,$destFileName):string{
        $destination = $this->uploadsPath.$uploadPathFromAjax.'/';
        $newFileName = $destFileName.".".$uploadedFile->guessExtension();
        $uploadedFile->move($destination,$newFileName);
        return $newFileName;
    }

    public function deleteFile($name):string{
        $filesystem = new Filesystem();
        //dd($this->get('kernel')->getProjectDir());
        try {
            $filesystem->remove(['file' , $this->publicPath.$name]);
        } catch (IOExceptionInterface $exception) {
            return "An error occurred while creating your directory at ".$exception->getPath();
        }
        return true;
    }

}