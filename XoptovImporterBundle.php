<?php

namespace Xoptov\ImporterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Xoptov\ImporterBundle\DependencyInjection\Compiler\ProvidersPass;

class XoptovImporterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ProvidersPass());
    }
}
