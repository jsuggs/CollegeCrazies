<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\Team'
        );
    }

    public function getName()
    {
        return 'team';
    }
}
