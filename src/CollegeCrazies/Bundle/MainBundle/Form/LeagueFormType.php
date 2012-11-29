<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LeagueFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('motto', 'textarea')
            ->add('password', 'text', array(
                'required' => false,
            ))
            ->add('public', 'choice', array(
                'expanded' => true,
                'choices' => array(
                    true => 'Public',
                    false => 'Private',
                ),
            ))
            ->add('commissioners', 'entity', array(
                'multiple' => true,
                'class' => 'CollegeCraziesMainBundle:User',
            ))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\League'
        );
    }

    public function getName()
    {
        return 'league';
    }
}
