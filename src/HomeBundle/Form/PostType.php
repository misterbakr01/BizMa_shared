<?php

namespace HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
                ->add('content',null,array(
                    'attr' => array(
                        'class' => 'tinymce',
                        )
                    )
                )
                ->add('file')
                ->add('submit',SubmitType::class,array(
                    'attr' => array(
                        'class' => 'btn-primary'
                        )
                    )
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'home_bundle_post_type';
    }
}
