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
        // convert the the FQCN of the User class into Doctrine Repo syntax
        $fqcn = ($this->container->getParameter('fos_user.model.user.class'));
        $unfilteredBundleName = strstr($fqcn, 'Bundle', true);
        $filteredBundleName = str_replace('\\', '', $unfilteredBundleName);

        $bundleName = sprintf('%sBundle', $filteredBundleName);
        $className = ltrim(strrchr($fqcn, '\\'), '\\');

        $repo = sprintf('%s:%s', $bundleName, $className);

        $users = $this->getDoctrine()->getRepository($repo)->findAll();

        return array(
            'users' => $users
        );
    }
}
