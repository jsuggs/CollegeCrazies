<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', 'hidden')
            ->add('startTime', 'datetime', array(
                'label' => 'Start Time',
            ))
            ->add('closeTime', 'datetime', array(
                'label' => 'Close Time',
            ))
            ->add('nfcTeam', 'entity', array(
                'class' => 'SofaChampsNFLBundle:Team',
                'label' => 'NFC Team',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.conference = ?0')
                        ->orderBy('t.id')
                        ->setParameters(array('NFC'));
                },
            ))
            ->add('afcTeam', 'entity', array(
                'class' => 'SofaChampsNFLBundle:Team',
                'label' => 'AFC Team',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.conference = ?0')
                        ->orderBy('t.id')
                        ->setParameters(array('AFC'));
                },
            ))
            ->add('finalScorePoints', 'integer', array(
                'label' => 'Final Score Points',
            ))
            ->add('halftimeScorePoints', 'integer', array(
                'label' => 'Halftime Score Points',
            ))
            ->add('firstTeamToScoreInAQuarterPoints', 'integer', array(
                'label' => 'First to Score Points',
            ))
            ->add('neitherTeamToScoreInAQuarterPoints', 'integer', array(
                'label' => 'Neither Team scores Points',
            ))
            ->add('bonusQuestionPoints', 'integer', array(
                'label' => 'Bonus Question Points',
            ))
            ->add('questions', 'collection', array(
                'label' => 'Bonus Questions',
                'type' => new QuestionFormType(),
                'prototype' => true,
                'by_reference' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Config',
        ));
    }

    public function getName()
    {
        return 'config';
    }
}
