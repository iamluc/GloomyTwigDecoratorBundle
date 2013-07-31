<?php

namespace Gloomy\TwigDecoratorBundle\Decorator;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ControllerDecorator implements DecoratorInterface
{
    protected $container;
    protected $parser;

    public function __construct(ContainerInterface $container, ControllerNameParser $parser)
    {
        $this->container = $container;
        $this->parser = $parser;
    }

    public function getTemplate(array $variables)
    {
        return isset($variables['_template']) ? $variables['_template'] : null;
    }

    public function getVariables(array $variables)
    {
        if (!isset($variables['_controller'])) {
            return $variables;
        }

        list($class, $method) = explode('::', $this->parser->parse($variables['_controller']));
        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return $controller->$method($variables);
    }
}
