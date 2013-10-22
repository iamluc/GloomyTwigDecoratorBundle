<?php

namespace Gloomy\TwigDecoratorBundle\Twig\Node;

class GrabNode extends \Twig_Node
{
    protected $serviceType;

    public function __construct(\Twig_NodeInterface $service, $variables = null, $lineno, $tag = null, $serviceType)
    {
        $this->serviceType = $serviceType;

        parent::__construct(array('service' => $service, 'variables' => $variables), array(), $lineno, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('echo $this->env->getExtension(\'DecorateExtension\')->getVariables(')
            ->subcompile($this->getNode('service'))
            ->raw(', ')
            ;

        if (null === $this->getNode('variables')) {
            $compiler->raw('array()');
        } else {
            $compiler->subcompile($this->getNode('variables'));
        }

        $compiler->raw(', $this, "'.$this->serviceType.'");');
    }
}
