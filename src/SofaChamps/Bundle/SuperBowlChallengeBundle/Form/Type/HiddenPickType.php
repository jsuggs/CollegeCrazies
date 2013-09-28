<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\DataTransformer\PickTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType(alias="hidden_pick")
 */
class HiddenPickType extends AbstractType
{
    private $transformer;

    /**
     * @DI\InjectParams({
     *      "transformer" = @DI\Inject("transformer.pick")
     * })
     */
    public function __construct(PickTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The pick does not exist',
        ));
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'hidden_pick';
    }
}
