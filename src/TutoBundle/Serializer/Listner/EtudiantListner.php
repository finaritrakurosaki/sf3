<?php

namespace TutoBundle\Serializer\Listner;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class EtudiantListner implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'format' => 'json',
                'class' => 'TutoBundle\Entity\etudiant',
                'method' => 'onPostSerialize',
            ]
        ];
    }

    public static function onPostSerialize(ObjectEvent $event)
    {
        // Possibilité de récupérer l'objet qui a été sérialisé
        $object = $event->getObject();
        $date = new \Datetime();
        $test = "mety v";
        // Possibilité de modifier le tableau après sérialisation
        $event->getVisitor()->addData('delivered_at', $date->format('l jS \of F Y h:i:s A'));
        $event->getVisitor()->addData('test',$test);
    }
}