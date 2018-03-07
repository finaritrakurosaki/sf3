<?php

namespace TutoBundle\Infrastructure\EasyAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use TutoBundle\Form\noteType;


class noController extends BaseAdminController
{
    protected function createEntityForm($entity, array $entityProperties, $view)
    {
         $form = $this->createForm(noteType::class,$entity);
         $form->add('create',SubmitType::class);
         return $form;

    }
}
