<?php

class Forms
{
	public static function Validate($params = array(), $formName)
	{
		$params = new Parameters($params);
		
		return DatabaseObject::Validate($params, "Forms", $formName);
	}
}