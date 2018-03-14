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
use Nelmio\ApiDocBundle\Annotation as Doc;

class NoteRestController extends Controller
{

    /**
     *@Doc\ApiDoc(
     *     section="notes",
     *     resource=true,
     *     description="get one note of student.",
     *     requirements={
     *        {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="identifier note."
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when success",
     *         401="Token not found"
     *     },
     *     output= { "class"=note::class,"groups"={"note"} }
     * )
     * @Route("/api/notes/{id}", name="note_show",requirements={"id":"\d+"})
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
     *@Doc\ApiDoc(
     *     section="notes",
     *     resource=true,
     *     description="get all notes.",
     *     statusCodes={
     *         200="Returned when success",
     *         401="Token not found"
     *     },
     *     output= {"class"=note::class}
     * )
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
     *@Doc\ApiDoc(
     *     section="notes",
     *     resource=true,
     *     description="create note for student.",
     *     statusCodes={
     *         201="Returned when created",
     *         401="Token not found"
     *     }
     * )
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
     *@Doc\ApiDoc(
     *     section="notes",
     *     resource=true,
     *     description="delete one note .",
     *     requirements={
     *        {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="identifier note."
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when deleted",
     *         401="Token not found"
     *     }
     * )
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
     *@Doc\ApiDoc(
     *     section="notes",
     *     resource=true,
     *     description="update note of student.",
     *     statusCodes={
     *         200="Returned when success",
     *         401="Token not found"
     *     }
     * )
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
