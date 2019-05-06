<?php

namespace Hex\App;

use Hex\Abstracts\Singleton;
use Database;
use Model;

class Tasks extends Singleton
{
	protected static $instance;
	
	protected $tasks;

	public function __construct()
	{
		//
	}

	public function getTasks()
	{
		if (! $this->tasks)
			$this->tasks = array(
                'clear_old_sessions' => new \Hex\Tasks\ClearOldSessions(),
                'send_mail' => new \Hex\Tasks\SendMailTask()
			);

		return $this->tasks;
	}

	public function find($key)
	{
		if (isset($this->getTasks()[$key]))
			return $this->getTasks()[$key];
	}

	public function executeAll()
	{
		// try {
			foreach ($this->getTasks() as $task)
				$task->execute();
		// } catch (\Exception  $ex) {
		// 	throw $ex;
		// }
	}
}