<?php

namespace Gloomy\TwigDecoratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class DecoratorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('decorate.twig.extension')) {
            return;
        }

        $definition = $container->getDefinition(
            'decorate.twig.extension'
        );

        $grabbers = array();
        foreach ($container->findTaggedServiceIds('gloomy.grabber') as $id => $attributes) {
            $alias = isset($attributes[0]['alias'])
                ? $attributes[0]['alias']
                : $id;

            $grabbers[$alias] = $id;
        }

        $decorators = array();
        foreach ($container->findTaggedServiceIds('gloomy.decorator') as $id => $attributes) {
            $alias = isset($attributes[0]['alias'])
                ? $attributes[0]['alias']
                : $id;

            $decorators[$alias] = $id;
        }

        $definition->replaceArgument(1, $grabbers);
        $definition->replaceArgument(2, $decorators);
    }
}