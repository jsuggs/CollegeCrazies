<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Controller;

use SofaChamps\Bundle\SuperBowlChallengeBundle\Entity\Pick;
use SofaChamps\Bundle\SuperBowlChallengeBundle\Form\PickFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected function getPickForm(Pick $pick = null)
    {
        return $this->createForm(new PickFormType(), $pick);
    }
}
