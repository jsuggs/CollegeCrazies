<?php

namespace CollegeCrazies\Bundle\MainBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LeagueCommissionersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $league = $options['league'];
        $builder
            ->add('commissioners', 'entity', array(
                'multiple' => true,
                'expanded' => true,
                'class' => 'CollegeCraziesMainBundle:User',
                'query_builder' => function(EntityRepository $er) use ($league) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin('u.leagues', 'l')
                        ->where('l.id = :leagueId')
                        ->setParameter('leagueId', $league->getId())
                        ->orderBy('u.username', 'ASC');
                },
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CollegeCrazies\Bundle\MainBundle\Entity\League',
            'invalid_message' => 'The game does not exist',
            'league' => null,
        ));
    }

    public function getName()
    {
        return 'league_commissioners';
    }
}
