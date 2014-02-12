<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType(alias="pirc_config")
 */
class ConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach (range(1, 16) as $seed) {
            $builder->add(sprintf('seed%dCost', $seed), 'integer', array(
                'label' => sprintf('#%d Seed Cost', $seed),
            ));
        }

        foreach (range(1, 6) as $round) {
            $builder->add(sprintf('round%dWin', $round), 'integer', array(
                'label' => sprintf('Round %d Win', $round),
            ));
        }
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

