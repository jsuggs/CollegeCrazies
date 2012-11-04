<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A Team
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="teams"
 * )
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $name;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return sprintf('%s - %s', $this->id, $this->name);
    }
}
