<?php

namespace Reconnix\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegistrationFormType extends AbstractType
{
    protected $class;
    protected $roles;
    protected $currentRole;
    protected $container;

    public function __construct($class, Container $container, $currentRole = 'ROLE_USER')
    {
        $this->class = $class;

        $this->container = $container;
        if($this->container->get('security.context')->isGranted('ROLE_ADMIN')){
            $this->roles = $this->refactorRoles($this->container->getParameter('security.role_hierarchy.roles'));
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
        ;

        if($this->roles !== null){
            $builder->add('roles', 'choice', array(
                'choices' => $this->roles,
                'multiple' => false,
                'expanded' => true,
                'mapped' => false,
                'data' => $this->currentRole
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
        ));
    }

    public function getName()
    {
        return 'reconnix_user_registration';
    }

    private function refactorRoles($originRoles)
    {
        $roles = array();
        $rolesAdded = array();
        // Add herited roles
        foreach ($originRoles as $roleParent => $rolesHerit) {
            $tmpRoles = array_values($rolesHerit);
            $rolesAdded = array_merge($rolesAdded, $tmpRoles);
            $roles[$roleParent] = array_combine($tmpRoles, $tmpRoles);
        }
       
        // always has this as a default minimum
        $finalRoles['ROLE_USER'] = 'Normal';

        foreach($roles as $key => $value){
            switch($key){
                case 'ROLE_ADMIN':
                    $finalRoles[$key] = 'Admin';
                    break;
                case 'ROLE_SUPER_ADMIN':
                    $finalRoles[$key] = 'Super Admin';
                    break;
                default:
                    break;
            }
        }
        return $finalRoles;
    }
}