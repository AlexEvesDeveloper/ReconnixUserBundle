<?php

namespace Reconnix\UserBundle\Classes;

use Symfony\Component\DependencyInjection\Container;

class UserManager
{
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function getUserRepository()
	{
        // convert the the FQCN of the User class into Doctrine Repo syntax
        $fqcn = ($this->container->getParameter('fos_user.model.user.class'));
        $unfilteredBundleName = strstr($fqcn, 'Bundle', true);
        $filteredBundleName = str_replace('\\', '', $unfilteredBundleName);

        $bundleName = sprintf('%sBundle', $filteredBundleName);
        $className = ltrim(strrchr($fqcn, '\\'), '\\');

        return sprintf('%s:%s', $bundleName, $className);
	}
}