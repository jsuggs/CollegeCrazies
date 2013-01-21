<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', 'hidden')
            ->add('nfcFinalScore', 'integer')
            ->add('afcFinalScore', 'integer')
            ->add('nfcHalftimeScore', 'integer')
            ->add('afcHalftimeScore', 'integer')
            ->add('firstTeamToScoreFirstQuarter', 'team_choice')
            ->add('firstTeamToScoreSecondQuarter', 'team_choice')
            ->add('firstTeamToScoreThirdQuarter', 'team_choice')
            ->add('firstTeamToScoreFourthQuarter', 'team_choice')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick',
        ));
    }

    public function getName()
    {
        return 'pick';
    }
}
