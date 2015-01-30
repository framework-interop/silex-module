Silex module for framework-interop
==================================

This package contains a *framework-interop* module that provides a Silex application.

It can be used by other framework-agnostic modules to ensure that a Silex application
is present and can be used.

##Framework-agnostic modules?

Have a look at [the demo](http://github.com/framework-interop/framework-interop-demo).
It shows 3 modules, one using Symfony 2, one using Silex and one using Zend Framework 1.

##How it works

Let's assume you are writing a framework-agnostic module using *framework-interop*. Your module might
offer web pages to the user. For this, it must be able to catch HTTP requests and respond accordingly.
Using *framework-interop*, you can do this by implementing a [HttpModuleInterface](https://github.com/framework-interop/http-module-interface).

However, this can be time consuming to directly implement a middleware. Most of the time, it is easier to
use an existing MVC framework, like [Silex](http://silex.sensiolabs.org/).

- The `SilexFrameworkModule` is a module that will load and initialize Silex for you.
- Then, all you have to do is extend the `AbstractSilexModule` class to add routes to the silex application.

##An example

Here is a minimalistic sample:

```php
namespace Acme\FrontendModule;

use Interop\Framework\Silex\AbstractSilexModule;
use Symfony\Component\HttpFoundation\Response;

/**
 * The frontend module is a Silex application.
 */
class SampleModule extends AbstractSilexModule
{
	
    public function getName()
    {
        return 'sample';
    }

	public function init()
	{
	    // Let's get the silex application
		$app = $this->getSilexApp();

		// Let's add a route
		$app->get('/myroute', function (Application $app) {
			return new Response('Hello world!');
		});

	}
}
```

##Things you should know

A Silex application embeds a Pimple container. This container will be shared with all the other modules.
Therefore, all objects you put in the Silex application will be available from the root container.
