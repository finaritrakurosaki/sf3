<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use TutoBundle\Service\uploadFile;


class FileController extends Controller
{
    /**
     *@Route("/admin/uploadFile",name="uploadFile")
     */
    public function uploadFile(Request $request,uploadFile $uploadFile)
    {
        $form= $this->createFormBuilder()
            ->add('file', FileType::class,array('label'=>'file(<=128MB)'))
            ->add('upload', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form->get('file')->getData();
            $fileName = $uploadFile->upload($file);
            $this->addFlash(
                'notice',
                'File : '.$fileName.' uploaded!'
            );
            return $this->redirectToRoute('uploadFile');
        }
        return $this->render('TutoBundle:Default:uploadFile.html.twig',array('form'=> $form->createView()));
    }
}
