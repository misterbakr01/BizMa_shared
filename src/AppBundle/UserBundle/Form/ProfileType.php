<?php
/**
 * Created by PhpStorm.
 * User: Bakr
 * Date: 4/3/2016
 * Time: 4:24 PM
 */
namespace AppBundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description')
                ->add('profilePictureFile');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile_edit';
    }

}