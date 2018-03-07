<?php

namespace TutoBundle\Infrastructure\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\Serializer\SerializationContext;
use TutoBundle\Entity\etudiant;
use TutoBundle\Entity\matiere;
use TutoBundle\Entity\note;


class NoteRestController extends Controller
{

    /**
     * @Route("/api/notes/{id}", name="note_show")
     *  @Method("GET")
     */
    public function showAction(note $note)
    {
        $data = $this->get('jms_serializer')->serialize($note, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/api/notes", name="note_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $note = $this->getDoctrine()->getRepository(note::class)->findAll();
        $data = $this->get('jms_serializer')->serialize($note, 'json',SerializationContext::create()->setGroups(array('list')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/api/notes", name="note_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $note = $this->get('jms_serializer')->deserialize($data, 'TutoBundle\Entity\note', 'json');
        $this->get('validator')->validate($note);
        $etudiant= $this->getDoctrine()->getRepository(etudiant::class)->find($note->getEtudiant()->getId());
        $matiere= $this->getDoctrine()->getRepository(matiere::class)->find($note->getMatiere()->getId());
        $note->setEtudiant($etudiant);
        $note->setMatiere($matiere);
        $em= $this->getDoctrine()->getManager();
        $em->persist($note);
        $em->flush();
        return new Response('', Response::HTTP_CREATED);
    }
    /**
     * @Route("/api/notes/{id}", name="note_delete")
     * @Method("DELETE")
     */
    public function deleteAction(note $note)
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($note);
        $em->flush();
        return new Response('', Response::HTTP_ACCEPTED);
    }
    /**
     * @Route("/api/notes", name="note_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request)
    {
        $data = $request->getContent();
        $noteData = $this->get('jms_serializer')->deserialize($data, 'TutoBundle\Entity\note', 'json');
        $this->get('validator')->validate($noteData);
        $note = $this->getDoctrine()->getRepository(note::class)->find($noteData->getId());
        $keys=$noteData->getKey();
        foreach ($keys as $key) {
            $setters = 'set'.ucfirst($key);
            $getters = 'get'.ucfirst($key);
            $note->$setters($noteData->$getters());
        }
        $em= $this->getDoctrine()->getManager();
        $em->flush();
        return new Response('', Response::HTTP_OK);
    }
}
