<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use TutoBundle\Entity\comment;
use TutoBundle\Entity\publication;
use TutoBundle\Form\commentType;
use JMS\DiExtraBundle\Annotation\Inject;

class CommentController extends BaseController
{
    /**
     * @Inject("tuto.crud", required = true)
     */
    private $crud;
    /**
     * @Route("/user/comments{id}", name="createComment")
     */
    public function createComment(publication $publication, Request $request)
    {
        $comment = new comment();
        $form = $this->createForm(commentType::class,$comment);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()) {
            $comment->setPublication($publication)
                    ->setUser($this->getUser());
            $this->crud->add($comment);
            return $this->redirectToRoute('createComment',array('id' => $publication->getId()),301);
        }
        return $this->render('TutoBundle:Comment:create_comment.html.twig',
            array('publication' => $publication,
                   'form' =>$form->createView()
                ));
    }

}
