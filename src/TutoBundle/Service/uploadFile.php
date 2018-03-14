<?php
namespace TutoBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;



class uploadFile
{

  protected $targetDir;
   public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

   public function upload(UploadedFile $file)
    {

        /*$fileName = md5(uniqid()).'.'.$file->guessExtension();*/
        $file->move($this->getTargetDir(), $file->getClientOriginalName());
        return $file->getClientOriginalName();
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
