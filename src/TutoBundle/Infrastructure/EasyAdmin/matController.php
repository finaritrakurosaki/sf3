<?php

namespace TutoBundle\Infrastructure\EasyAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use TutoBundle\Form\matiereType;

class matController extends BaseAdminController
{
    protected function createEntityForm($entity, array $entityProperties, $view)
    {
        return $form = $this->createForm(matiereType::class,$entity);

    }
}
