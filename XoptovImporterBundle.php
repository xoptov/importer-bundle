<?php

namespace Xoptov\ImporterBundle;

use Xoptov\ImporterBundle\DependencyInjection\Compiler\ProviderPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class XoptovImporterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ProviderPass());
    }
}
