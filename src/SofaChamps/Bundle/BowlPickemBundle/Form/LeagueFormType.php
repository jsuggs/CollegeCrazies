<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LeagueFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('motto', 'textarea', array(
                'required' => false,
            ))
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
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\League',
        ));
    }

    public function getName()
    {
        return 'league';
    }
}
