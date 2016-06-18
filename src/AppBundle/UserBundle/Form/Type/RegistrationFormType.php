<?php
/**
 * Created by PhpStorm.
 * User: Bakr
 * Date: 6/16/2016
 * Time: 6:55 PM
 */
namespace AppBundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function __construct($class, $roles_hierarchy = null)
    {
        $this->roles_hierarchy = $roles_hierarchy;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('roles',ChoiceType::class,array(
            'label'   => 'Rôle à attribuer',
            'multiple'=> true,
            'choices' => array(
                'Membre' => 'ROLE_USER',
                'Propriétaire' => 'ROLE_BUSINESS_OWNER',
            )
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}