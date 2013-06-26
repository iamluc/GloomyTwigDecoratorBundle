<?php

namespace Gloomy\TwigDecoratorBundle\Twig\Node;

class DecorateNode extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $service, $variables = null, $lineno, $tag = null)
    {
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

        $compiler->raw(', $this);');
    }
}
