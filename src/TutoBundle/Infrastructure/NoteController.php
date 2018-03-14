<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TutoBundle\Entity\etudiant;
use TutoBundle\Entity\note;
use TutoBundle\Form\noteType;
use JMS\DiExtraBundle\Annotation\Inject;

class NoteController extends BaseController
{
    /**
     * @Inject("tuto.crud", required = true)
     */
    private $crud;
    /**
     * @Route("/admin/createNote",name="createNote")
     */
    public function createNote(Request $request)
    {
        $note = new note();
        $form = $this->createForm(noteType::class,$note);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()) {
            $this->crud->add($note);
            $this->addFlash(
                'notice',
                'note Added!'
            );
           return $this->redirectToRoute("createNote");
        }
        return $this->render('TutoBundle:Bulletin:createNote.html.twig',array('form'=> $form->createView()));
    }
    /**
     * @Route("/user/FindQ/{id}")
     */
    public function findQB(etudiant $etudiant)
    {

        $notes = $this->getDoctrine()->getRepository(note::class)->FindQueribuilder($etudiant->getId());
        return $this->render('TutoBundle:Note:affiche_note.html.twig',array(
                             'notes'=> $notes,
                             'etudiant'=> $etudiant
                             ));
    }
    /**
     * @Route("/user/FindDQL/{id}",name="findNote")
     */
    public function findDQL(etudiant $etudiant)
    {
        $result = $this->getDoctrine()->getRepository(note::class)->FindDQL($etudiant->getId());
        return $this->json($result);

    }

}
