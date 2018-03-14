<?php


namespace TutoBundle\Service;

use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("tuto.uploadImage")
 */
class uploadImage extends uploadFile
{
    /**
    @InjectParams({
    "targetDir" = @Inject("%image_directory%")
    })
     */
    public function __construct($targetDir)
    {
        parent::__construct($targetDir);
    }


}