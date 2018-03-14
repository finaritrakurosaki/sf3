<?php


namespace TutoBundle\Service;

use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("tuto.uploadParole")
 */
class uploadParole extends uploadFile
{
    /**
    @InjectParams({
    "targetDir" = @Inject("%parole_directory%")
    })
     */
    public function __construct($targetDir)
    {
        parent::__construct($targetDir);
    }
    public function saveText($titre,$file)
    {
        $fileName = $titre;
        $open = fopen($this->targetDir.$fileName, "a");
        fwrite($open, $file);
        fclose($open);
        return $fileName ;
    }


}