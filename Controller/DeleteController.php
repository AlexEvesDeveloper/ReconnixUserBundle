<?php

namespace Reconnix\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DeleteController extends Controller
{
    /**
     * @Route("/delete/{id}")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $this->container->get('reconnix_user.user_manager')->getUserRepository();

        $user = $this->getDoctrine()->getRepository($repo)->find($id);

        // remove the entity
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('reconnix_user_all_index'));
    }
}