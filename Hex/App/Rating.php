<?php

namespace Hex\App;

use Database;

class Rating
{	
	public static function rated($model, $object_ID, $person_ID = false)
	{
		list($model, $object_ID, $person_ID) = self::normalize($model, $object_ID, $person_ID);

		return Database::value("SELECT COUNT(*) FROM `Rating_votes` WHERE `model` = '" . $model . "' AND `object_ID` = '" . $object_ID . "' AND `person_ID` = '" . $person_ID . "'");
	}
	
	public static function get($model, $object_ID)
	{
		$result = array();

		list($model, $object_ID) = self::normalize($model, $object_ID);

		$arr = Database::value("SELECT * FROM `Rating` WHERE `model` = '" . $model . "' AND `object_ID` = '" . $object_ID . "'", true);
		
		if (is_array($arr) and $arr['count'] > 0) {
			$result['count'] = $arr['count'];
			$result['sum'] = $arr['sum'];
			$result['value'] = (float) ($arr['sum'] / $arr['count']);
		} else {
			$result = array(
				'count' => 0,
				'sum' => 0,
				'value' => 0
			);
		}

		return $result;
	}
	
	public static function getVote($model, $object_ID, $person_ID = false)
	{
		list($model, $object_ID, $person_ID) = self::normalize($model, $object_ID, $person_ID);

		return Database::value("SELECT `value` FROM `Rating_votes` WHERE `model` = '" . $model . "' AND `object_ID` = '" . $object_ID . "' AND `person_ID` = '" . $person_ID . "'");
	}
	
	public static function rate($model, $object_ID, $person_ID = false, $value = 5)
	{
		list($model, $object_ID, $person_ID) = self::normalize($model, $object_ID, $person_ID);
		
		return Database::query("REPLACE INTO `Rating_votes` SET `model` = '" . $model . "', `object_ID` = '" . $object_ID . "', `person_ID` = '" . $person_ID . "', `value` = '" . (int) $value . "'");
	}

	public static function normalize($model = 'Item', $object_ID = null, $person_ID = null)
	{
		if (! $person_ID or $person_ID == null)
			$person_ID = Auth::getCurrentUser()->person_ID;

		return array(
			Database::escape($model),
			(int) $object_ID,
			(int) $person_ID
		);
	}
}