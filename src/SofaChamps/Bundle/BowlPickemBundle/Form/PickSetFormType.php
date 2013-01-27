<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use SofaChamps\Bundle\BowlPickemBundle\Form\UserFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\PickFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PickSetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'max_length' => 50,
            ))
            ->add('tiebreakerHomeTeamScore', 'integer', array(
                'required' => false,
            ))
            ->add('tiebreakerAwayTeamScore', 'integer', array(
                'required' => false,
            ))
            ->add('picks', 'collection', array(
                'type' => new PickFormType(),
                'allow_add' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet',
        );
    }

    public function getName()
    {
        return 'pickset';
    }
}
