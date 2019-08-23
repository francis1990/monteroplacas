<?php

namespace Sistemadmin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\SecurityContext;


class DefaultController extends Controller
{
    /**
     * @Route("/login" ,name="login")
     */
    public function indexAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('sistAdminBundle:Default:index.html.twig',
            array(
                'last_username'=>$lastUsername,
                'error'=>$error,
            ));
    }
    /**
     * @Route("/login_check", name="login_check")
     */
    public function securityCheckAction() {
        // The security layer will intercept this request
    }



}
