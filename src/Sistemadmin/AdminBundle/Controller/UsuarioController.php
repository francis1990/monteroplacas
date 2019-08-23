<?php

namespace Sistemadmin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sistemadmin\AdminBundle\Entity\Usuario;
use Sistemadmin\AdminBundle\Form\UsuarioType;
use Sistemadmin\BackendBundle\Helper;

/**
 * Usuario controller.
 *
 * @Route("/usuario")
 */
class UsuarioController extends Controller
{

    /**
     * Creates a new Usuario entity.
     *
     * @Route("/new", name="usuario_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
//        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
        $usuario = new Usuario();
        $form = $this->createForm('Sistemadmin\AdminBundle\Form\UsuarioType', $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
            $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
            $usuario->setPassword($passwordCodificado);
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('usuario_index');
        }

        return $this->render('usuario/new.html.twig', array(
            'usuario' => $usuario,
            'form' => $form->createView(),
        ));
    }
    /**
     * Lists all Usuario entities.
     *
     * @Route("/{page}", name="usuario_index")
     * @Method("GET")
     */
    public function indexAction($page=1)
    {
//        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
        $em = $this->getDoctrine()->getManager();

        $order_by = array();
        $usuariosCount = $em->getRepository('sistAdminBundle:Usuario')->GetByParamCount();
        $results = 10; //paginado
        $paginator = new Helper\Paginator($usuariosCount, $page, $results);
        $usuarios = $em->getRepository('sistAdminBundle:Usuario')->GetByParam( $order_by, $paginator->getOffset(), $paginator->getLimit());
        $sort_direction = 'desc';
        $pathern='usuario_index';
        return $this->render('usuario/index.html.twig', array(
            'usuarios' => $usuarios, 'sort_dir' => $sort_direction, 'paginator' => $paginator,
            "page" =>$page,"pathern" =>$pathern
        ));
    }



    /**
     * Finds and displays a Usuario entity.
     *
     * @Route("/show/{id}", name="usuario_show")
     * @Method("GET")
     */
    public function showAction(Usuario $usuario)
    {
        $deleteForm = $this->createDeleteForm($usuario);

        return $this->render('usuario/show.html.twig', array(
            'usuario' => $usuario,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/edit/{id}", name="usuario_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Usuario $usuario)
    {
//        if (!$this->get('security.authorization_checker')->isGranted('edit',$usuario)) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('Sistemadmin\AdminBundle\Form\UsuarioType', $usuario);
        $editForm->remove('password');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
            $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
            $usuario->setPassword($passwordCodificado);
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(
                'success',
                'Se ha modificado el usuario de manera exitosa!'
            );
            return $this->redirectToRoute('usuario_index');
        }

        return $this->render('usuario/edit.html.twig', array(
            'usuario' => $usuario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to reset password an existing Usuario entity.
     *
     * @Route("/reset/{id}", name="usuario_reset")
     * @Method({"GET", "POST"})
     */
    public function resetPasswordAction(Request $request, Usuario $usuario)
    {
//        if ( !$this->isGranted('ROLE_USER', $usuario)) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('Sistemadmin\AdminBundle\Form\ResetPasswordType', $usuario);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
            $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
            $usuario->setPassword($passwordCodificado);
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(
                'success',
                'Se ha cambiado la clave de manera exitosa!'
            );
            return $this->redirectToRoute('usuario_index');
        }

        return $this->render('usuario/reset.html.twig', array(
            'usuario' => $usuario,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Usuario entity.
     *
     * @Route("/delete/{id}", name="usuario_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Usuario $usuario)
    {
//        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            $this->addFlash(
//                'danger',
//                'No tiene permiso de realizar esta acción!'
//            );
//            return $this->redirect($this->generateUrl('main'));
//        }
       /* $form = $this->createDeleteForm($usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {*/
            $em = $this->getDoctrine()->getManager();
            $em->remove($usuario);
            $em->flush();
        $this->addFlash(
            'success',
            'Se ha eliminado el usuario de manera exitosa!'
        );
       /* }*/

        return $this->redirectToRoute('usuario_index');
    }

    /**
     * Creates a form to delete a Usuario entity.
     *
     * @param Usuario $usuario The Usuario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Usuario $usuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_delete', array('id' => $usuario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
