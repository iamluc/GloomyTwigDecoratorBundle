<?php

namespace Gloomy\TwigDecoratorBundle\Tests\Decorator;

class ControllerDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateConsoleDataGrid()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $parser    = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser')
            ->disableOriginalConstructor()
            ->getMock();

        $decorator = new \Gloomy\TwigDecoratorBundle\Decorator\ControllerDecorator($container, $parser);

        $this->assertEquals('toto', $decorator->getTemplate(array('_template' => 'toto')));
        $this->assertEquals(array('jean' => 'bon'), $decorator->getVariables(array('jean' => 'bon')));
    }
}
