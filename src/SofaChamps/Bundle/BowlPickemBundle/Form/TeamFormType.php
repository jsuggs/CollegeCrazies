<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', array(
                'max_length' => 5,
            ))
            ->add('name', 'text')
            ->add('thumbnail', 'text')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\Team'
        );
    }

    public function getName()
    {
        return 'team';
    }
}
