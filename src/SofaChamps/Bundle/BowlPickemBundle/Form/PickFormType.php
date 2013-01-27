<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use SofaChamps\Bundle\BowlPickemBundle\Form\GameFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\DataTransformer\TeamTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\Pick',
        );
    }

    public function getName()
    {
        return 'pick';
    }
}
