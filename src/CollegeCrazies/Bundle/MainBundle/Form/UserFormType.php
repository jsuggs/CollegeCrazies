<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('username', 'text')
            ->add('email', 'text')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\User'
        );
    }

    public function getName()
    {
        return 'user';
    }
}
