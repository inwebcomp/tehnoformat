<?php
interface iModule
{
	const InterfaceName = 'Module';
	
	public function __construct();
	
	public function Init();

}