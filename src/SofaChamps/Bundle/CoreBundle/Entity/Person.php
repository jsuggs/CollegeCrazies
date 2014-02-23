<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A person
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
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $lastName;
    protected $birthDate;
    protected $birthPlace;
}
