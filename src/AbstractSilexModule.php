<?php

namespace Interop\Framework\Silex;

use Interop\Framework\Module;
use Interop\Container\ContainerInterface;
use Interop\Framework\ModuleInterface;
use Mouf\Interop\Silex\Application;
use Interop\Container\Exception\NotFoundException;

/**
 * If you need to write a module that relies on Silex, your module can extend this class.
 */
abstract class AbstractSilexModule implements ModuleInterface
{
    /**
     * @var ContainerInterface
     */
    private $rootContainer;

    private $silexFrameworkModule;

    /**
     * @param string $prefix The prefix to use for all the container entries.
     */
    public function __construct(SilexFrameworkModule $silexFrameworkModule) {
        $this->silexFrameworkModule = $silexFrameworkModule;
    }

    abstract public function getName();

    public function getContainer(ContainerInterface $rootContainer)
    {
        $this->rootContainer = $rootContainer;
        return;
    }

    abstract public function init();

    /**
     * @return Application
     */
    protected function getSilexApp()
    {
        return $this->silexFrameworkModule->getSilexApp();
    }

    /**
     * @return ContainerInterface
     */
    protected function getRootContainer()
    {
        return $this->rootContainer;
    }
}
