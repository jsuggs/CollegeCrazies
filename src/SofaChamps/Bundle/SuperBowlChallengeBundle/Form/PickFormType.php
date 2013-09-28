<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form;

use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType(alias="sbc_pick")
 */
class PickFormType extends AbstractType
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
