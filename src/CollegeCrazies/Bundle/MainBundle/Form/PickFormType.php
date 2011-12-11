<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PickFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('team', 'choice', array(
                'choices' => array(
                    'homeTeam' => 'Team A',
                    'awayTeam' => 'Team B',
                ),
                'expanded' => true,
            ))
            ->add('confidence', 'choice', array(
                'choices' => $this->getConfidenceChoices(5)
            ))
        ;
    }

    public function getConfidenceChoices($size)
    {
        $choices = array();
        for ($x = 1; $x <= $size; $x++) {
            $choices[$x] = $x;
        }
        return $choices;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\Pick'
        );
    }

    public function getName()
    {
        return 'team';
    }
}
