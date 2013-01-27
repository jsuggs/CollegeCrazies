<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Form\Type;

use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\DataTransformer\QuestionTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HiddenQuestionType extends AbstractType
{
    private $transformer;

    public function __construct(QuestionTransformer $transformer)
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
            'invalid_message' => 'The question does not exist',
        ));
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'hidden_question';
    }
}
