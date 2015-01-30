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

    /**
     * @var Application
     */
    protected $silexApp;

    abstract public function getName();

    public function getContainer(ContainerInterface $rootContainer)
    {
        $this->rootContainer = $rootContainer;

        try {
            $this->silexApp = $this->rootContainer->get(SilexFrameworkModule::SILEX_APP_ENTRY);
        } catch (NotFoundException $ex) {
            throw new SilexFrameworkModuleException('Could not find instance "silexApp". The most likely reason is that you did not add the SilexFrameworkModule to the list of available modules in your application.', 0, $ex);
        }

        return;
    }

    abstract public function init();

    /**
     * @return Application
     */
    protected function getSilexApp()
    {
        return $this->silexApp;
    }

    /**
     * @return ContainerInterface
     */
    protected function getRootContainer()
    {
        return $this->rootContainer;
    }
}
