<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TextExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'truncate_text' => new \Twig_Function_Method($this, 'truncateText'),
        );
    }

    public function truncateText($text, $maxLen, $additionalText = '...')
    {
        return $this->container->get('text.manipulator')->truncateText($text, $maxLen, $additionalText = '...');
    }

    public function getName()
    {
        return 'sofachamps.text';
    }
}
