<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LeagueAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('id')
                ->add('season')
                ->add('name')
                ->add('motto')
                ->add('password', null, array('required' => false))
                ->add('locked', null, array('required' => false))
            ->end()
            ->with('Members')
                ->add('users')
                ->add('commissioners')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('season')
            ->add('name')
            ->add('motto')
            ->add('password')
            ->add('locked')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('season')
            ->add('name')
        ;
    }
}
