<?php

namespace AdrienSamson\GuzzleBundle;

use AdrienSamson\GuzzleBundle\DependencyInjection\AdrienSamsonGuzzleExtension;
use AdrienSamson\GuzzleBundle\DependencyInjection\EmitterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AdrienSamsonGuzzleBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new AdrienSamsonGuzzleExtension();
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EmitterCompilerPass());
    }
}
