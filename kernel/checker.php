<?php

class Checker
{
	protected static $dataTypes = array('Int' => NULL,
										'IntArray' => NULL,
										'IntArrayString' => NULL,
										'FloatArray' => NULL,
										'FloatArrayString' => NULL,
										'String' => NULL,
										'StringArray' => NULL,
										'ArrayValue' => NULL,
										'LiteralString' => NULL,
										'Bool' => 0,
										'SeoName' => NULL,
										'BoolArray' => NULL,
										'Password' => NULL,
										'Html' => NULL,
										'Text' => NULL,
										'Double' => NULL,
										'Email' => NULL,
										'Url' => NULL,
										'Wmr' => NULL,
										'DataType' => NULL,
										'Datetime' => NULL,
										'SqlTypeByDataType' => NULL,
										'PostText' => NULL,
										'InfoBlock' => NULL);
	protected $defaults;
	protected $intervals;
	protected $types;

	public function __construct()
	{
		$types = func_get_args();
		$this->types = array();

		foreach ($types as $type)
			$this->types[] = Checker::GetDataType($type);
	}

	protected static function GetDataType($type)
	{
		if (!array_key_exists($type, self::$dataTypes) && !class_exists($type))
			throw new Exception(lang('Инициализация неизвестным типом данных.') . '[library: checker, type: ' . $type . ']');
		return $type;
	}

	public function SetDefaults()
	{
		if (func_num_args() != count($this->types))
			throw new Exception(lang('Неверное количество аргументов для инициализации.') . '[library: checker, method: SetDefaults]');

		for ($i = 0; $i < count($this->types); $i++)
			$this->defaults[] = func_get_arg($i);
	}

	public function SetInterval()
	{
		if (func_num_args() != count($this->types))
			throw new Exception(lang('Неверное количество аргументов для инициализации.') . '[library: checker, method: SetInterval]');
		$args = func_get_args();

		for ($i = 0; $i < count($this->types); $i++)
		{
			$tmpArr = array();

			if ($args[$i])
			{
				$tmpArr = explode(':', $args[$i]);

                self::Int($tmpArr[0]);
                self::Int($tmpArr[1]);
				if (isset($tmpArr[0], $tmpArr[1]) && $tmpArr[0] <= $tmpArr[1])
				{
    				$this->intervals[] = $tmpArr;
				}
				else
				{
					throw new Exception(lang('Неверный диапазон.') . '[library: checker, method: SetInterval, interval: ' . $args[$i] . ']');
				}
			}
			else
			{
				$this->intervals[] = NULL;
			}
		}
	}

	public function Get()
	{
		if (func_num_args() != count($this->types))
			throw new Exception(lang('Неверное количество аргументов для инициализации.') . '[library: checker, method: Get]');

        $args = func_get_args();

		$values = array();

		for ($i = 0; $i < count($this->types); $i++)
		{
			$value = $args[$i];
            $check = true;

			try
			{
				if (!is_null($value))
				{
					switch ($this->types[$i])
					{
						case 'Int':
							$value = self::Int($value, $this->intervals[$i]);
							break;

						case 'IntArray':
							$value = self::IntArray($value, $this->intervals[$i]);
							break;
							
						case 'IntArrayString':
							$value = self::IntArrayString($value, $this->intervals[$i]);
							break;
							
						case 'FloatArray':
							$value = self::FloatArray($value, $this->intervals[$i]);
							break;
							
						case 'FloatArrayString':
							$value = self::FloatArrayString($value, $this->intervals[$i]);
							break;

						case 'Double':
							$value = self::Float($value);
							break;

						case 'Url':
							$value = self::Url($value);
							break;

						case 'Datetime':
							$value = self::Datetime($value);
							break;

						case 'Email':
							$value = self::Email($value);
							break;

						case 'SqlTypeByDataType':
							$value = self::SqlTypeByDataType($value);
							break;

						case 'String':
							$value = self::String($value, $this->intervals[$i]);
							if ($value) $value = self::Escape($value);
							break;

						case 'StringArray':
							$value = self::StringArray($value, $this->intervals[$i]);
							for ($i = 0; $i < count($value); $i++)
								$value[$i] = self::Escape($value[$i]);
							break;

						case 'ArrayValue':
							$value = self::ArrayValue($value);
							$value = self::Escape($value, 'html');
							break;

						case 'LiteralString':
							$value = self::LiteralString($value, $this->intervals[$i]);
							break;

						case 'SeoName':
							$value = self::SeoName($value);
							break;

						case 'Bool':
							$value = self::Bool($value);
							break;

						case 'BoolArray':
							$value = self::BoolArray($value);
							break;

						case 'Wmr':
							$value = self::Wmr($value);
							break;

						case 'Text':
							$value = self::Text($value, $this->intervals[$i]);
							if ($value) $value = self::Escape($value);
							break;

						case 'Html':
							$value = self::Html($value);
							if ($value) $value = self::Escape($value, 'html');
							break;

						case 'PostText':
							$value = self::PostText($value);
							break;

						case 'InfoBlock':
							if (!$value instanceof InfoBlock)
								$value = new InfoBlock($value, 1);
							break;

						case 'Password':
							$value = self::Password($value);
							break;

						default:
							$type = $this->types[$i];
							if (!$value instanceof $type)
							{
								$value = new $type($value);

								if ($value instanceof \Hex\App\Entity)
								{
									if (! $value->real())
										$value = NULL;
								}
							}
					}
				}
			}
			catch (Exception $ex) { $value = NULL; }

			$values[] = (!is_null($value)) ? $value : ((isset($this->defaults[$i])) ? $this->defaults[$i] : ((isset(self::$dataTypes[$this->types[$i]])) ? self::$dataTypes[$this->types[$i]] : NULL));
		}

		return $values;
	}

