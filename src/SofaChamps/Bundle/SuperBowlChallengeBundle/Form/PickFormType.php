<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PickFormType extends AbstractType
{
    protected $repo;
    protected $year;

    public function __construct(EntityRepository $repo, $year)
    {
        $this->repo = $repo;
        $this->year = $year;
    }

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
            ->add('bonusQuestion1', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 1),
            ))
            ->add('bonusQuestion2', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 2),
            ))
            ->add('bonusQuestion3', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 3),
            ))
            ->add('bonusQuestion4', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 4),
            ))
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
        return 'sbc_pick';
    }

    protected function getChoices($year, $index)
    {
        $questions = $this->repo->createQueryBuilder('c')
            ->where('c.index = :index AND c.year = :year')
            ->setParameter('index', $index)
            ->setParameter('year', $year)
            ->getQuery()
            ->execute();

        $choices = array();
        foreach ($questions as $question) {
            $choices[$question->getId()] = $question->getText();
        }

        return $choices;
    }
}
