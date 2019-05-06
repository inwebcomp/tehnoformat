<?php

use Hex\App\Entity;

class Followers extends Entity
{
	public static function exists($email)
	{
		$num = Database::value("SELECT COUNT(*) FROM `Followers` WHERE email = '" . Database::escape($email) . "'");
		
		return ($num > 0 ? true : false);
	}

	public static function subscribe($email, $name)
	{
		if (self::exists($email)) {
			self::changeUpdated($email);

			return self::find($email, array('email'));
		}

		$params = new Parameters();
		$params->name = $name;
		$params->email = $email;
		
		$errors = new Parameters(); 
	
		$follower = Followers::create("Followers", "create", $params, $errors);

		return $follower;
	}

	public static function changeUpdated($email)
	{
		return Database::query("UPDATE `Followers` SET updated = NOW() WHERE email = '" . Database::escape($email) . "'");
	}
}