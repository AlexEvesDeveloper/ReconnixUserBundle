<?php

namespace Reconnix\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ReconnixUserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // this bundle wraps FOSUserBundle, so set some of its configuration here
        //$container->setParameter('fos_user.registration.form.type', 'reconnix_user_registration');
        //$container->setParameter('fos_user.registration.form.name', 'reconnix_user_registration_form');
        //$container->setParameter('fos_user.profile.form.type', 'reconnix_user_profile');
        //$container->setParameter('fos_user.profile.form.name', 'reconnix_user_profile_form');

        if (!empty($config['registration'])) {
            $this->loadRegistration($config['registration'], $container, $loader);
        }

        if (!empty($config['profile'])) {
            $this->loadProfile($config['profile'], $container, $loader);
        }
    }

    private function loadRegistration(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $this->remapParametersNamespaces($config, $container, array(
            'form' => 'reconnix_user.registration.form.%s',
        ));
    }

    private function loadProfile(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $this->remapParametersNamespaces($config, $container, array(
            'form' => 'reconnix_user.profile.form.%s',
        ));
    }

    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }
}
