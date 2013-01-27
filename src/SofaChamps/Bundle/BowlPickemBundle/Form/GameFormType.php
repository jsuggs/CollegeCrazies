<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            //->add('name', 'text')
            //->add('homeTeam', new TeamFormType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\Game',
        );
    }

    public function getName()
    {
        return 'game';
    }
}
