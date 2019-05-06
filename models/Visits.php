<?php

class Visits extends databaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Visits';
        $this->controllerName = 'visits';
		
		parent::__construct($ID);
	}
	
	public function is_bot(&$botname = ''){
		$bots = array(
            //'mozilla/'=>'Mozilla',
            'Yandex'=>'Yandex Bot',
            'yaDirectBot'=>'YandexDirect Bot',
            'yandexSomething'=>'YandexSomething Bot',
            'googlebot/'=>'Google Bot',
            'AdsBot-Google'=>'AdsBot',
            'Nigma.ru'=>'Nigma Bot',
            'bing.com'=>'Bing Bot',
            'aport/'=>'Aport Robot',
            'mail.ru'=>'Mail.Ru Bot',
            'Rambler/'=>'Rambler Bot',
            'msnbot/'=>'MSN Bot',
            'yahoo/'=>'Yahoo Bot',
            'AbachoBOT'=>'AbachoBOT',
            'Lycos/'=>'Lycos spider',
            'scooter/'=>'Altavista robot',
            'AltaVista'=>'Altavista robot',
            'WebAlta'=>'WebAlta',
            'Scrubby'=>'Scrubby robot',
            'sape.bot'=>'Sape Bot',
            'sape_context'=>'Sape Context Bot',
            'gigabot'=>'Giga Bot',
            'snapbot'=>'Snap Bot',
            'alexa.com'=>'Alexa Bot',
            'igde.ru'=>'Igde Bot',
            'ask.com'=>'Ask Bot',
            'qwartabot'=>'Qwarta Bot',
            'yanga.co.uk'=>'Yanga Bot',
            'liveinternet.ru'=>'Liveinternet Bot',
            'agama'=>'Agama Bot',
            'metadatalabs.com'=>'Metadata Bot',
            'Copyscape.com'=>'Copyscape Bot',
            'accoona'=>'Accoona Bot',
            'ASPSeek'=>'ASPSeek',
            'CrocCrawler'=>'CrocCrawler',
            'Dumbot'=>'Dumbot',
            'FAST-WebCrawler'=>'FAST-WebCrawler',
            'GeonaBot'=>'GeonaBot',
            'Gigabot'=>'Gigabot',
            'MSRBOT'=>'MSRBOT',
    	);
	
		
    	foreach($bots as $bot => $name){
			if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
				$botname = $name;
				return $botname;
			}
		}
		
		return false;
	} 
	
	public function AddNew(){
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$browser = false;
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) $browser = 'firefox';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) $browser = 'chrome';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) $browser = 'safari';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) $browser = 'opera';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) $browser = 'ie6';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) $browser = 'ie7';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) $browser = 'ie8';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) $browser = 'ie9';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) $browser = 'ie10';
		elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7')) $browser = 'ie11';
		
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
		if(!self::is_bot() and $browser){
			if(Model::$db->NumAll("SELECT ID FROM Visits WHERE ip = '".$ip."' AND DATE(updated) = '".date("Y-m-d")."'") == 0){
				$created = Model::$db->Value("SELECT created FROM Visits WHERE ip = '".$ip."'");
				if(trim($created) !== ""){
					Model::$db->Query("INSERT INTO Visits SET ip = '".$ip."', browser = '".$browser."', user_agent = '".$user_agent."', created = '".$created."', updated = NOW()");	
				}else{
					Model::$db->Query("INSERT INTO Visits SET ip = '".$ip."', browser = '".$browser."', user_agent = '".$user_agent."', created = NOW(), updated = NOW()");	
				}
			}else{
				Model::$db->Query("UPDATE Visits SET updated = NOW() WHERE ip = '".$ip."' AND browser = '".$browser."' AND DATE(updated) = '".date("Y-m-d")."'");	
			}
		}
		
		return true;
		
	}
	
	public function GetVisitsCount(){
		
		$arr = Model::$db->ArrayValuesQ("SELECT DATE(updated) date, COUNT(ID) count FROM Visits GROUP BY DATE(created) ORDER BY DATE(updated) DESC");
	
		return $arr;
		
	}
	
	public function GetVisitsCountByInterval($interval, $num = 1){
		
		$arr = array();
		
		$date = date("Y-m-d", time() - 3600 * 24 * $interval * $num);
		
		$q = Model::$db->Query("SELECT DATE(updated) date, COUNT(ID) count FROM Visits WHERE updated BETWEEN STR_TO_DATE('".$date." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".date("Y-m-d")." 23:59:59', '%Y-%m-%d %H:%i:%s') GROUP BY DATE(updated) ORDER BY DATE(updated) DESC");

		while($tmp = Model::$db->Fetch($q)){
			$arrtmp[$tmp["date"]] = $tmp;	
		}

		$count = 0;
		for($i=$num*$interval;$i>=0;$i--){
			$day = $i;
			$date = date("Y-m-d", time() - 3600 * 24 * $day);
			$count = (isset($arrtmp[$date]["count"])) ? $count + $arrtmp[$date]["count"] : $count + 0;
			$arr[$date] = array("date" => $date, "count" => $count);
			if($day % $interval == 0){ $count = 0; }
		}

		return $arr;
		
	}
	
	public function GetBrowsers(){
		
		$arr = Model::$db->ArrayValuesQ("SELECT browser, COUNT(ID) count FROM Visits GROUP BY browser ORDER BY COUNT(ID) DESC");
	
		foreach($arr as &$value){
			
			if($value["browser"] == "chrome"){
				$value["name"] = "Google Chrome";
			}elseif($value["browser"] == "firefox"){
				$value["name"] = "Mozilla Firefox";
			}elseif($value["browser"] == "safari"){
				$value["name"] = "Safari";
			}elseif($value["browser"] == "opera"){
				$value["name"] = "Opera";
			}elseif($value["browser"] == ""){
				$value["name"] = "None";
			}elseif($value["browser"][0].$value["browser"][1] == "ie"){
				$value["name"] = "Internet Explorer";
			}else{
				$value["name"] = "None";
			}
		}
	
		return $arr;
		
	}
	
	public function GetNewVisitors(){
		
		$count = Model::$db->NumAll("SELECT ID FROM Visits WHERE DATE(created) > '".date("Y-m-d", time() - 60 * 60 * 24 * 3)."' GROUP BY DATE(created)");	

		return $count;
		
	}
	
	public function GetOnlineVisitors(){
		
		$count = Model::$db->Value("SELECT COUNT(ID) FROM Visits WHERE updated BETWEEN STR_TO_DATE('".date("Y-m-d H:i:s", time() - 60 * 15)."', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".date("Y-m-d H:i:s")."', '%Y-%m-%d %H:%i:%s')");	

		return $count;
		
	}
}

?>