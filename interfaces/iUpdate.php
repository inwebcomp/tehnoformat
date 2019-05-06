<?php
interface iUpdate
{
	const InterfaceName = 'Update';
	
	/**
	Коды ошибок
	*/
	const ERROR_VERSION = 100;
	const ERROR_FUNCTIONS = 101;
	const ERROR_STRUCTURE = 200;
	const ERROR_ENGINE_STRUCTURE = 201;
	const ERROR_UPDATE_STRUCTURE = 202;
	const ERROR_FATAL = 300;
	const ERROR_WARNING = 301;
	
	public function __construct();
}