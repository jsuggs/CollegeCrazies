<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use CollegeCrazies\Bundle\MainBundle\Entity\User;
use CollegeCrazies\Bundle\MainBundle\Entity\League;
use CollegeCrazies\Bundle\MainBundle\Entity\Pick;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Users Pick for a single game
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="picksets"
 * )
 */
class PickSet {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="pickset_seq", initialValue=1, allocationSize=100)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Pick", mappedBy="pickSet")
     */
    protected $picks;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setPicks($picks)
    {
        $this->picks = $picks;
    }

    public function addPick(Pick $pick)
    {
        $this->picks[] = $pick;
    }

    public function getPicks()
    {
        return $this->picks;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
