<?php

namespace Gloomy\TwigDecoratorBundle\Decorator;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class XmlHttpRequestDecorator extends ControllerDecorator
{
    protected $request;

    public function __construct(ContainerInterface $container, ControllerNameParser $parser, Request $request)
    {
        $this->request = $request;

        parent::__construct($container, $parser);
    }

    public function getTemplate(array $variables)
    {
        if (!isset($variables['_template'])) {
            throw new \Exception('The _template variable is required');
        }

        if (!isset($variables['_xmlhttprequest'])) {
            throw new \Exception('The _xmlhttprequest variable is required');
        }

        return $this->request->isXmlHttpRequest() ? $variables['_xmlhttprequest'] : $variables['_template'];
    }
}
