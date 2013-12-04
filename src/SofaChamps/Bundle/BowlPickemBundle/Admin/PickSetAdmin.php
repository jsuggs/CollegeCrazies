<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PickSetAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('season')
            ->add('tiebreakerHomeTeamScore')
            ->add('tiebreakerAwayTeamScore')
            ->add(
                'leagues',
                'sonata_type_collection',
                array(
                    'required' => false,
                    'by_reference' => false,
                ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
                )
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('season')
            ->add('user')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('season')
            ->add('user')
            ->add('name')
        ;
    }
}
