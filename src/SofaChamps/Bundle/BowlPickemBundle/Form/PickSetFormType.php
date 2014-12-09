<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Form;

use SofaChamps\Bundle\BowlPickemBundle\Form\UserFormType;
use SofaChamps\Bundle\BowlPickemBundle\Form\PickFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PickSetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'max_length' => 50,
            ))
            ->add('picks', 'collection', array(
                'type' => new PickFormType(),
                'allow_add' => true,
            ))
        ;

        if ($options['hasChampionship']) {
            $builder
                ->add('championshipWinner', 'entity', array(
                    'class' => 'SofaChampsNCAABundle:Team',
                    'query_builder' => function ($repo) {
                        $teams = array('ALA', 'FLST', 'ORE', 'OHST');
                        return $repo->createQueryBuilder('t')
                            ->where('t.id IN (:teams)')
                            ->setParameter('teams', $teams);
                    },
                    'required' => false,
                ));
        } else {
            $builder
                ->add('tiebreakerHomeTeamScore', 'integer', array(
                    'required' => false,
                ))
                ->add('tiebreakerAwayTeamScore', 'integer', array(
                    'required' => false,
                ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\BowlPickemBundle\Entity\PickSet',
            'hasChampionship' => false,
        ));
    }

    public function getName()
    {
        return 'pickset';
    }
}
