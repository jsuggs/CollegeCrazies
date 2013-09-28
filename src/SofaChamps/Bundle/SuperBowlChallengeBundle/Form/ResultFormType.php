<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType(alias="sbc_result")
 */
class ResultFormType extends AbstractType
{
    protected $repo;
    protected $year;

    /**
     * @DI\InjectParams({
     *      "repo" = @DI\Inject("sofachamps.superbowlchallenge.repo.question_choice"),
     *      "year" = @DI\Inject("config.curyear")
     * })
     */
    public function __construct(EntityRepository $repo, $year)
    {
        $this->repo = $repo;
        $this->year = $year;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', 'hidden')
            ->add('nfcFinalScore', 'integer', array(
                'label' => 'NFC Final Score',
            ))
            ->add('afcFinalScore', 'integer', array(
                'label' => 'AFC Final Score',
            ))
            ->add('nfcHalftimeScore', 'integer', array(
                'label' => 'NFC Halftime Score',
            ))
            ->add('afcHalftimeScore', 'integer', array(
                'label' => 'AFC Halftime Score',
            ))
            ->add('firstTeamToScoreFirstQuarter', 'team_choice', array(
                'label' => 'First Quarter',
            ))
            ->add('firstTeamToScoreSecondQuarter', 'team_choice', array(
                'label' => 'Second Quarter',
            ))
            ->add('firstTeamToScoreThirdQuarter', 'team_choice', array(
                'label' => 'Third Quarter',
            ))
            ->add('firstTeamToScoreFourthQuarter', 'team_choice', array(
                'label' => 'Fourth Quarter',
            ))
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
            ->add('bonusQuestion5', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 5),
            ))
            ->add('bonusQuestion6', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 6),
            ))
            ->add('bonusQuestion7', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 7),
            ))
            ->add('bonusQuestion8', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices($this->year, 8),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Result',
        ));
    }

    public function getName()
    {
        return 'sbc_result';
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
