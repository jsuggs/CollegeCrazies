<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Multiple Choice Question Choice
 *
 * ORM\Entity
 */
class TextQuestionChoice
{
    public function getType()
    {
        return 'text';
    }
}
