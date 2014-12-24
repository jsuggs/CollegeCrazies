<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SeasonAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('season')
                ->add('picksLockAt', null, array('required' => false))
                ->add('locked', null, array('required' => false))
            ->end()
            ->with('Points')
                ->add('gamePoints')
                ->add('championshipPoints')
            ->end()
            ->with('Championship/Playoffs')
                ->add('hasChampionship', null, array('required' => false))
                ->add('championshipWinner')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('season')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('season')
            ->add('locked')
            ->add('picksLockAt')
        ;
    }
}
