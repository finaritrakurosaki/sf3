<?php
/**
 * Created by PhpStorm.
 * User: f.ratsimbazafy
 * Date: 12/03/2018
 * Time: 15:22
 */

namespace TutoBundle\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TutoBundle\Event\ObjetEvent;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Observe;

/**
 * @Service
 */
class ObjetSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['object.update.subscriber'=>'onObjectCreate'];
    }
    /**
     * @Observe("object.update.subscriber", priority = 255)
     */
    public function onObjectCreate(ObjetEvent $event)
    {
        die('success subscriber');
    }

}