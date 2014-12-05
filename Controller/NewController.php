<?php

namespace Reconnix\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;

use Reconnix\UserBundle\Form\Type\RegistrationFormType;

class NewController extends BaseController
{
    /**
     * @Route("/new")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        //$formType = $this->container->get('reconnix_user.registration.form.type');
        //$form = $this->container->get('form.factory')->create($formType);

        $form = $this->container->get('reconnix_user.registration.form');
        $form->handleRequest($request);

        if($form->isValid()){

            $user = $form->getData();

            // activate the account by default
            $user->setEnabled(true);

            // set the role
            if(($role = $request->request->get('reconnix_user_registration_form')['roles']) != null){
                $user->setRoles(array($role), 'ROLE_USER');
            }

            // finish up
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();

            // return to User index page            
            $url = $this->container->get('router')->generate('reconnix_user_all_index');
            return new RedirectResponse($url);                       
        }

        return $this->container->get('templating')->renderResponse('ReconnixUserBundle:New:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}