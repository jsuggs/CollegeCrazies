<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('name', 'text')
            ->add('network', 'text')
            ->add('gamedate', 'datetime')
            ->add('homeTeam', 'entity', array(
                'class' => 'SofaChampsBowlPickemBundle:Team',
            ))
            ->add('awayTeam', 'entity', array(
                'class' => 'SofaChampsBowlPickemBundle:Team',
            ))
            ->add('homeTeamScore', 'integer', array('required' => false))
            ->add('awayTeamScore', 'integer', array('required' => false))
            ->add('spread', 'number')
            ->add('overunder', 'integer')
            ->add('description', 'textarea', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\Game',
        ));
    }

    public function getName()
    {
        return 'game_edit';
    }
}
