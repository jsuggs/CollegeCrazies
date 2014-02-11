<?php

namespace SofaChamps\Bundle\PriceIsRightChallengeBundle\Form;

/**
 * @DI\FormType(alias="pirc_portfolio")
 */
class PortfolioType extends AbstractType
{
    protected $repo;

    /**
     * @DI\InjectParams({
     *      "repo" = @DI\Inject("sofachamps.mm.repo.bracket"),
     * })
     */
    public function __construct(EntityRepository $repo)
    {
        $this->repo = $repo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teams', 'choice', array(
                'expanded' => true,
                'choices' => $this->getChoices(2013),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\PriceIsRightChallengeBundle\Entity\Portfolio',
        ));
    }

    public function getName()
    {
        return 'pirc_portfolio';
    }

    protected function getChoices($season)
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

