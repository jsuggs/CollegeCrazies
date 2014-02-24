<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A person, no more no less
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="core_people"
 * )
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_core_people", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=32)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=32)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $birthPlace;

    public function getId()
    {
        return $this->id;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setBirtDate(\DateTime $birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;
    }

    public function getBirthPlace()
    {
        return $this->birthPlace;
    }
}
