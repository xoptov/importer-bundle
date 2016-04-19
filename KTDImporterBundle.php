<?php

namespace KTD\ImporterBundle;

use KTD\ImporterBundle\DependencyInjection\Compiler\ProviderPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KTDImporterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ProviderPass());
    }
}
