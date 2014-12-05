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
        //$roles = $this->container->getParameter('security.role_hierarchy.roles');
    	$users = $this->getDoctrine()->getRepository('ReconnixUtilBundle:User')->findAll();
/*
        $forms = array();
        foreach($users as $user){
            $form = $this->createForm(new UserRoleFormType($roles, current($user->getRoles())));
           // $rows['user'] = $user;
            $forms[] = $form->createView();
        }
*/
        return array(
        	'users' => $users
        );
    }
}
