<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use TutoBundle\Entity\image;
use TutoBundle\Service\uploadFile;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;


class FileController extends BaseController
{
    /**
     * @Inject("tuto.crud", required = true)
     */
    private $crud;
    /**
     * @Inject("tuto.uploadImage", required = true)
     */
    private $uploadFile;
    /**
     *@Route("/admin/uploadFile",name="uploadFile")
     */
    public function uploadFile(Request $request)
    {
        $image = new image();
        $form= $this->createFormBuilder()
            ->add('file', FileType::class,array('label'=>'file(<=128MB)'))
            ->add('upload', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form->get('file')->getData();
            $fileName = $this->uploadFile->upload($file);
            $image->setImage($fileName);
            $this->crud->add($image);
            $this->addFlash(
                'notice',
                'File : '.$fileName.' uploaded!'
            );
            return $this->redirectToRoute('uploadFile');
        }
        $imageUploaded = $this->getDoctrine()->getRepository(image::class)->findAll();
        return $this->render('TutoBundle:Default:uploadFile.html.twig',array('form'=> $form->createView(),
                              'imageUploaded'=>$imageUploaded
                             ));
    }
}
