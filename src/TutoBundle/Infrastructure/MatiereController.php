<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use TutoBundle\Entity\matiere;
use TutoBundle\Form\matiereEditType;
use TutoBundle\Form\matiereType;
use TutoBundle\Service\crudService;

class MatiereController extends BaseController
{
    /**
     * @Route("/admin/createMatiere",name="createMatiere")
     */
    public function createMatiere(Request $request)
    {
        $matiere = new matiere();
        $form = $this->createForm(matiereType::class,$matiere);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()) {
            $this->loadService()->add($matiere);
            $this->addFlash(
                'notice',
                'Matiere ajoutée!'
            );
            return $this->redirectToRoute("createMatiere");
        }
        return $this->render('TutoBundle:Matiere:create_matiere.html.twig', array(
           'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/admin/updateMatiere/{id}",name="updateMatiere")
     */
    public function updateMatiere(Request $request, matiere $matiere,crudService $crudService)
    {

        $form = $this->createForm(matiereEditType::class,$matiere);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()) {
            $crudService->update();
            $this->addFlash(
                'notice',
                'Matiere editée!'
            );

        }
        return $this->render('TutoBundle:Matiere:update_matiere.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/user/listMatiere",name="listMatiere")
     */
    public function listMatiere()
    {
        $matieres = $this->getDoctrine()->getRepository(matiere::class)->findAll();
        return $this->render('TutoBundle:Matiere:list_matiere.html.twig', array(
            'matieres'=>$matieres
        ));
    }

    /**
     * @Route("/admin/deleteMatiere{id}",name="deleteMatiere")
     */
    public function deleteMatiere(matiere $matiere)
    {
        $this->loadService()->delete($matiere);
        return $this->redirectToRoute('listMatiere');
    }
}
