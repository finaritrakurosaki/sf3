<?php
/**
 * Created by PhpStorm.
 * User: f.ratsimbazafy
 * Date: 12/03/2018
 * Time: 15:22
 */

namespace TutoBundle\Listener;

use TutoBundle\Event\ObjetEvent;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Observe;

/**
 * @Service
 */
class ObjetListener
{
    /**
     * @Observe("object.create.listener", priority = 250)
     */
    public function onObjectCreate(ObjetEvent $event)
    {
        die('success listener');
    }

}