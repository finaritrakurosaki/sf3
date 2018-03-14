<?php

namespace TutoBundle\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class testApiExtController extends Controller
{
    /**
     * @Route("/user/getPays/{name}", name="getPays",requirements={"name":"^[a-z]{3}$"})
     */
    public  function getPays($name)
    {
        $datas = file_get_contents("https://restcountries.eu/rest/v2/name/".$name);
        $datas_parse = json_decode($datas);
        for ($nombrePays = 0 ; $nombrePays < count($datas_parse) ; $nombrePays ++) {
            echo($datas_parse[$nombrePays]->{'name'});
        }

        die();
    }
}
