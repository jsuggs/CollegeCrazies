<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType(alias="pirc_game")
 */
class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Game Name',
            ))
            ->add('config', new ConfigFormType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Game',
        ));
    }

    public function getName()
    {
        return 'pirc_game';
    }
}

