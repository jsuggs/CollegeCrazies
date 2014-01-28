<?php

namespace SofaChamps\Bundle\CoreBundle;

use SofaChamps\Bundle\CoreBundle\DependencyInjection\Compiler\RequestProcessorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SofaChampsCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RequestProcessorCompilerPass());
    }
}
