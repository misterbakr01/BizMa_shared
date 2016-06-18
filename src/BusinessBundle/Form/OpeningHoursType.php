<?php

namespace BusinessBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeningHoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('open',HiddenType::class)
                ->add('close',HiddenType::class)
                ->add('slider',TextType::class,array('label'=>false))
                ->add('day',HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessBundle\Entity\OpeningHours',
        ));
    }

    public function getName()
    {
        return 'business_bundle_opening_hours';
    }
}
