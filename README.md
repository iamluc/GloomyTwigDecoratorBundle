GloomyTwigDecoratorBundle
=========================

ABOUT
-----

This bundle adds 2 tags in twig:
- {% grab %} which injects variables into the template
- {% decorate %} that you can use instead of the {% extends %} tag to determine which layout to use to extend your template, and inject custom variables to each layout.

USAGE
-----

### {% grab %}
``` html+django
{% grab 'my_grabber' %}
{# or #}
{% grab 'my_grabber' with {'var1': 'my_value'} %}
```

The bundle comes with 1 grabber:
- ControllerGrabber

    This grabber will be used only to inject variables inside the template from a controller.
    ``` html+django
    {% grab 'controller' with {
        '_controller': 'MyBundle:MyController:MyMethod',
        'my_own_var': 'cool !'}
    %}
    ```

    ``` php
    <?php
    //...
    class MyController
    {
        public function MyMethodAction($variables)
        {
            $layout = array('my_var' => 'I use my layout in many templates, but I inject variables only here');
            return array_merge($variables, $layout);
        }
    ```

### {% decorate %}
``` html+django
{% decorate 'my_decorator' %}
{# or #}
{% decorate 'my_decorator' with {'var1': 'my_value'} %}
```

The bundle comes with 2 decorators:
- controller

    This decorator will be used only to inject variables inside the layout from a controller.
    ``` html+django
    {% decorate 'controller' with {
        '_controller': 'MyBundle:MyController:MyMethod',
        '_template': 'MyBundle::layout.html.twig',
        'my_own_var': 'cool !'}
    %}
    ```

    ``` php
    <?php
    //...
    class MyController
    {
        public function MyMethodAction($variables)
        {
            $layout = array('my_var' => 'I use my layout in many templates, but I inject variables only here');
            return array_merge($variables, $layout);
        }
    ```

- xmlhttprequest

    This decorator lets you define 2 layouts depending if XmlHttpRequest has been used or not. Then you can choose which variables to inject into each.
    ``` html+django
    {% decorate 'xmlhttprequest' with {
        '_controller': 'MyBundle:MyController:MyMethod',
        '_template': 'MyBundle::layout.html.twig',
        '_xmlhttprequest': 'MyBundle::xmlhttprequest.html.twig',
        'my_own_var': 'cool !'} %}
    ```

    ``` php
    <?php
    //...
    class MyController
    {
        public function MyMethodAction($variables)
        {
            $layout = array();
            if (false === $this->getRequest()->isXmlHttpRequest()) {
                $layout = array('needed_only_for_layout' => 'This variable is NOT injected in XmlHttpRequest mode');
            }
            return array_merge($variables, $layout);
        }
    ```

    All parameters are optionals.
    Without the _xmlhttprequest parameter, all blocks are printed by default.

    ``` html+django
    {% decorate 'xmlhttprequest' with {
        '_template': 'MyBundle::layout.html.twig',
        'my_own_var': 'cool !'} %}

    {% block test %}
        This block is printed directly if request is xmlHttpRequest, but extends MyBundle::layout.html.twig otherwise
    {% endblock %}
    ```

    You can choose the blocks you want to display with the _blocks parameter

    ``` html+django
    {% decorate 'xmlhttprequest' with {
        '_template': 'MyBundle::layout.html.twig',
        '_blocks': ['test']
        'my_own_var': 'cool !'} %}

    {% block test %}
        This block is printed if request is xmlHttpRequest, but extends MyBundle::layout.html.twig otherwise
    {% endblock %}

    {% block test_not_printed %}
        This block is NOT printed if request is xmlHttpRequest, but extends MyBundle::layout.html.twig otherwise
    {% endblock %}
    ```

EXTEND
------

Create your own grabber or decorator.

### Grabber
Your class must implement _Gloomy\TwigDecoratorBundle\Grabber\GrabberInterface_ which has only 1 method:
- public function getVariables(array $variables);

### Decorator
Your class must implement _Gloomy\TwigDecoratorBundle\Decorator\DecoratorInterface_ which has only 2 methods:
- public function getTemplate(array $variables);
- public function getVariables(array $variables);

Then define a tagged service and use it as the first argument of the {% grab %} or {% decorate %} tag.
The tag can be :
```
    <tag name="gloomy.grabber" alias="my_alias"/>
    <tag name="gloomy.decorator" alias="my_alias"/>
```

LICENSE
-------

MIT

INSTALLATION
------------

### 1. Install with composer

    composer.phar require "gloomy/twig-decorator-bundle" "*"

### 2. Modify your app/AppKernel.php

``` php
<?php
    //...
    $bundles = array(
        //...
        new Gloomy\TwigDecoratorBundle\GloomyTwigDecoratorBundle(),
    );
```
