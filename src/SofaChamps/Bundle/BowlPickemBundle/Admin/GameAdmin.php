<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GameAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('name')
            ->add('homeTeam')
            ->add('awayTeam')
            ->add('spread')
            ->add('overunder')
            ->add('gameDate')
            ->add('homeTeamScore')
            ->add('awayTeamScore')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('homeTeam')
            ->add('awayTeam')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('homeTeam')
            ->add('awayTeam')
            ->add('spread')
            ->add('overunder')
            ->add('gameDate')
        ;
    }
}
