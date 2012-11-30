<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LeagueCommissionersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commissioners', 'entity', array(
                'multiple' => true,
                'expanded' => true,
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
        return 'league_commissioners';
    }
}
