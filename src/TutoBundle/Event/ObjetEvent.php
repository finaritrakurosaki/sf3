<?php
/**
 * Created by PhpStorm.
 * User: f.ratsimbazafy
 * Date: 12/03/2018
 * Time: 15:17
 */

namespace TutoBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class ObjetEvent extends Event
{
  private $object;

    /**
     * ObjetEvent constructor.
     * @param $object
     */
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

}