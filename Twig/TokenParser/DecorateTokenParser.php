<?php

namespace Gloomy\TwigDecoratorBundle\Twig\TokenParser;

use Gloomy\TwigDecoratorBundle\Twig\Node\DecorateNode;

class DecorateTokenParser extends \Twig_TokenParser
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
        $service   = $this->parser->getExpressionParser()->parseExpression();
        $variables = $this->parseArguments();

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

        return new DecorateNode($service, $variables, $token->getLine(), $this->getTag());
    }

    protected function parseArguments()
    {
        $stream = $this->parser->getStream();

        $variables = null;
        if ($stream->test(\Twig_Token::NAME_TYPE, 'with')) {
            $stream->next();

            $variables = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return $variables;
    }

    public function getTag()
    {
        return 'decorate';
    }
}
