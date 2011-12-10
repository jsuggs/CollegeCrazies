<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PickFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('confidence', 'integer')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\Pick'
        );
    }

    public function getName()
    {
        return 'team';
    }
}
