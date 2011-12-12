<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A User
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="users"
 * )
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_seq", initialValue=1, allocationSize=100)
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="League", inversedBy="users")
     */
    protected $leagues;

    /**
     * @ORM\OneToOne(targetEntity="PickSet")
     */
    protected $pickSet;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPickSet()
    {
        return $this->pickSet;
    }

    public function __toString()
    {
        return $this->username;
    }
}
