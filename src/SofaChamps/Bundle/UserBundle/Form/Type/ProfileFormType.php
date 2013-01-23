<?php

namespace SofaChamps\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Additional Fields
        $builder
            ->add('emailVisible', 'checkbox', array(
                'required' => false,
                'label' => 'Email visible in leagues',
            ))
            ->add('firstName', 'text', array(
                'required' => false,
                'label' => 'First Name',
            ))
            ->add('lastName', 'text', array(
                'required' => false,
                'label' => 'Last Name',
            ))
            ->add('emailFromCommish', 'checkbox', array(
                'required' => false,
                'label' => 'Receive Emails From Commissioners',
            ))
        ;
    }

    public function getName()
    {
        return 'college_crazies_user_profile';
    }
}
