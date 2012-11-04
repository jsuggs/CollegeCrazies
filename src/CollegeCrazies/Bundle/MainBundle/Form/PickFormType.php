<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use CollegeCrazies\Bundle\MainBundle\Form\GameFormType;
use CollegeCrazies\Bundle\MainBundle\Form\DataTransformer\TeamTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('game', 'hidden_game')
            ->add('team', 'hidden_team')
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
