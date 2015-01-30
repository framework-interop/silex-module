<?php

namespace Interop\Framework\Silex;

use Acclimate\Container\Adapter\PimpleContainerAdapter;
use Interop\Framework\Module;
use Interop\Container\ContainerInterface;
use Mouf\Interop\Silex\Application;
use Mouf\StackPhp\SilexMiddleware;
use Interop\Framework\HttpModuleInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * This module provides a base Silex application.
 * Other modules can hook on that application.
 */
class SilexFrameworkModule implements HttpModuleInterface
{
    const SILEX_APP_ENTRY = "silexApp";

    private $rootContainer;
    private $silex;

    public function getName()
    {
        return 'silex';
    }

    public function getContainer(ContainerInterface $rootContainer)
    {
        $this->rootContainer = $rootContainer;
        $this->silex = new Application($this->rootContainer);

        // Let's put the silex app in the container... that is itself the silex app :)
        $this->silex[self::SILEX_APP_ENTRY] = $this->silex;

        // The app is the container, but not compatible with ContainerInterop (because of the "get" method that has a different meaning).
        // Let's wrap it in a container.
        return new PimpleContainerAdapter($this->silex);
    }

    /* (non-PHPdoc)
     * @see \Interop\Framework\ModuleInterface::init()
     */
    public function init()
    {
    }

    /* (non-PHPdoc)
     * @see \Interop\Framework\HttpModuleInterface::getHttpMiddleware()
     */
    public function getHttpMiddleware(HttpKernelInterface $app)
    {
        return new SilexMiddleware($app, $this->silex);
    }
}
