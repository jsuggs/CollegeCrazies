<?php

namespace SofaChamps\Bundle\SquaresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameResultsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('payouts', 'collection', array(
                'type' => 'sofachamps_squares_result',
                'by_reference' => false,
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
