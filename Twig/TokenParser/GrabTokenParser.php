<?php

namespace Gloomy\TwigDecoratorBundle\Twig\TokenParser;

use Gloomy\TwigDecoratorBundle\Twig\Node\GrabNode;

class GrabTokenParser extends \Twig_TokenParser
{
    public function parse(\Twig_Token $token)
    {
        list($service, $variables) = $this->parseArguments();
        return new GrabNode($service, $variables, $token->getLine(), $this->getTag(), 'grabber');
    }

    protected function parseArguments()
    {
        $service = $this->parser->getExpressionParser()->parseExpression();

        $stream = $this->parser->getStream();
        $variables = null;
        if ($stream->test(\Twig_Token::NAME_TYPE, 'with')) {
            $stream->next();

            $variables = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return array($service, $variables);
    }

    public function getTag()
    {
        return 'grab';
    }
}
