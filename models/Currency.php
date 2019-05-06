<?php

use Hex\App\Entity;

class Currency extends Entity
{
	protected static $findByFields = array('ID', self::NAMEID_FIELD);
	
	public static function GetDefaultCurrency()
	{
        if(isset(Model::$session->info["currency"]) and trim(Model::$session->info["currency"]) !== ""){
			$val = Model::$db->Row("SELECT * FROM Currency WHERE name = '".Model::$session->info["currency"]."'");
		}
		if(! isset($val) or ! $val or count($val) == 0){
			$val = Model::$db->Row("SELECT * FROM Currency WHERE def = 1 AND block != 1");
			
			if(!$val or count($val) == 0){
				$val = Model::$db->Row("SELECT * FROM Currency WHERE block != 1 ORDER BY pos ASC LIMIT 1");
			}
		}
	
		return $val;
		
	}
	
	public static function FormatPrice($price = 0, $multiplier = 1, $format = 1, $round = 2){

		$multiplier = (float)$multiplier;
		$round = (int)$round;
		$format = (int)$format;

		$separator = ".";
		$spacer = "";

		if(($price or $price == 0) and $multiplier > 0){
			switch($format){
				case 1:
					$separator = ".";
					$spacer = "";
				break;	
				case 2:
					$separator = ",";
					$spacer = "";
				break;	
				case 3:
					$separator = ".";
					$spacer = ",";
				break;	
				case 4:
					$separator = ",";
					$spacer = ",";
				break;	
				case 5:
					$separator = ".";
					$spacer = ".";
				break;	
				case 6:
					$separator = ",";
					$spacer = ".";
				break;	
				case 7:
					$separator = ".";
					$spacer = " ";
				break;	
				case 8:
					$separator = ",";
					$spacer = " ";
				break;	
			}
			
			$price = number_format($price / $multiplier, $round, $separator, $spacer);
		}
		
		return $price;
		
	}
}

?>