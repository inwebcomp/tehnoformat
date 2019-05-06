<?php
class ModuleCurrencyCalculator extends Module implements iModule
{
	public function __construct()
	{
		$this->name = "CurrencyCalculator";
		$this->title = lang("Калькулятор валют");
		$this->version = "1.0.0";
		$this->version_required = "1.3.0";
		
		parent::__construct();
	}
	
	public function Init()
	{
		echo "Initiation done!";
	}

}