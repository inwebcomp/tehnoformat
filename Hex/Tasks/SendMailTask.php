<?php

namespace Hex\Tasks;

use Database;
use Hex\App\Queue;
use Item;

class SendMailTask extends Task
{
	public function execute()
	{
		Queue::execute(10);
	}
}