<?php

namespace SofaChamps\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vlabs\MediaBundle\Entity\BaseFile as VlabsFile;

/**
 * An image
 *
 * @ORM\Entity
 * @ORM\Table(
 *      name="core_images"
 * )
 */
class Image extends VlabsFile
{
    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255)
     * @Assert\Image()
     */
    private $path;

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
