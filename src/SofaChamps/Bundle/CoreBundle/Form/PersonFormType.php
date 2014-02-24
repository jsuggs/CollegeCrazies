<?php

namespace SofaChamps\Bundle\CoreBundle\Form;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType(alias="core_person")
 */
class PersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array(
                'label' => 'First Name',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Last Name',
            ))
            ->add('birthDate', 'date', array(
                'label' => 'Birth Date',
                'widget' => 'text',
            ))
            ->add('birthPlace', 'text', array(
                'label' => 'Birth Place',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\CoreBundle\Entity\Person',
        ));
    }

    public function getName()
    {
        return 'core_person';
    }
}
