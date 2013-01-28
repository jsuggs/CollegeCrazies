<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\League',
        ));
    }

    public function getName()
    {
        return 'league_lock';
    }
}
