<?php

namespace SofaChamps\Bundle\CoreBundle\Admin;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('email')
                ->add('username')
                ->add('referrer')
                ->add('plainPassword', 'text', array(
                    'required' => false,
                    'label' => 'Password',
                    'help' => 'Update the users password',
                ))
            ->end()
            ->with('Bowl Pickem Leagues')
                ->add('leagues', 'sonata_type_collection', array(), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position'
                ))
            ->end()
            ->with('User Status')
                ->add('enabled', null, array('required' => false))
                ->add('expired', null, array('required' => false))
                ->add('locked', null, array('required' => false))
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('enabled')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            //->addIdentifier('username')
            ->add('enabled')
            ->add('locked')
            ->add('expired')
            ->add('username', 'impersonate', array(
                'template' => 'SofaChampsCoreBundle:Admin:User\impersonate.html.twig',
                'label' => 'Impersonate',
            ))
        ;
    }

    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function getUserManager()
    {
        return $this->userManager;
    }
}
