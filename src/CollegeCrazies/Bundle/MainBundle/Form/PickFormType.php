<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use CollegeCrazies\Bundle\MainBundle\Form\GameFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PickFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('game', new GameFormType())
            ->add('team', new TeamFormType())
            ->add('confidence', 'hidden')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\Pick'
        );
    }

    public function getName()
    {
        return 'pick';
    }
}
