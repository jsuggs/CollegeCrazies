<?php

namespace SofaChamps\Bundle\SquaresBundle\Form;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType("sofachamps_squares_result")
 */
class ResultFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('homeTeamResult', 'integer', array(
                'label' => 'The home team result',
                'required' => false,
            ))
            ->add('awayTeamResult', 'integer', array(
                'label' => 'The away team result',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SquaresBundle\Entity\Payout',
        ));
    }

    public function getName()
    {
        return 'sofachamps_squares_result';
    }
}
