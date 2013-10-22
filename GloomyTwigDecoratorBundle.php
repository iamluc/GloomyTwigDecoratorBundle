<?php

namespace Gloomy\TwigDecoratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Gloomy\TwigDecoratorBundle\DependencyInjection\Compiler\DecoratorCompilerPass;

class GloomyTwigDecoratorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DecoratorCompilerPass());
    }
}
