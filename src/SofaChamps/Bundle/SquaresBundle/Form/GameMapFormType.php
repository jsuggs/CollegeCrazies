<?php

namespace SofaChamps\Bundle\SquaresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameMapFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('row0', 'integer', array(
                'label' => 'Row 0',
            ))
            ->add('row1', 'integer', array(
                'label' => 'Row 1',
            ))
            ->add('row2', 'integer', array(
                'label' => 'Row 2',
            ))
            ->add('row3', 'integer', array(
                'label' => 'Row 3',
            ))
            ->add('row4', 'integer', array(
                'label' => 'Row 4',
            ))
            ->add('row5', 'integer', array(
                'label' => 'Row 5',
            ))
            ->add('row6', 'integer', array(
                'label' => 'Row 6',
            ))
            ->add('row7', 'integer', array(
                'label' => 'Row 7',
            ))
            ->add('row8', 'integer', array(
                'label' => 'Row 8',
            ))
            ->add('row9', 'integer', array(
                'label' => 'Row 9',
            ))
            ->add('col0', 'integer', array(
                'label' => 'Col 0',
            ))
            ->add('col1', 'integer', array(
                'label' => 'Col 1',
            ))
            ->add('col2', 'integer', array(
                'label' => 'Col 2',
            ))
            ->add('col3', 'integer', array(
                'label' => 'Col 3',
            ))
            ->add('col4', 'integer', array(
                'label' => 'Col 4',
            ))
            ->add('col5', 'integer', array(
                'label' => 'Col 5',
            ))
            ->add('col6', 'integer', array(
                'label' => 'Col 6',
            ))
            ->add('col7', 'integer', array(
                'label' => 'Col 7',
            ))
            ->add('col8', 'integer', array(
                'label' => 'Col 8',
            ))
            ->add('col9', 'integer', array(
                'label' => 'Col 9',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SofaChamps\Bundle\SquaresBundle\Entity\Game',
        ));
    }

    public function getName()
    {
        return 'game_map';
    }
}
