<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Admin;

use SofaChamps\Bundle\BowlPickemBundle\Game\GameManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GameAdmin extends Admin
{
    protected $gameManager;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('season')
            ->add('name')
            ->add('description')
            ->add('shortName')
            ->add('homeTeam')
            ->add('awayTeam')
            ->add('spread')
            ->add('overunder')
            ->add('gameDate')
            ->add('homeTeamScore')
            ->add('awayTeamScore')
            ->add('championshipGame', null, array('required' => false))
            ->add('tiebreakerPriority', null, array('required' => false))
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

    public function preUpdate($game)
    {
        $this->getGameManager()->updateGame($game);
    }

    public function setGameManager(GameManager $gameManager)
    {
        $this->gameManager = $gameManager;
    }

    public function getGameManager()
    {
        return $this->gameManager;
    }
}
