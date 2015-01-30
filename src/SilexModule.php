<?php

namespace Interop\Framework\Silex;

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
class SilexModule implements HttpModuleInterface
{
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
		$this->silex->re

		// Let's put the silex app in the container... that is itself the silex app :)
		$this->silex['silexApp'] = $this->silex;

		// The app is the container.
        return $this->silex;
    }

	/* (non-PHPdoc)
	 * @see \Interop\Framework\ModuleInterface::init()
	 */
	public function init() {

	}

	/* (non-PHPdoc)
	 * @see \Interop\Framework\HttpModuleInterface::getHttpMiddleware()
	 */
	public function getHttpMiddleware(HttpKernelInterface $app) {

		return new SilexMiddleware($app, $this->silex);
	}

}
