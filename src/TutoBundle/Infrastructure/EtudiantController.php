<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use TutoBundle\Entity\etudiant;
use TutoBundle\Form\etudiantEditType;
use TutoBundle\Form\etudiantType;
use JMS\DiExtraBundle\Annotation\Inject;

class EtudiantController extends BaseController
{
    /**
     * @Inject("tuto.crud", required = true)
     */
    private $crud;
    /**
     * @Route("/admin/createEtudiant",name="createEtudiant")
     */
    public function createEtudiant(Request $request)
    {
        $etudiant = new etudiant();
        $form = $this->createForm(etudiantType::class,$etudiant);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()) {
            $this->crud->add($etudiant);
            $this->addFlash(
                'notice',
                'Student Added!'
            );
           return $this->redirectToRoute("createEtudiant");
        }
        return $this->render('TutoBundle:Etudiant:create.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/user/listEtudiant",name="listEtudiant")
     */
    public function listEtudiant(Request $request)
    {
        $query = $this->getDoctrine()->getRepository(etudiant::class)->FindAllPaginator();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),3 );
        return $this->render('TutoBundle:Etudiant:list.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/admin/updateEtudiant/{id}",name="updateEtudiant")
     */
    public function updateEtudiant(Request $request, etudiant $etudiant)
    {
        $form = $this->createForm(etudiantEditType::class,$etudiant);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()) {
            $this->crud->update();
            $this->addFlash(
                'notice',
                'Student Edited!'
            );

        }
        return $this->render('TutoBundle:Etudiant:update.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/admin/deleteEtudiant/{id}",name="deleteEtudiant")
     */
    public function deleteEtudiant(etudiant $etudiant)
    {

        try {
            $this->crud->delete($etudiant);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
            return $this->redirectToRoute('listEtudiant');
    }

    /**
     * @Route("/user/noteEtudiant/{id}",name="noteEtudiant")
     */
    public function noteEtudiant($id)
    {
        return $this->forward('TutoBundle\Infrastructure\NoteController::findQB',array(
                              'id'=>$id
                             ));
    }
    /**
     * @Route("/user/detailsEtudiant/{id}",name="detailsEtudiant")
     */
    public function detailsEtudiant(etudiant $etudiant)
    {
        return $this->render('TutoBundle:Etudiant:details.html.twig',array('etudiant'=> $etudiant));
    }
}
