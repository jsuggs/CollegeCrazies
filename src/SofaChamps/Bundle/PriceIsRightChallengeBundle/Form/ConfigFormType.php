<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Form;

/**
 * @DI\FormType(alias="pirc_config")
 */
class ConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seed1Cost', 'integer', array(
                'label' => '#1 Seed Cost',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Config',
        ));
    }

    public function getName()
    {
        return 'pirc_config';
    }
}

