<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SofaChamps\Bundle\CoreBundle\Entity\User;

/**
 * An invite
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="invites"
 * )
 */
class Invite
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="seq_invites", initialValue=1, allocationSize=1)
     */
    protected $id;

    /**
     * Who sent the invite
     *
     * @ORM\ManyToOne(targetEntity="SofaChamps\Bundle\CoreBundle\Entity\User")
     */
    protected $user;

    /**
     * Email the invite was sent to
     *
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $email;

    /**
     * When then invite occurred
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $createdAt;

    public function __construct(User $user, $email)
    {
        $this->user = $user;
        $this->email = $email;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
