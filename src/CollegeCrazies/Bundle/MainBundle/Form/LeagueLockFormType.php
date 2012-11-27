<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LeagueLockFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locked', 'choice', array(
                'choices' => array(
                    true => 'Locked',
                    false => 'Unlocked',
                ),
                'required' => false,
                'expanded' => true,
                'label' => 'Is the league locked',
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
        return 'league_lock';
    }
}
