<?php

namespace SofaChamps\Bundle\SquaresBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\CoreBundle\Entity\User;
use SofaChamps\Bundle\SquaresBundle\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @DI\FormType("sofachamps_squares_player")
 */
class PlayerFormType extends AbstractType
{
    private $om;
    private $securityContext;

    /**
     * @DI\InjectParams({
     *      "om" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *      "securityContext" = @DI\Inject("security.context"),
     * })
     */
    public function __construct(ObjectManager $om, SecurityContext $securityContext)
    {
        $this->om = $om;
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Display Name',
                'max_length' => 10,
            ))
            ->add('color', 'text', array(
                'label' => 'Color',
                'max_length' => 6,
            ))
            ->add('admin', 'checkbox', array(
                'label' => 'Admin',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $om = $this->om;
        $user = $this->getUser();
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SquaresBundle\Entity\Player',
            'empty_data' => function (FormInterface $form) use ($om, $user) {
                // Get the game
                $game = $form->getParent()->getParent()->getData();
                // Create the payout
                $player = new Player($user, $game);
                // Persist
                $om->persist($player);

                return $player;
            },
        ));
    }

    public function getName()
    {
        return 'sofachamps_squares_player';
    }

    protected function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }
}
