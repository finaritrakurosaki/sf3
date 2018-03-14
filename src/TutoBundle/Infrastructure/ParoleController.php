<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use TutoBundle\Entity\parole;
use JMS\DiExtraBundle\Annotation\Inject;

class ParoleController extends Controller
{
    /**
     * @Inject("tuto.crud", required = true)
     */
    private $crud;
    /**
     * @Inject("tuto.uploadParole", required = true)
     */
    private $uploadParole;
    /**
     * @Inject("%parole_directory%")
     */
    private $targetDir;
    /**
     * @Route("/user/addParole",name="addParole")
     */
    public function addParole(Request $request)
    {
        $parole = new parole();
        //fromulaire d'upload d'un fichier texte qui existe deja
        $form= $this->createFormBuilder()
            ->add('parole', FileType::class,array('label'=>'fichier(.txt)'))
            ->add('langue',ChoiceType::class,array('choices'=>array('en'=>'en','fr'=>'fr')))
            ->add('upload', SubmitType::class)
            ->getForm();
        //fromulaire de sauvegarde d'un texte copier dans un textearea
        $form1= $this->createFormBuilder()
            ->add('titre', TextType::class)
            ->add('parole', TextareaType::class)
            ->add('langue',ChoiceType::class,array('choices'=>array('en'=>'en','fr'=>'fr')))
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('parole')->getData();
            $fileName = $this->uploadParole->upload($file);
            $parole->setTitre($fileName);
            $parole->setLangue($form->get('langue')->getData());
            $this->crud->add($parole);
            $this->addFlash(
                'notice',
                'Parole : '.$fileName.' uploaded!'
            );
            return $this->redirectToRoute('listParole');
        }
        $form1->handleRequest($request);
        if ($form1->isValid()){
            $titre = $form1->get('titre')->getData();
            $file = $form1->get('parole')->getData();
            $fileName = $this->uploadParole->saveText($titre,$file);
            $parole->setTitre($fileName);
            $parole->setLangue($form->get('langue')->getData());
            $this->crud->add($parole);
            $this->addFlash(
                'notice',
                'Parole : '.$fileName.' uploaded!'
            );
            return $this->redirectToRoute('listParole');
        }
        return $this->render('TutoBundle:Parole:add_parole.html.twig', array(
            'form'=> $form->createView(),
            'form1'=> $form1->createView()
        ));
    }

    /**
     * @Route("/user/listParole",name="listParole")
     */
    public function listParole()
    {
        $parolesENG=[];
        $parolesFR=[];
        $paroles = $this->getDoctrine()->getRepository(parole::class)->findAll();

        foreach ($paroles as $parole   ){
            if ( 'en'===$parole->getLangue()){
                $parolesENG[$parole->getId()]=str_replace('.txt','',$parole->getTitre());
            }
            else{
                $parolesFR[$parole->getId()]=str_replace('.txt','',$parole->getTitre());
            }
        }
        return $this->render('TutoBundle:Parole:list_parole.html.twig', array(
            'paroleENG' => $parolesENG,
            'paroleFR' => $parolesFR
        ));
    }
    /**
     * @Route("user/showParole/{id}",name="showParole")
     */
    public  function showParole(parole $parole)
    { $contenus=[];
            if( file_exists( $this->targetDir.$parole->getTitre())){
                $open = fopen( $this->targetDir.$parole->getTitre(), 'r');
                while(!feof($open)){
                    $contenus[]=fgets($open);
                }
                fclose($open);
            }

            $titre = str_replace('.txt','',$parole->getTitre());
        return $this->render('TutoBundle:Parole:show_parole.html.twig', array(
              'contenus'=> $contenus,
             'titre' => $titre
        ));
    }

}