	public static function Escape($val, $type = "string")
	{
		if($type == "string"){
			return addcslashes(htmlspecialchars($val), "'\\");
		}elseif($type == "int"){
			return intval($val);
		}elseif($type == "float"){
			return (float)$val;
		}elseif($type == "like"){
			return addcslashes($val, "\%_");
		}

		return addcslashes(stripcslashes($val), "'\\");
	}

	public static function UnEscape($val)
	{
		return stripslashes($val);
	}

	public static function Int($val, $interval = NULL)
	{
		if (preg_match('#^-{0,1}[0-9]+$#isu', $val))
		{
			if (!is_array($interval))
				return intval($val);

			if ($val <= $interval[1] && $val >= $interval[0])
				return intval($val);

			throw new Exception(lang('Datatype error') . '[library: checker]');
		}
		else
			throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function IntArray($val, $interval = NULL)
	{
		if(is_array($val)){
			foreach($val as &$value) $value = intval($value);
				
			return implode(",", $val);
		}else{
			return intval($val);
		}

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}
	
	public static function IntArrayString($val, $interval = NULL)
	{
		if(!is_array($val)){
			$arr = explode(",", $val);
			foreach($arr as &$value) $value = intval($value);
			
			return implode(",", $arr);
		}
		
		throw new Exception(lang('Datatype error') . '[library: checker]');
	}
	
	public static function FloatArray($val, $interval = NULL)
	{
		if(is_array($val)){
			foreach($val as &$value) $value = (float)$value;
				
			return implode(",", $val);
		}else{
			return (float)$val;
		}

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}
	
	public static function FloatArrayString($val, $interval = NULL)
	{
		if(!is_array($val)){
			$arr = explode(",", $val);
			foreach($arr as &$value) $value = (float)$value;
			
			return implode(",", $arr);
		}
		
		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function Url($val)
	{
		$val = 'https://' . preg_replace('#^https://#isu', '', $val);
		return $val;
	}

	public static function Email($val)
	{
		if (preg_match('#^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z]{2,4}$#isu', $val))
			return $val;

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function String($val, $interval = NULL)
	{
		if (!is_array($interval))
			return trim($val);

		if (mb_strlen($val, 'UTF-8') <= $interval[1] && mb_strlen($val, 'UTF-8') >= $interval[0])
			return trim($val);

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function StringArray($val, $interval = NULL)
	{/* Метод не дописан */
		if (!is_array($val))
			throw new Exception(lang('Datatype error') . '[library: checker]');

        $valNew = array();       	for ($i = 0; $i < count($val); $i++)
       	{        	try
        	{
        		$str = self::String($val[$i], $interval);
        		$valNew[] = $str;
       		}
       		catch (Exception $ex) { }
       	}

		//if (!is_array($interval))
			return $valNew;

		//if (mb_strlen($val, 'UTF-8') <= $interval[1] && mb_strlen($val, 'UTF-8') >= $interval[0])
		//	return $val;

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function ArrayValue($val)
	{
		if (!is_array($val))
			throw new Exception(lang('Datatype error') . '[library: checker]');

     	return serialize($val);
	}


	public static function LiteralString($val, $interval = NULL)
	{
		if (preg_match('#^[a-z0-9_]+$#isu', $val))
		{
			if (!is_array($interval))
				return $val;

			if (mb_strlen($val, 'UTF-8') <= $interval[1] && mb_strlen($val, 'UTF-8') >= $interval[0])
				return $val;

			throw new Exception(lang('Datatype error') . '[library: checker]');
		}
		else
			throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function SeoName($val)
	{
		$val = Utils::RusLat($val);
		if (mb_strlen($val, 'UTF-8') > 1)
			return $val;

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function Float($val)
	{
		$val = str_replace(',', '.', trim($val));

		if (preg_match('#^-{0,1}[0-9]+\.{0,1}[0-9]*$#isu', $val))
			return $val;

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function DataType($val)
	{
		if (in_array($val, array('String', 'Text', 'Html', 'Int', 'Double', 'Bool', 'One2many', 'Timestamp', 'Email')))
			return $val;

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function SqlTypeByDataType($val)
	{
		switch ($val)
		{
		   	case 'String': case 'Email':
		       	return "VARCHAR(255) DEFAULT NULL";
		       	break;

			case 'Text': case 'Html':
				return "TEXT";
				break;

		   	case 'Int':
		      	return "INT(11) DEFAULT NULL";
		       	break;

			case 'Double':
			   	return "DOUBLE DEFAULT NULL";
			   	break;

			case 'Bool':
			   	return "BOOL DEFAULT 0";
			   	break;

			case 'Timestamp':
			   	return "TIMESTAMP DEFAULT NOW()";
			   	break;

			case 'One2many':
		      	return "INT(11) DEFAULT NULL";
		       	break;

			default:
				throw new Exception(lang('Datatype error') . '[library: checker]');
		}
    }

	public static function Password($val)
	{
		if (!preg_match("#^[\@\#\$\*\^\%\!\:\.\,\<\>a-z0-9_]{4,32}$#isu", $val))
			throw new Exception(lang('Datatype error') . '[library: checker]');

		return $val;
	}

	public static function Wmr($val)
	{
		if (!preg_match("#^R[0-9]{12}$#isu", $val))
			throw new Exception(lang('Datatype error') . '[library: checker]');

		return $val;
	}

	public static function Bool($val)
	{
		return (!$val or $val == "NULL") ? 0 : 1;
	}

	public static function Datetime($val)
	{
		if ($val = strtotime($val))
			return date('Y-m-d H:i:s', $val);
		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public static function BoolArray($val)
	{
		if (is_array($val) && count($val) > 0)
		{
			$newVal = array();
			foreach ($val as $key => $value)
				$newVal[self::Escape($key)] = ($val[$key]) ? 1 : 0;

			return $newVal;
		}
		else
			return array();
	}

	public function Html($val, $interval = NULL)
	{
		if (!is_array($interval))
			return $val;

		if (mb_strlen($val, 'UTF-8') <= $interval[1] && mb_strlen($val, 'UTF-8') >= $interval[0])
			return $val;

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public function Text($val, $interval = NULL)
	{
		if (!is_array($interval))
			return $val;

		if (mb_strlen($val, 'UTF-8') <= $interval[1] && mb_strlen($val, 'UTF-8') >= $interval[0])
			return $val;

		throw new Exception(lang('Datatype error') . '[library: checker]');
	}

	public function PostText($val)
	{
		if ((mb_strlen($val, 'UTF-8') >= 5 && mb_strlen($val, 'UTF-8') <= 10000))
		{
			$val = Utils::StripTagsAttributes($val, array('<p>','<em>','<strong>','<div>','<b>','<i>','<u>','<a>','<span>','<br>'));

			$matches = array();
			preg_match_all('#<a#isu', $val, $matches);

			if (count($matches[0]) <= 1)
			{
				return $val;
			}
		}

        throw new Exception(lang('Datatype error') . '[library: checker]');
	}
}


final class File
{
	private $info;

	public function __construct($name)
	{
		$conf = KernelSettings::GetInstance();
		$db = Database::DataBaseConnect();

		$this->info = $db->Row("SELECT name, size, md5 FROM Uploads WHERE name = '" . Checker::Escape($name) . "' LIMIT 1");
		if (!isset($this->info['md5']))
		   throw new Exception('Undefined file.');

		$this->info['path'] = $conf->documentroot . '/tmp/' . $this->info['md5'];
		
	}

	public function GetValue()
	{
		return $this->info['md5'];
	}

	public function GetInfo()
    {
		return $this->info;
    }

    public function Remove()
    {
    	$db = Database::DataBaseConnect();
    	@unlink($this->info['path']);
    	$db->Query("DELETE FROM Uploads WHERE md5 = '" . $this->info['md5'] . "'");
    }

	public function __get($name)
    {
    	return (isset($this->info[$name])) ? $this->info[$name] : NULL;
    }
}

final class FileList
{
	protected $value;

	public function GetValue()
	{
		return $this->value;
	}

	public function __construct($md5Arr)
	{
		if (!is_array($md5Arr))
			throw new Exception('Undefined FileList.');
		$this->value = array();
		foreach ($md5Arr as $md5)
			$this->value[] = new File($md5);
	}
}



final class LiteralString
{
	protected $value;

	public function GetValue()
	{
		return $this->value;
	}

	public function __construct($val)
	{
		$val = trim($val);
		if (!preg_match("#^[a-z0-9_\-]+$#isu", $val))
			throw new Exception('Incorrect LiteralString type.');
		$this->value = htmlentities(urldecode($val), ENT_QUOTES, 'UTF-8');
	}
}


?>
