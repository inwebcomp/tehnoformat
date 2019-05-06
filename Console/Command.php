<?php

namespace Console;

class Command extends \Symfony\Component\Console\Command\Command
{
    public function include_app()
    {
		require(__DIR__ . "/../kernel/include.php");

		\Database::$instance = \Database::DataBaseConnect();

		\Model::Initialize();

		date_default_timezone_set((trim(\Model::$conf->default_timezone) !== "" ? \Model::$conf->default_timezone : "Europe/Chisinau"));
				
		\Session::CreateSession();
		
		\Hex\App\Auth::checkAuthTime();
		
		\Application::$language = new \Language(\Model::$conf->default_language);
    }
}