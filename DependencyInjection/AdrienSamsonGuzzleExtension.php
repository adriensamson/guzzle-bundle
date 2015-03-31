<?php

namespace AdrienSamson\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AdrienSamsonGuzzleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('data_collector.xml');

        $container->setDefinition('adriensamson_guzzle.base_configurator', new Definition('AdrienSamson\GuzzleBundle\ClientConfigurator'));

        foreach ($config['clients'] as $name => $client) {
            $configurator = new Definition('AdrienSamson\GuzzleBundle\ClientConfigurator', [new Reference('adriensamson_guzzle.base_configurator')]);
            $container->setDefinition(sprintf('adriensamson_guzzle.configurator.%s', $name), $configurator);

            $definition = new Definition('GuzzleHttp\Client', [$client['config']]);
            $definition->setConfigurator([new Reference(sprintf('adriensamson_guzzle.configurator.%s', $name)), 'configure']);
            $container->setDefinition(sprintf('adriensamson_guzzle.client.%s', $name), $definition);
        }
    }

    public function getAlias()
    {
        return 'adriensamson_guzzle';
    }
}
