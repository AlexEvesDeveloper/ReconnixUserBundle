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

        return array(
            'user' => $user
        );
    }
}