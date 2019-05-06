<?php

class Routing extends DatabaseObject
{
	protected static $metaInfo = array();
	protected $values;

	public function __construct($ID)
	{
        $this->modelName = 'Routing';

        parent::__construct($ID);

		$this->values = array();
		$this->info['params'] = unserialize($this->info['params']);
	}

	public function IsUniqBlockName($name)
	{
		return (Model::$db->Value("SELECT COUNT(*) FROM `Routing_item` WHERE router_ID = '" . $this->info['ID'] . "' AND blockName = '" . $name . "'") == 0);
	}

	public function AddBlock($blockName, $controllerName, $methodName)
	{		Model::$db->Query("INSERT INTO Routing_item SET router_ID = '" . $this->info['ID'] . "', blockName = '" . $blockName . "', controller = '" . $controllerName . "', method = '" . $methodName . "'");

		return true;
	}

	public function RemoveBlock($blockName)
	{
		Model::$db->Query("DELETE FROM Routing_item WHERE router_ID = '" . $this->info['ID'] . "' AND blockName = '" . $blockName . "'");

		return true;
	}

	public function GetBlockNameInfo($blockName)
	{		 $info = Model::$db->Row("SELECT * FROM Routing_item WHERE router_ID = '" . $this->info['ID'] . "' AND blockName = '" . $blockName . "'");

		 return $info;
	}

	public function GetBlocks($section)
	{
		$content = array();

		$res = Model::$db->Query("SELECT * FROM Routing_item WHERE router_ID = '" . $this->info['ID'] . "'");
		while ($arr = Model::$db->Fetch($res))
		{
			$action = new Action($arr['method'], new Controller($arr['controller'], $section));
			$params = $action->GetParamNames();
			for ($i = 0; $i < count($params); $i++)
			{
				$paramArr = array();
				$paramArr['name'] = $params[$i];
				$arr['params'][] = $paramArr;
			}
			$content[] = $arr;
        }
		return $content;
	}

	public function GetParamsList($section)
	{
		$content = array();

		$res = Model::$db->Query("SELECT * FROM Routing_item WHERE router_ID = '" . $this->info['ID'] . "'");
		$params = array();
		while ($arr = Model::$db->Fetch($res))
		{
			$action = new Action($arr['method'], new Controller($arr['controller'], $section));
			$params = array_merge($action->GetParamNames(), $params);
		}

		for ($i = 0; $i < count($params); $i++)
		{
			$paramArr = array();
			$paramArr['name'] = $params[$i];
			$content[] = $paramArr;
		}

		return $content;
	}

	public function IsBlockName($blockName)
	{
		$is = Model::$db->Value("SELECT COUNT(*) FROM `Routing_item` WHERE router_ID = '" . $this->info['ID'] . "' AND blockName = '" . $blockName . "'");
		return ($is) ? true : false;

	}

	public function GetParams()
	{		return $this->info['params'];
	}

	public function SetParams($params)
	{
		if (is_array($params))
		{			$res = Model::$db->Query("UPDATE Routing SET params = '" . serialize($params) . "' WHERE ID = '" . $this->info['ID'] . "'");
			$this->info['params'] = $params;
		}
		return true;
	}

	public function SetValues($values)
	{	
		if (is_array($values))
		{		
			$this->values = $values;
		}
	}
	
	public function GetValues()
	{		
		return $this->values;
	}

	public function GetParamsHash($blockName)
	{
		
		$params = array();
		$count = 0;
	
		foreach ($this->info['params'] as $name)
        {
			if (isset($this->values[$count]))
				$params[$name] = $this->values[$count];
			$count ++;
		}

		return $params;
	}


}

?>