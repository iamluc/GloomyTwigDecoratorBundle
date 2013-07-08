<?php

namespace Gloomy\TwigDecoratorBundle\Grabber;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ControllerGrabber implements GrabberInterface
{
    protected $container;
    protected $parser;

    public function __construct(ContainerInterface $container, ControllerNameParser $parser)
    {
        $this->container = $container;
        $this->parser = $parser;
    }

    public function getVariables(array $variables)
    {
        if (!isset($variables['_controller'])) {
            throw new \Exception('The _controller variable is required');
        }

        list($class, $method) = explode('::', $this->parser->parse($variables['_controller']));
        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return $controller->$method($variables);
    }
}
