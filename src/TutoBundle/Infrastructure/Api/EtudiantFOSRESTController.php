<?php

namespace TutoBundle\Infrastructure\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TutoBundle\Entity\etudiant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TutoBundle\Form\etudiantType;
use TutoBundle\Service\crudService;


class EtudiantFOSRESTController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/REST/etudiants/{id}",
     *     name = "rest_etudiant_show",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function showAction(etudiant $etudiant)
    {
        $view = $this->view($etudiant);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post(
     *    path = "/REST/etudiants",
     *    name = "rest_etudiant_create"
     * )
     */
    public function createAction(Request $request,crudService $crudService)
    {
        $data = $request->getContent();
        $etudiant = $this->get('jms_serializer')->deserialize($data, 'TutoBundle\Entity\etudiant', 'json');
        $this->get('validator')->validate($etudiant);
        $crudService->add($etudiant);
        return new Response('', Response::HTTP_CREATED);
    }
}
