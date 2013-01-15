<?php

namespace SofaChamps\Bundle\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Question component with a text response type
 *
 * @ORM\Entity
 */
class TextComponent extends AbstractQuestionComponent
{
    public function getResponseType()
    {
        return 'text';
    }
}
