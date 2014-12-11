<?php

namespace Reconnix\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Reconnix\UserBundle\Form\Type\UserRoleFormType;

class AllController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $repo = $this->container->get('reconnix_user.user_manager')->getUserRepository();

        $users = $this->getDoctrine()->getRepository($repo)->findAll();

        return array(
            'users' => $users
        );
    }
}
