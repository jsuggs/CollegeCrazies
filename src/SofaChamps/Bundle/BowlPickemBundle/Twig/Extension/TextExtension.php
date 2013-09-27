<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Twig\Extension;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TextExtension
 *
 * @DI\Service("sofachamps.bp.twig.text")
 * @DI\Tag("twig.extension")
 */
class TextExtension extends \Twig_Extension
{
    private $container;

    /**
     * @DI\InjectParams({
     *      "container" = @DI\Inject("service_container")
     * })
     */
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
        return 'sofachamps.bp.text';
    }
}
