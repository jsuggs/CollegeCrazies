<?php

namespace SofaChamps\Bundle\SecurityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RequestProcessorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sofachamps.component.authentication.handler.login_success_handler')) {
            return;
        }

        $processors = new \SplPriorityQueue();
        foreach ($container->findTaggedServiceIds('sofachamps.request_processor') as $id => $attributes) {
            $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
            $processors->insert(new Reference($id), $priority);
        }

        $processors = iterator_to_array($processors);
        krsort($processors);

        $definition = $container->getDefinition('sofachamps.component.authentication.handler.login_success_handler');

        foreach ($processors as $processor) {
            $definition->addMethodCall('addRequestProcessor', array($processor));
        }
    }
}
