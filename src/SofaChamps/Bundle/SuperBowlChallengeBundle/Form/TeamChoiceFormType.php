<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamChoiceFormType extends AbstractType
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'afc' => 'AFC',
                'nfc' => 'NFC',
                'none' => 'NONE',
            ),
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'team_choice';
    }
}
