<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class GameEditFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('name', 'text')
            ->add('network', 'text')
            ->add('gamedate', 'datetime')
            ->add('homeTeamScore', 'integer')
            ->add('awayTeamScore', 'integer')
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
