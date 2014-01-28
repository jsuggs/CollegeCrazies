<?php

namespace SofaChamps\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RequestProcessorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('sofachamps.component.authentication.handler.login_success_handler')) {
            $loginProcessors = new \SplPriorityQueue();
            foreach ($container->findTaggedServiceIds('sofachamps.request_processor.login') as $id => $attributes) {
                $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
                $loginProcessors->insert(new Reference($id), $priority);
            }

            $loginProcessors = iterator_to_array($loginProcessors);
            krsort($loginProcessors);

            $definition = $container->getDefinition('sofachamps.component.authentication.handler.login_success_handler');

            foreach ($loginProcessors as $processor) {
                $definition->addMethodCall('addRequestProcessor', array($processor));
            }
        }

        if ($container->hasDefinition('sofachamps.listener.registration_listener')) {
            $registrationProcessors = new \SplPriorityQueue();
            foreach ($container->findTaggedServiceIds('sofachamps.request_processor.registration') as $id => $attributes) {
                $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
                $registrationProcessors->insert(new Reference($id), $priority);
            }

            $registrationProcessors = iterator_to_array($registrationProcessors);
            krsort($registrationProcessors);

            $definition = $container->getDefinition('sofachamps.listener.registration_listener');

            foreach ($registrationProcessors as $processor) {
                $definition->addMethodCall('addRequestProcessor', array($processor));
            }
        }
    }
}
