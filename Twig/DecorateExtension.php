<?php

namespace Gloomy\TwigDecoratorBundle\Twig;

use Gloomy\TwigDecoratorBundle\Twig\TokenParser\DecorateTokenParser;
use Gloomy\TwigDecoratorBundle\Twig\TokenParser\GrabTokenParser;

use Gloomy\TwigDecoratorBundle\Grabber\GrabberInterface;
use Gloomy\TwigDecoratorBundle\Decorator\DecoratorInterface;

class DecorateExtension extends \Twig_Extension
{
    const DEFAULT_TEMPLATE = 'GloomyTwigDecoratorBundle::render_blocks.html.twig';

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
            new GrabTokenParser(),
            new DecorateTokenParser($this->container, $this),
        );
    }

    public function getTemplate($serviceId, $variables)
    {
        $service = $this->getService($serviceId);
        if (!$service instanceof DecoratorInterface) {
            throw new \Exception('The decorator must implement DecoratorInterface');
        }

        $template = $service->getTemplate($variables);
        if (null === $template) {
            $template = self::DEFAULT_TEMPLATE;
        }

        return $template;
    }

    public function getVariables($serviceId, array $variables, \Twig_Template $template)
    {
        $service = $this->getService($serviceId);
        if (!$service instanceof DecoratorInterface && !$service instanceof GrabberInterface) {
            throw new \Exception('The service must implement DecoratorInterface or GrabberInterface');
        }

        $variables = $service->getVariables($variables);
        if (!is_array($variables)) {
            throw new \Exception('The decorator must return an array');
        }

        foreach ($variables as $name => $value) {
            $template->getEnvironment()->addGlobal($name, $value);
        }

        // list of all blocks to render in self::DEFAULT_TEMPLATE (if the user did not give a template)
        $template->getEnvironment()->addGlobal('__blocks', $template->getBlockNames());
    }

    protected function getService($serviceId)
    {
        return $this->container->get($serviceId);
    }
}
