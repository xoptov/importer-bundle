<?php

namespace KTD\ImporterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('importer.provider_manager')) {
            return;
        }

        $providerManager = $container->findDefinition('importer.provider_manager');

        foreach ($container->findTaggedServiceIds('importer.provider') as $id => $tags) {
            $provider = new Reference($id);
            $providerManager->addMethodCall('addProvider', array($provider));
        }
        
        foreach ($container->findTaggedServiceIds('importer.provider.phase') as $id => $tags) {
            foreach ($tags as $tag) {
                if (array_key_exists('provider', $tag)) {
                    if (array_key_exists('priority', $tag)) {
                        $definition = $container->getDefinition($id);
                        $definition->addMethodCall('setPriority', array($tag['priority']));
                    }
                    $phase = new Reference($id);
                    $providerManager->addMethodCall('addPhase', array($tag['provider'], $phase));
                }
            }
        }
    }
}