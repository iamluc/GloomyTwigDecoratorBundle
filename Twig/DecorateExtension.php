<?php

namespace Gloomy\TwigDecoratorBundle\Twig;

use Gloomy\TwigDecoratorBundle\Twig\TokenParser\DecorateTokenParser;

use Gloomy\TwigDecoratorBundle\Decorator\DecoratorInterface;

class DecorateExtension extends \Twig_Extension
{
    protected $environment;

    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getName()
    {
        return 'DecorateExtension';
    }

    public function getTokenParsers()
    {
        return array(
            new DecorateTokenParser($this->container, $this),
        );
    }

    public function getTemplate($serviceId, $variables)
    {
        return $this->getDecorator($serviceId)->getTemplate($variables);
    }

    public function getVariables($serviceId, array $variables, \Twig_Template $template)
    {
        $variables = $this->getDecorator($serviceId)->getVariables($variables);
        if (!is_array($variables)) {
            throw new \Exception('The decorator must return an array');
        }

        foreach ($variables as $name => $value) {
            $template->getEnvironment()->addGlobal($name, $value);
        }
    }

    protected function getDecorator($serviceId)
    {
        $decorator = $this->container->get($serviceId);
        if (!$decorator instanceof DecoratorInterface) {
            throw new \Exception('The decorator must implement DecoratorInterface');
        }

        return $decorator;
    }
}
