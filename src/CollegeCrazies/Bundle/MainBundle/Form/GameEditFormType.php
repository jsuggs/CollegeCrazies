<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GameEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('name', 'text')
            ->add('network', 'text')
            ->add('gamedate', 'datetime')
            ->add('homeTeamScore', 'integer', array('required' => false))
            ->add('awayTeamScore', 'integer', array('required' => false))
            ->add('description', 'textarea', array('required' => false))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\Game'
        );
    }

    public function getName()
    {
        return 'game_edit';
    }
}
