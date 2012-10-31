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
            ->add('password', 'text')
            ->add('public', 'checkbox')
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
