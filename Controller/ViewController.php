<?php

namespace Reconnix\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ViewController extends Controller
{
    /**
     * @Route("/view/{id}")
     * @Template()
     */
    public function indexAction(Request $request, $id)
    {
        // convert the the FQCN of the User class into Doctrine Repo syntax
        $fqcn = ($this->container->getParameter('fos_user.model.user.class'));
        $unfilteredBundleName = strstr($fqcn, 'Bundle', true);
        $filteredBundleName = str_replace('\\', '', $unfilteredBundleName);

        $bundleName = sprintf('%sBundle', $filteredBundleName);
        $className = ltrim(strrchr($fqcn, '\\'), '\\');

        $repo = sprintf('%s:%s', $bundleName, $className);

        $user = $this->getDoctrine()->getRepository($repo)->find($id);
        $form = $this->container->get('fos_user.profile.form');
        $form->setData($user);

        $form->handleRequest($request);
        if($form->isValid()){
            
            // set the role
            if(($role = $request->request->get('reconnix_user_profile_form')['roles']) != null){
                $user->setRoles(array($role), 'ROLE_USER');
            }

            // finish up
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();
        }

        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }
}