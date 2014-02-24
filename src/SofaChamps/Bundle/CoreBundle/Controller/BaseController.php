<?php

namespace SofaChamps\Bundle\CoreBundle\Controller;

use SofaChamps\Bundle\CoreBundle\Entity\Person;
use SofaChamps\Bundle\CoreBundle\Form\PersonFormType;

class BaseController extends CoreController
{
    public function getPersonForm(Person $person = null)
    {
        return $this->createForm('core_person', $person);
    }
}
