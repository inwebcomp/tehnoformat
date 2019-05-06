<?php

namespace Hex\Tasks;

use Database;

class ClearOldSessions extends Task
{
	public function execute()
	{
        Database::query("DELETE FROM `Sessions` WHERE updated < STR_TO_DATE('" . date("Y-m-d H:i:s", time() - 3600 * 24 * 14) . "', '%Y-%m-%d %H:%i:%s')");
	}
}