<?php

namespace Paginaweb\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/principal")
     */
    public function indexAction()
    {
        return $this->render('pwebFrontendBundle:Default:index.html.twig');
    }
}
