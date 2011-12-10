<?php

namespace CollegeCrazies\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A User
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="users"
 * )
 */
class User
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length="5")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length="255")
     * @Assert\Email
     */
    protected $email;

    protected $password;

    protected $leagues;
    protected $picks;
}
