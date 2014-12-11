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

        // convert the the FQCN of the User class into Doctrine Repo syntax
        $fqcn = ($this->container->getParameter('fos_user.model.user.class'));
        $unfilteredBundleName = strstr($fqcn, 'Bundle', true);
        $filteredBundleName = str_replace('\\', '', $unfilteredBundleName);

        $bundleName = sprintf('%sBundle', $filteredBundleName);
        $className = ltrim(strrchr($fqcn, '\\'), '\\');

        $repo = sprintf('%s:%s', $bundleName, $className);

        $user = $this->getDoctrine()->getRepository($repo)->find($id);

        // remove the entity
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('reconnix_user_all_index'));
    }
}