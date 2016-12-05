<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType(alias="team_choice")
 */
class TeamChoiceFormType extends AbstractType
{
    protected $config;

    /**
     * @DI\InjectParams({
     *      "config" = @DI\Inject("sofachamps.superbowlchallenge.config")
     * })
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'afc' => $this->config->getAfcTeam()->getName(),
                'nfc' => $this->config->getNfcTeam()->getName(),
                'none' => 'Neither team',
            ),
            'empty_value' => 'Choose a Team',
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
