<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A module on the sofachamps platform
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="core_module"
 * )
 */
class Module
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5)
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="ModuleConfig", mappedBy="module")
     */
    protected $configs;

    /**
     * @ORM\Column(type="integer")
     */
    protected $activeConfigId;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addConfig(ModuleConfig $config)
    {
        $this->configs[] = $config;
    }
}
