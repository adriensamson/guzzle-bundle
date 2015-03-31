<?php

namespace AdrienSamson\GuzzleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EmitterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('adriensamson_guzzle.subscriber') as $serviceId => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['client'])) {
                    $configurator = $container->getDefinition('adriensamson_guzzle.base_configurator');
                } else {
                    $configurator = $container->getDefinition(sprintf('adriensamson_guzzle.%s.configurator', $tag['client']));
                }
                $configurator->addMethodCall('addSubscriber', [new Reference($serviceId)]);
            }
        }
    }
}
