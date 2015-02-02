<?php

namespace Interop\Framework\Silex;

use Acclimate\Container\Adapter\PimpleContainerAdapter;
use Interop\Framework\Module;
use Interop\Container\ContainerInterface;
use Mouf\Interop\Silex\Application;
use Mouf\PrefixerContainer\DelegateLookupUnprefixerContainer;
use Mouf\PrefixerContainer\PrefixerContainer;
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
    private $prefix;

    /**
     * @param string $prefix The prefix to use for all the container entries.
     */
    public function __construct($prefix = null) {
        $this->prefix = $prefix;
    }

    public function getName()
    {
        return 'silex';
    }

    public function getContainer(ContainerInterface $rootContainer)
    {
        $this->rootContainer = $rootContainer;

        if ($this->prefix) {
            $this->silex = new Application(new DelegateLookupUnprefixerContainer($this->rootContainer, $this->prefix));
        } else {
            $this->silex = new Application($this->rootContainer);
        }

        // Let's put the silex app in the container... that is itself the silex app :)
        $this->silex[self::SILEX_APP_ENTRY] = $this->silex;

        // Because we are using silex.interop.di, the pimple container is externalized.
        if ($this->prefix) {
            return new PrefixerContainer($this->silex->getSilexContainer(), $this->prefix);
        } else {
            return $this->silex->getSilexContainer();
        }
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

    /**
     * @return Application
     */
    public function getSilexApp() {
        return $this->silex;
    }
}
