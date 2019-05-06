<?php

class Parameters
{
	protected $info;
	protected $updated;
    protected $val;

	public function __construct($info = array(), $val = NULL)
	{
		$this->updated = false;

		if (count($info) > 0)
		{
			foreach ($info as $key => $value)
			{
				if (is_array($value))
					$info[$key] = new Parameters($value);
				else
					$info[$key] = new Parameters(array(), $value);
			}
		}
		$this->val = $val;
		$this->info = $info;
	}

	public function __get($name)
    {
		if (!isset($this->info[$name]))
			$this->info[$name] = new Parameters();

    	return $this->info[$name];
    }

    public function __set($name, $value)
    {
    	$this->updated = true;

    	if ($value instanceof Parameters)
    		$this->info[$name] = $value;
    	elseif (is_array($value))
    		$this->info[$name] = new Parameters($value);
    	else
    	{
			$this->info[$name] = new Parameters(array(), $value);
    	}
    }

    public function Count()
    {    	return count($this->info);
    }

    public function Val()
    {
    	return $this->val;
    }

    public function EscapeVal()
    {
    	return addslashes($this->val);
    }

    public function UnEscapeVal()
    {
    	return stripslashes($this->val);
    }

    public function GetInfo()
    {
    	$info = $this->info;
    	if (count($info) > 0)
		{
			foreach ($info as $key => $value)
			{
				if ($value->Count() && is_null($value->Val()))
					$info[$key] = $value->GetInfo();
				elseif (!is_null($value->Val()))
					$info[$key] = $value->Val();
				else
					unset($info[$key]);
			}
		}
    	return $info;
    }

    public function GetInfoUnEscape()
    {    	$info = $this->info;
    	if (count($info) > 0)
		{
			foreach ($info as $key => $value)
			{
				if ($value->Count() && is_null($value->Val()))
					$info[$key] = $value->GetInfoUnEscape();
				elseif (!is_null($value->Val()))
					$info[$key] = $value->UnEscapeVal();
				else
					unset($info[$key]);
			}
		}
    	return $info;
    }

    public function IsUpdated()
    {
    	return $this->updated;
    }

    public function Merge(Parameters $params)
    {    	$this->info = array_merge($this->GetInfo(), $params->GetInfo());
    	if (count($this->info) > 0)
		{
			foreach ($this->info as $key => $value)
			{
				if (is_array($value))
					$this->info[$key] = new Parameters($value);
				else
					$this->info[$key] = new Parameters(array(), $value);
			}
		}

    }

}


?>