<?php

namespace CollegeCrazies\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Additional Fields
        $builder
            ->add('emailVisible', 'checkbox')
            ->add('firstName', 'text')
            ->add('lastName', 'text');
    }

    public function getName()
    {
        return 'college_crazies_user_registration';
    }
}
