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

        $definition = $container->getDefinition(
            'sofachamps.component.authentication.handler.login_success_handler'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'sofachamps.request_processor'
        );
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addRequestProcessor',
                array(new Reference($id))
            );
        }
    }
}
