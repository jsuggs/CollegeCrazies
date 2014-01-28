<?php

namespace SofaChamps\Bundle\SecurityBundle;

use SofaChamps\Bundle\SecurityBundle\DependencyInjection\Compiler\RequestProcessorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SofaChampsSecurityBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RequestProcessorCompilerPass());
    }
}
