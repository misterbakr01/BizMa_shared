<?php

namespace BusinessBundle\Form;

use BusinessBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class BusinessType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,array(
                'attr' => array(
                    'placeholder' => 'Ex: Restaurant Sushi'
                )
            ))
            ->add('description',null,array(
                    'attr' => array(
                        'class' => 'tinymce',
                    ),
                )
            )
            ->add('openings',CollectionType::class, array(
                'entry_type'   => OpeningHoursType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'label' => false,
                )
            )
            ->add('address',null,[
                'attr' => [
                    'class' => 'map-address',
                ]
            ])
            ->add('lng',HiddenType::class)
            ->add('lat',HiddenType::class)
            ->add('city', EntityType::class, [
                'class' => 'BusinessBundle\Entity\City',
                'choice_label' => 'name',
            ])
            ->add('file')
            ->add('website',null,[
                'attr' => array(
                    'placeholder' => 'Ex: www.monsite.com'
                )
            ])
            ->add('phone',null,[
                'attr' => array(
                    'placeholder' => 'Ex: 0610203040'
                )
            ])
            ->add('email',EmailType::class,[
                'attr' => array(
                    'placeholder' => 'Ex: restaurant@email.com'
                )
            ])
            ->add('category', EntityType::class, [
                'class' => 'BusinessBundle\Entity\Category',
                'choice_label' => 'name'
                ]
            )
            ->add('submit',SubmitType::class, [
                    'attr' => [
                        'class' => 'btn-primary btn-block'
                    ]
                ]
            );
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessBundle\Entity\Business'
        ));
    }
}
