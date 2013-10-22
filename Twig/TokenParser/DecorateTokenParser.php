<?php

namespace Gloomy\TwigDecoratorBundle\Twig\TokenParser;

use Gloomy\TwigDecoratorBundle\Twig\Node\GrabNode;

class DecorateTokenParser extends GrabTokenParser
{
    protected $container;
    protected $extension;

    public function __construct($container, $extension)
    {
        $this->container = $container;
        $this->extension = $extension;
    }

    public function parse(\Twig_Token $token)
    {
        list($service, $variables) = $this->parseArguments();

        // Set up callback to determine the parent template
        $arguments = new \Twig_Node_Expression_Array(array(), $token->getLine());
        $arguments->addElement($service);
        $arguments->addElement($variables);

        $parent = new \Twig_Node_Expression_MethodCall(
            new \Twig_Node_Expression_ExtensionReference('DecorateExtension', $token->getLine()),
            'getTemplate',
            $arguments,
            $token->getLine()
            );
        $this->parser->setParent($parent);

        return new GrabNode($service, $variables, $token->getLine(), $this->getTag(), 'decorator');
    }

    public function getTag()
    {
        return 'decorate';
    }
}
