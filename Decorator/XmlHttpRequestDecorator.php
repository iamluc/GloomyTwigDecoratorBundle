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
        $_template       = isset($variables['_template']) ? $variables['_template'] : null;
        $_xmlhttprequest = isset($variables['_xmlhttprequest']) ? $variables['_xmlhttprequest'] : null;

        return $this->request->isXmlHttpRequest() ? $_xmlhttprequest : $_template;
    }
}
