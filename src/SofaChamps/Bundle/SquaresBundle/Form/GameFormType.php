<?php

namespace SofaChamps\Bundle\SquaresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'The Square Name (have fun, be creative)',
            ))
            ->add('homeTeam', 'text', array(
                'label' => 'The Home team name',
            ))
            ->add('awayTeam', 'text', array(
                'label' => 'The Away team name',
            ))
            ->add('costPerSquare', 'money', array(
                'label' => 'Cost Per Square',
                'currency' => 'USD',
                'divisor' => 100,
            ))
            ->add('payouts', 'collection', array(
                'type' => 'sofachamps_squares_payout',
                'allow_add' => true,
            ))
            ->add('locked', 'checkbox', array(
                'label' => 'Lock the game',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SquaresBundle\Entity\Game',
        ));
    }

    public function getName()
    {
        return 'game';
    }
}
