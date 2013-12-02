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
            ->add('season')
            ->add('name')
            ->add('shortName')
            ->add('homeTeam')
            ->add('awayTeam')
            ->add('spread')
            ->add('overunder')
            ->add('gameDate')
            ->add('homeTeamScore')
            ->add('awayTeamScore')
            ->add('tiebreakerPriority')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('season')
            ->add('name')
            ->add('homeTeam')
            ->add('awayTeam')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('season')
            ->add('name')
            ->add('homeTeam')
            ->add('awayTeam')
            ->add('spread')
            ->add('overunder')
            ->add('gameDate')
        ;
    }
}
