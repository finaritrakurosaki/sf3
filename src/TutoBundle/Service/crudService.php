<?php

namespace TutoBundle\Service;

 use JMS\DiExtraBundle\Annotation\Service;
 use JMS\DiExtraBundle\Annotation\InjectParams;
 use JMS\DiExtraBundle\Annotation\Inject;
 use TutoBundle\Entity\etudiant;
 use TutoBundle\Event\ObjetEvent;


 /**
  * @Service("tuto.crud")
  */
 class crudService
{

    private $_em;
    private $dispatcher;

     /**
     *@InjectParams({
     * "em" = @Inject("doctrine.orm.entity_manager"),
     *  "dispatcher" = @Inject("event_dispatcher")
     })
      */
    public function __construct($em,$dispatcher)
    {
        $this->_em = $em;
        $this->dispatcher = $dispatcher;

    }


    public function add($object)
    {
        if ($object instanceof etudiant){
            $this->dispatcher->dispatch('sendMailEvent',new ObjetEvent($object));
        }

        $this->_em->persist($object);
        $this->_em->flush();
    }
    public function update()
    {
        $this->dispatcher->dispatch('object.update.subscriber',new ObjetEvent());
        $this->_em->flush();
    }
    public function delete($object)
    {
        $this->_em->remove($object);
        $this->_em->flush();
    }
}