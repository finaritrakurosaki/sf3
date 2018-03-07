<?php

namespace TutoBundle\Infrastructure\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TutoBundle\Entity\etudiant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\Serializer\SerializationContext;


class EtudiantRestController extends Controller
{

    /**
     * @Route("/api/etudiants/{id}", name="etudiant_show")
     *  @Method("GET")
     */
    public function showAction(etudiant $etudiant)
    {
        $data = $this->get('jms_serializer')->serialize($etudiant, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        /*
         * serializer an sf
          $data =  $this->get('serializer')->serialize($etudiant, 'json');
          $response = new Response($data);
          $response->headers->set('Content-Type', 'application/json');
          return $response;
         * */
    }
    /**
     * @Route("/api/etudiants", name="etudiant_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $etudiants = $this->getDoctrine()->getRepository(etudiant::class)->findAll();
        $data = $this->get('jms_serializer')->serialize($etudiants, 'json',SerializationContext::create()->setGroups(array('detail')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/api/etudiants", name="etudiant_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $etudiant = $this->get('jms_serializer')->deserialize($data, 'TutoBundle\Entity\etudiant', 'json');
        /*
         * $data = $request->getContent();
           $etudiant = $this->get('serializer')->deserialize($data, 'TutoBundle\Entity\etudiant', 'json');*/
        $this->get('validator')->validate($etudiant);
        $em= $this->getDoctrine()->getManager();
        $em->persist($etudiant);
        $em->flush();
        return new Response('', Response::HTTP_CREATED);
    }
    /**
     * @Route("/api/etudiants/{id}", name="etudiant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(etudiant $etudiant)
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($etudiant);
        $em->flush();
        return new Response('', Response::HTTP_ACCEPTED);
    }
    /**
     * @Route("/api/etudiants", name="etudiant_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request)
    {
        $data = $request->getContent();
        $etudiantData = $this->get('jms_serializer')->deserialize($data, 'TutoBundle\Entity\etudiant', 'json');
        $this->get('validator')->validate($etudiantData);
        $etudiant = $this->getDoctrine()->getRepository(etudiant::class)->find($etudiantData->getId());
        $keys=$etudiantData->getKey();
        foreach ($keys as $key) {
            $setters = 'set'.ucfirst($key);
            $getters = 'get'.ucfirst($key);
            $etudiant->$setters($etudiantData->$getters());
        }
        $em= $this->getDoctrine()->getManager();
        $em->flush();
        return new Response('', Response::HTTP_OK);
    }
}
