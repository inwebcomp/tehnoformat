<?php

namespace Hex\App;

use Hex\Abstracts\Singleton;

class Debug extends Singleton
{
	protected static $instance;

	private $connector;
	private $errorHandler;

	private $password = 'reddino';

	// public function __construct()
	// {
	// 	\PhpConsole\Helper::register();

	// 	$this->errorHandler = \PhpConsole\Handler::getInstance();
	// 	$this->errorHandler->start();

	// 	$this->connector = \PhpConsole\Connector::getInstance();
	// 	$this->connector->setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
	// 	$this->connector->setPassword($this->password, true);
	// 	$this->connector->getDebugDispatcher()->detectTraceAndSource = true;
	// }
}