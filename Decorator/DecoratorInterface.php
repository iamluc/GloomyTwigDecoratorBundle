<?php

namespace Gloomy\TwigDecoratorBundle\Decorator;

interface DecoratorInterface
{
    public function getTemplate(array $variables);

    public function getVariables(array $variables);
}