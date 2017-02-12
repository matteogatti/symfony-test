<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ingrediente;
use AppBundle\Entity\Ricetta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DashController extends Controller
{
    /**
     * @Route("/", name="app_bundle.dashboard.index")
     * @Template()
     */
    public function indexAction()
    {
        $ricette = $this->getDoctrine()->getRepository(Ricetta::class)->countAll();
        $ingredienti = $this->getDoctrine()->getRepository(Ingrediente::class)->countAll();

        return [
            'title'         => 'Dashboard',
            'ricette'       => $ricette,
            'ingredienti'   => $ingredienti
        ];
    }
}
