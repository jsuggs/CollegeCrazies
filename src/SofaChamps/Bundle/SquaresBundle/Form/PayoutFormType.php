<?php

namespace SofaChamps\Bundle\SquaresBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SquaresBundle\Entity\Payout;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType("sofachamps_squares_payout")
 */
class PayoutFormType extends AbstractType
{
    private $om;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seq', 'integer', array(
                'label' => 'The order of the payouts',
            ))
            ->add('description', 'text', array(
                'label' => 'What the payout is for',
            ))
            ->add('percentage', 'integer', array(
                'label' => 'The percentage this wins',
            ))
            ->add('homeTeamResult', 'integer', array(
                'label' => 'The home team result',
                'required' => false,
            ))
            ->add('awayTeamResult', 'integer', array(
                'label' => 'The away team result',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $om = $this->om;
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SquaresBundle\Entity\Payout',
            'empty_data' => function (FormInterface $form) use ($om) {
                // Get the game
                $game = $form->getParent()->getParent()->getData();
                $curPayouts = $game->getPayouts()->count();

                // Create the payout
                $payout = new Payout($game, $curPayouts + 1);

                // Persist
                $om->persist($payout);

                return $payout;
            },
        ));
    }

    public function getName()
    {
        return 'sofachamps_squares_payout';
    }
}
