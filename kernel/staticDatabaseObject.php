<?php

define('CMF_RELATION_NO', 1);
define('CMF_RELATION_SIMPLE', 2);
define('CMF_RELATION_SIMPLE_RECURSIVE', 3);
define('CMF_RELATION_SIMPLE_LIST', 4);
define('CMF_RELATION_SIMPLE_LIST_RECURSIVE', 5);

define('CMF_VALIDATE_FULL', 1);
define('CMF_VALIDATE_FAST', 2);

abstract class StaticDatabaseObject extends Model
{
    public static $tables;
    protected static $tablesInfo;
	protected static $fieldsInfo;
	protected static $shema;
	protected $relationsInfo;
	
	public static function GetList($modelName, $params, $blockName = 'items')
	{
		$content = array();
		
		$select = "`".$modelName."`.*";
		$from = "`".$modelName."`";
		$join = $where = $group = "";
		$order = "`".$modelName."`.`ID`";
		$onpage = 1;
		$arr = array();
		
		$params = self::NormalizeSelectParams($modelName, $params);
				
		$content['select']['page_is'] = 1;
		
		
		if(self::IfMultilangTable($modelName)){
			$join = " LEFT JOIN `".$modelName."_ml` ON `".$modelName."`.ID = `".$modelName."_ml`.ID";
			$where .= " AND `".$modelName."_ml`.lang_ID = '".Application::$language->ID."'";
			$select .= ", `".$modelName."_ml`.*";
		}
		
		if($params->page_is->Val()){ $onpage = $params->page_is->Val(); $content['select']['page_is'] = $params->page_is->Val(); }
		
		if($params->onPage->Val()){ $limit = $params->onPage->Val(); $content['select']['onPage'] = $params->onPage->Val(); }
		if($params->ne->block->Val()) { $where .= " AND `".$modelName."`.`block` != 1"; $content['select']['block'] = 0; }
		
		if($params->order->Val()){ 
			$order = "";
			$orderArr = explode(",", $params->order->Val());
			$n = 1;
			foreach($orderArr as $key => $value){
				$value = trim($value);
				if(in_array($value, self::GetMultilangTableColumns($modelName))){
					$table = $modelName."_ml";	
				}else{
					$table = $modelName;	
				}
				if($n < count($orderArr)){ $dot = ","; }else{ $dot = ""; }
				$order .= "`".$table."`.`".$value."`".$dot;
				$n++;
			}
			$content['select']['order'] = $orderArr[0];
		}

		if($params->freeOrder->Val()){ 
			$order = $params->freeOrder->Val();
		}


		if($params->randomOrder->Val()){ 
			$order = "RAND()";
		}
		
		
		if($params->group->Val()){ 
			$group = " GROUP BY ".$params->group->Val();
		}

		
		if($params->in->Count() > 0){ 
			$in = $params->in->GetInfo(); 
			
			foreach($in as $key => $value){
				$value = Checker::Escape($value);
				if($value !== '(_NULL)' and $value !== '_NULL' and $value !== ""){
					if(in_array($key, self::GetMultilangTableColumns($modelName))){
						$table = $modelName."_ml";	
					}else{
						$table = $modelName;	
					}
					$where .= " AND `".$table."`.".$key." IN ".$value;
				
					$content['select']['where']['in'][$key] = $value;
				}
			}
		}
		if($params->not_in->Count() > 0) { 
			$not_in = $params->not_in->GetInfo(); 
			
			foreach($not_in as $key => $value){
				$value = Checker::Escape($value);
				if($value !== '(_NULL)' and $value !== '_NULL' and $value !== ""){
					if(in_array($key, self::GetMultilangTableColumns($modelName))){
						$table = $modelName."_ml";	
					}else{
						$table = $modelName;	
					}
					$where .= " AND `".$table."`.".$key." NOT IN ".$value;
				
					$content['select']['where']['not_in'][$key] = $value;
				}
			}
		}
		
		if($params->orderDirection->Val()) { $order .= " ".$params->orderDirection->Val(); $content['select']['orderDirection'] = $params->orderDirection->Val(); }
		

		if($params->where->Count() > 0) { 
			$where_val = $params->where->GetInfo();
			
			foreach($where_val as $key => $value){ 
				if($key !== 'not' and $key !== "logic"){ 
					if($value !== '_NULL' and !$params->in->$key->Val()){
						if(in_array($key, self::GetMultilangTableColumns($modelName))){
							$table = $modelName."_ml";	
						}else{
							$table = $modelName;	
						}
						
						if($params->setWhereTable->$key->Val()){
							$table = $params->setWhereTable->$key->Val();
						}
						$value = Checker::Escape($value);
						$content['select']['where'][$key] = $value;
						$where .= " AND `".$table."`.`".$key."` = '".$value."'";
					}
				}elseif($key == "logic"){
					
					if($value !== '_NULL'){
						
						$logic_arr = $params->where->logic->GetInfo();
							foreach($logic_arr as $key3 => $value3){
								if(count($logic_arr) > 0){
									$whereParams .=  " AND (";
								} 
								$n = 1;
								foreach($value3 as $key2 => $value2){
									//$value2 = Checker::Escape($value2);
									if(in_array($key, self::GetMultilangTableColumns($modelName))){
										$table = $modelName."_ml";	
									}else{
										$table = $modelName;	
									}
									
									if($params->setWhereTable->$key3->Val()){
										$table = $params->setWhereTable->$key3->Val();
									}
									
									$content['select']['where']['logic'][$key3] = $value3;
									
									$compare = array(">", "<", "<>", ">=", "<=");
									
									preg_match("/^((OR)|(AND)|(ANDF))\s((<)|(>)|(<>)|(>=)|(<=)|(=));([\s\S]{1,})/", $value2, $reg);
									
									//$staple1 = ($reg[1] == "OR") ? " (" : " ";
									$staple2 = ($reg[1] == "AND") ? ")" : "";
							
									$staple2 =  ")";
							
									$staple1 = ($reg[1] == "ANDF" or $n == 1) ? " (" : $staple1;
									$reg[1] = ($reg[1] == "ANDF" or $n == 1) ? "" : $reg[1];
									
									if(trim($reg[5]) !== "="){
										$whereParams .= " ".$reg[1].$staple1."TRUNCATE(".$table.".".$key3.",2) ".$reg[5]." ".(!in_array($reg[5], $compare) ? "'" : "").$reg[12].(!in_array($reg[5], $compare) ? "'" : "").$staple2;
									}else{
										$whereParams .= " ".$reg[1].$staple1."".$table.".".$key3." ".$reg[5]." '".$reg[12]."'".$staple2;
									}
									
									$n++;
								}
								
								if(count($logic_arr) > 0){
									$whereParams .=  ")";
								}
								
								$where .= $whereParams;
							}
					}
				}else{
					$name = $params->where->not->GetInfo();
					
					foreach($name as $key2 => $value2){
						if($value2 !== '_NULL'){
							$value2 = Checker::Escape($value2);
							if(in_array($key2, self::GetMultilangTableColumns($modelName))){
								$table = $modelName."_ml";	
							}else{
								$table = $modelName;	
							}
							
							if($params->setWhereTable->not->$key->Val()){
								$table = $params->setWhereTable->not->$key->Val();
							}
							
							$content['select']['where']['not'][$key2] = $value2;
							$val = Checker::Escape($params->where->not->$key2->Val());
							$where .= " AND `".$table."`.`".$key2."` != '".$val."'";
						}
					}
				}
			}
		}
		

		if($params->freeWhere->Val()) { 
			$content['select']['freeWhere'] = $params->freeWhere->Val();
			$where .= $params->freeWhere->Val();
		}
		
		
		if($params->like->Count() > 0) { 
			$like_val = $params->like->GetInfo();
			foreach($like_val as $key => $value){
				if($value !== '_NULL'){
					if(is_array($value)){
						$new_value = $value; 
					}else{
						$new_value = array($value);	
					}
					$n = 1;
					foreach($new_value as $vkey => $vvalue){
						if(in_array($key, self::GetMultilangTableColumns($modelName))){
							$table = $modelName."_ml";	
						}else{
							$table = $modelName;	
						}
						$a = ($n == 1) ? "(" : "";
						$b = ($n == count($new_value)) ? ")" : "";
						$o = ($n == 1) ? "AND" : "OR";
						$where .= " ".$o." ".$a."`".$table."`.`".$key."` LIKE '".$vvalue."'".$b;
						$n++;
					}
					$content['select']['like'][$key][] = $vvalue;
				}
			}
		}
		
		
		if($params->likeA->Count() > 0) { 
			$likeA_val = $params->likeA->GetInfo(); 
			foreach($likeA_val as $key => $value){
				if($value !== '_NULL'){
					$value = Checker::Escape($value, "like");
					if(in_array($key, self::GetMultilangTableColumns($modelName))){
						$table = $modelName."_ml";	
					}else{
						$table = $modelName;	
					}
					$content['select']['likeA'][$key] = $value;
					$where .= " AND `".$table."`.`".$key."` LIKE '%".$value."%'";
				}
			}
		}
		
		if($params->likeOr->Count() > 0) { 
			$like_val = $params->likeOr->GetInfo();
			$k = 1;
			foreach($like_val as $key => $value){
				if($value !== '_NULL'){
					if(is_array($value)){
						$new_value = $value; 
					}else{
						$new_value = array($value);	
					}
					$n = 1;
					foreach($new_value as $vkey => $vvalue){
						if(in_array($key, self::GetMultilangTableColumns($modelName))){
							$table = $modelName."_ml";	
						}else{
							$table = $modelName;	
						}
						$a = ($n == 1) ? "(" : "";
						$a = ($k == 1) ? "((" : $a;
						$b = ($n == count($new_value)) ? ")" : "";
						$o = ($n == 1) ? "OR" : "OR";
						$o = ($k == 1) ? "AND" : $o;
						$where .= " ".$o." ".$a."`".$table."`.`".$key."` LIKE '".$vvalue."'".$b;
						$n++; $k++;
					}
					$content['select']['likeOr'][$key][] = $vvalue;
				}
			}
			if(count($like_val)){
				$where .= ")";
			}
		}
	
		if($params->select->Val()){
			$select = "";
			$select_str = $params->select->Val();
			
			$select_str = explode(",", $select_str);
			$n = 1;
			foreach($select_str as $key => $value){
				$value = trim($value);
				if($n < count($select_str)){ $dot = ", "; }else{ $dot = ""; }
				$select .= "`".$modelName."`.".$value.$dot;
				$n++;
			}
		}
		
		if($params->join->Count() > 0) { 
			$arrjoin = $params->join->GetInfo(); 
			
			foreach($arrjoin as $key => $value){
				if(self::IfMultilangTable($key)){

					$value = ($value == 1) ? array("ID", "ID") : $value;
					
					if(preg_match("/^Paramvalue/", $key)){
						$key2 = "object_";
					}
					
					$joinType = ($params->joinType->$key->Val() !== "") ? $params->joinType->$key->Val() : "LEFT";
									
					$join .= " $joinType JOIN ".$key." ON ".$from.".".$value[0]." = ".$key.".".$key2."".$value[1]." $joinType JOIN ".$key."_ml ON ".$key."_ml.ID = ".$key.".ID";	
					$where .= " AND (".$key."_ml.lang_ID = ".Application::$language->ID." OR ".$key."_ml.lang_ID IS NULL)";
				}else{
					$join .= " $joinType JOIN ".$key." ON ".$from.".".$value[0]." = `".$key."`.".$value[1]."";	
					$where .= " AND ".$from.".".$value[0]." = `".$key."`.".$value[1]."";
				}
				
				$content['select']['join'][$key] = $value;
			}
		}

		$order = " ORDER BY ".$order;
			
		if($params->orderByBlock->Val()){
			$order .= ", block"; 	
		}
		
		if($params->getMax->Count()) { 
			foreach($params->getMax->GetInfo() as $col => $as){
				$selectMax[] = array($col, $as);
			}
		}
		
		if($params->getMin->Count()) { 
			foreach($params->getMin->GetInfo() as $col => $as){
				$selectMin[] = array($col, $as);
			}
		}
		
		
		
		$onpage = ($onpage - 1) * $limit;
		
		$limit = " LIMIT ".$onpage.",".$limit;
		$content['select']['limit'] = $limit;

		/*if(Model::$user->name == "Saneock" and Application::$section !== "backend")
			echo "SELECT ".$select." FROM ".$from.$join." WHERE 1".$where.$group.$order.$limit;*/
	
		$q2 = Model::$db->Value("SELECT COUNT(*) FROM ".$from.$join." WHERE 1".$where.$group);
		$q = Model::$db->query("SELECT ".$select." FROM ".$from.$join." WHERE 1".$where.$group.$order.$limit);

		if(isset($selectMax) and is_array($selectMax)){
			foreach($selectMax as $v){
				$content["select"][$v[1]] = Model::$db->Value("SELECT MAX(".$v[0].") FROM ".$from.$join." WHERE 1".$where.$group);
			}
		}
		
		if(isset($selectMin) and is_array($selectMin)){
			foreach($selectMin as $v){
				$content["select"][$v[1]] = Model::$db->Value("SELECT MIN(".$v[0].") FROM ".$from.$join." WHERE 1".$where.$group);
			}
		}

		
		$content['select']['num'] = $q2;
		$content['count'] = $q2;
		
		
		if($params->selectByID->Count() > 0){ 
			
			$selectByID = $params->selectByID->GetInfo();
			$selectByIDTable = current($selectByID); 
			$selectByIDCol = key($selectByID); 
			$q3 = $q;
			
			$n = 1;
			while($val3 = Model::$db->fetch($q3)){
				$q4 = Model::$db->fetch(Model::$db->query("SELECT COUNT(*) FROM ".$selectByIDTable." WHERE `".$selectByIDTable."`.`".$selectByIDCol."` = '".$val3['ID']."'"));
				$arr[$n]['items_count'] = $q4[0];
				$n++;
			}
			
			$q = Model::$db->query("SELECT ".$select." FROM ".$from.$join." WHERE 1".$where.$group.$order.$limit);
			
		}
		

		$splitName = array();

		if(count($params->SplitBlocksByName->GetInfo()) > 0){
			$array = $params->SplitBlocksByName->GetInfo();
			foreach($array as $key => $value){
				$splitName[$key] = $array[$key]['name'];
			}
		}elseif(count($params->SplitBlocksByID->GetInfo()) > 0){
			$array = $params->SplitBlocksByID->GetInfo();
			foreach($array as $key => $value){
				$splitName[$key] = $array[$key]['ID'];
			}
		}elseif($params->SplitBlocksByName->Val() !== ''){
			$splitName = '';
		}else{
			$splitName = '';
		}
		
		
		if($params->onPage->Val() < $content['select']['num']){
		
			$pages = array();
			$selectedPage = ($params->page_is->Val()) ? $params->page_is->Val() : 1;
			$pagesNum = ceil($content['select']['num'] / $params->onPage->Val());
			
			if($selectedPage > 1){
				$pages['back']['name'] = 'back';
				$pages['back']['link'] = $selectedPage - 1;
				
				$content['select']['prev_page'] = $selectedPage - 1;
			}
			
			$selectedPageN = ($selectedPage < 5) ? 1 : $selectedPage - 3;
			$selectedPageN2 = ($selectedPage < 5) ? 7 - $selectedPage : 3;
			
			if($selectedPage >= 5){
				$pages['more1']['name'] = '...';
				$pages['more1']['link'] = ($selectedPage > 6) ? $selectedPage - 6 : $selectedPage - (5 - (6 - $selectedPage));
			}
			
			$nNum = ($pagesNum < $selectedPage + $selectedPageN2) ? $pagesNum : $selectedPage + $selectedPageN2;
			
			for($n = $selectedPageN; $n <= $nNum; $n++){

				$pages[$n]['name'] = $n;
				$pages[$n]['link'] = $n;
				if($n == $selectedPage){ $pages[$n]['selected'] = 1; }	
				
			}
			
			if($pagesNum > 7 and $selectedPage <= $pagesNum - 7){
				$pages['more2']['name'] = '...';
				$pages['more2']['link'] = $selectedPage + 6;
			}
			
			if($selectedPage < $pagesNum){
				$pages['next']['name'] = 'next';
				$pages['next']['link'] = $selectedPage + 1;

				$content['select']['next_page'] = $selectedPage + 1;
			}
			
			$content['select']['pages_count'] = $pagesNum;
			$content['select']['pages'] = $pages;

		}

		$content['select']['onpage_from'] = ($content['select']['page_is'] - 1) * $content['select']['onPage'] + 1;
		$content['select']['onpage_to'] = min($content['select']['page_is'] * $content['select']['onPage'], $content['select']['num']);
		
		
		$n = 1;
		$n2 = 0;
		while($val = Model::$db->fetch($q)){
			$splitName = (!is_array($splitName)) ? $n2 : $splitName[$n2];
			if($params->numToSplitBlocks->Val()){
				if($n - floor($n / $params->numToSplitBlocks->Val()) * $params->numToSplitBlocks->Val() == 0){
					$arr[$splitName]['block'][$n] = $val;
					$n2++;
				}else{
					$arr[$splitName]['block'][$n] = $val;
				}
			}else{
				if(isset($arr[$n]) and is_array($arr[$n])){
					$arr[$n] = array_merge($val, $arr[$n]);
				}else{
					$arr[$n] = $val;
				}
			}
			$n++;
		}
		

		
		if($blockName !== 'items'){
			$content['select_'.$blockName] = $content['select'];
			$content[$blockName] = $arr;
			unset($content['select']);
		}else{	
			if(isset($selectBySome)){
				$content['items'] = $arr;
			}else{
				$content['items'] = $arr;
			}
		}
		
        return $content;
	}

	
	public static function NormalizeSelectParams($modelName, $params)
	{
    	if (!$params instanceof Parameters)
        	$params = new Parameters();
		
    	$tableColumns = self::GetTableColumns($modelName);

        $params->onPage = intval($params->onPage->Val());
		$onPageDefault = 'cmf_' . $modelName . '_default_onPage';

		
    	if (!$params->onPage->Val() || $params->onPage->Val() < 0)
    	{
        	if ($params->smart->Val() == 1)
        		$params->onPage = self::$session->$onPageDefault;

        	if (!$params->onPage->Val())
        		$params->onPage = 50;
		}
		if ($params->smart->Val())
			self::$session->Set($onPageDefault, $params->onPage->Val());
			
			
		
		
		
		$params->page_is = intval($params->page_is->Val());
		$page_isDefault = 'cmf_' . $modelName . '_default_page_is';

		
    	if (!$params->page_is->Val() || $params->page_is->Val() < 0)
    	{
        	if ($params->smart->Val() == 1)
        		$params->page_is = self::$session->$page_isDefault;

        	if (!$params->page_is->Val())
        		$params->page_is = 1;
		}
		if ($params->smart->Val())
			self::$session->Set($page_isDefault, $params->page_is->Val());
			
		
	

		$params->order = Checker::Escape($params->order->Val());
    	$orderDefault = 'cmf_' . $modelName . '_default_order';
    	if (!$params->order->Val() || !in_array($params->order->Val(), $tableColumns))
    	{
        	if ($params->smart->Val())
        		$params->order = self::$session->$orderDefault;

        	if (!$params->order->Val() && in_array('pos', $tableColumns))
        		$params->order = 'pos';
        	elseif (!$params->order->Val())
        		$params->order = $tableColumns[0];
		}
		if ($params->smart->Val())
			self::$session->Set($orderDefault, $params->order->Val());
			
	



		
		
		if($params->orderDirection->Val() != 'ASC' && $params->orderDirection->Val() != 'DESC')
			$params->orderDirection = NULL;

		$orderDirectionDefault = 'cmf_' . $modelName . '_default_orderDirection';
		if (!$params->orderDirection->Val())
    	{
        	if ($params->smart->Val())
        		$params->orderDirection = self::$session->$orderDirectionDefault;

        	if (!$params->orderDirection->Val())
        		$params->orderDirection = 'ASC';
		}
		if ($params->smart->Val())
			self::$session->Set($orderDirectionDefault, $params->orderDirection->Val());




		$neDefault = 'cmf_' . $modelName . '_default_ne';
    	if ($params->where->not->Count())
    	{
        	$arr = $params->where->not->GetInfo();
        	$filteredArr = array();
        	foreach ($arr as $key => $value)
        	{
        		if ($key && in_array($key, $tableColumns) && $value !== '_NULL')
        			$filteredArr[$key] = Checker::Escape($value);
        	}
        	if (count($filteredArr) > 0)
        	{
        		$params->where->not = $filteredArr;
        		if ($params->smart->Val())
        			self::$session->Set($neDefault, $params->where->not->GetInfo());
        	}
        	else
        		$params->where->not = new Parameters();
		}
		else
		{
			$params->where->not = new Parameters();
			if ($params->smart->Val())
				$params->where->not = self::$session->$neDefault;
		}
		if ($params->smart->Val())
			self::$session->Set($neDefault, $params->where->not->GetInfo());
			
			
			
			
		$logicDefault = 'cmf_' . $modelName . '_default_logic'; 
    	if ($params->where->logic->Count())
    	{
        	$arr = $params->where->logic->GetInfo(); 
        	$filteredArr = array();
        	foreach ($arr as $key => $value)
        	{  
        		if ($key && in_array($key, $tableColumns) && $value !== '_NULL'){
        			$filteredArr[$key] = $value;
				}elseif($params->setWhereTable->$key->Val()){
					$filteredArr[$key] = $value;
				}
        	}
        	if (count($filteredArr) > 0)
        	{
        		$params->where->logic = $filteredArr;
        		if ($params->smart->Val())
        			self::$session->Set($logicDefault, $params->where->logic->GetInfo());
        	}
        	else
        		$params->where->logic = new Parameters();
		}
		else
		{
			$params->where->logic = new Parameters();
			if ($params->smart->Val())
				$params->where->logic = self::$session->$logicDefault;
		}
		if ($params->smart->Val())
			self::$session->Set($logicDefault, $params->where->logic->GetInfo());


		
		
		$whereDefault = 'cmf_' . $modelName . '_default_where';
		if (in_array('parent_ID', $tableColumns) && !$params->where->parent_ID->Val() && !$params->notNestedSets->Val())
        { 
			if ($params->smart->Val()) 
			{ 
				eval("\$parent_ID = self::\$session->".$whereDefault."['parent_ID'];");
			
				$params->where->parent_ID = ($parent_ID) ? intval($parent_ID) : 1;
				self::$session->Set($whereDefault, $params->where->GetInfo());
			}
			else{
				if(!$params->freeSelect->Val())
					$params->where->parent_ID = 1;
			}
        }
		
		if ($params->where->Count() > 0)
    	{
        	$params->where->level = '_NULL';
			$arr = $params->where->GetInfo();
			
        	$filteredArr = array();
        	foreach ($arr as $key => $value)
        	{ 
        		if($key && in_array($key, $tableColumns) && $value !== '_NULL')
        		{
        			$filteredArr[$key] = $value;
        		}elseif($key && in_array($key, $tableColumns) && $value == '_NULL')
        		{
        			$filteredArr[$key] = NULL;
        		}elseif($params->setWhereTable->$key->Val()){
					$filteredArr[$key] = Checker::Escape($value);
				}elseif($key == "logic" and is_array($value)){
					$filteredArr[$key] = $value;
				}elseif($key == "not" and is_array($value)){
					$filteredArr[$key] = $value;
				}
        	}

        	if (count($filteredArr) > 0)
        	{
				$parent_ID = intval($params->where->parent_ID->Val());
				
        		$params->where = $filteredArr; 
        		if ($params->smart->Val()) 
        			self::$session->Set($whereDefault, $params->where->GetInfo()); 
					
				if(in_array('parent_ID', $tableColumns) and ! $params->freeSelect->Val()){
					$params->where->parent_ID = ($parent_ID) ? intval($parent_ID) : 1;
				}
        	}
        	else
        	{
        		if(is_array(self::$session->$whereDefault)){
					foreach (self::$session->$whereDefault as $key => $value)
					{ 
						if($key && !in_array($key, $filteredArr) && $value !== '_NULL')
						{
							$params->where->$key = $value;
						}
					} 
				}	
			}
		}
		else
		{
			$params->where = new Parameters();

			if ($params->smart->Val())
				$params->where = self::$session->$whereDefault;
		}
		
		
		$likeDefault = 'cmf_' . $modelName . '_default_like';
    	if ($params->like->Count())
    	{
        	$arr = $params->like->GetInfo();
        	$filteredArr = array();
        	foreach ($arr as $key => $value)
        	{
        		if ($key && in_array($key, $tableColumns) && $value !== '_NULL')
        			$filteredArr[$key] = $value;
        	}
        	if (count($filteredArr) > 0)
        	{
        		$params->like = $filteredArr;
        		if ($params->smart->Val())
        			self::$session->Set($likeDefault, $params->like->GetInfo());
        	}
        	else
        		$params->like = new Parameters();
		}
		else
		{
			$params->like = new Parameters();
			if ($params->smart->Val())
				$params->like = self::$session->$likeDefault;
		}
		if ($params->smart->Val())
			self::$session->Set($likeDefault, $params->like->GetInfo());
			
			
		
		$likeDefault = 'cmf_' . $modelName . '_default_likeOr';
    	if ($params->likeOr->Count())
    	{
        	$arr = $params->likeOr->GetInfo();
        	$filteredArr = array();
        	foreach ($arr as $key => $value)
        	{
        		if ($key && in_array($key, $tableColumns) && $value !== '_NULL')
        			$filteredArr[$key] = $value;
        	}
        	if (count($filteredArr) > 0)
        	{
        		$params->likeOr = $filteredArr;
        		if ($params->smart->Val())
        			self::$session->Set($likeDefault, $params->likeOr->GetInfo());
        	}
        	else
        		$params->likeOr = new Parameters();
		}
		else
		{
			$params->likeOr = new Parameters();
			if ($params->smart->Val())
				$params->like = self::$session->$likeDefault;
		}
		if ($params->smart->Val())
			self::$session->Set($likeDefault, $params->likeOr->GetInfo());
		
			
			
		$likeADefault = 'cmf_' . $modelName . '_default_likeA';
    	if ($params->likeA->Count())
    	{
        	$arr = $params->likeA->GetInfo();
        	$filteredArr = array();
        	foreach ($arr as $key => $value)
        	{
        		if ($key && in_array($key, $tableColumns) && $value !== '_NULL')
        			$filteredArr[$key] = Checker::Escape($value);
        	}
        	if (count($filteredArr) > 0)
        	{
        		$params->likeA = $filteredArr;
        		if ($params->smart->Val())
        			self::$session->Set($likeADefault, $params->likeA->GetInfo());
        	}
        	else
        		$params->likeA = new Parameters();
		}
		else
		{
			$params->likeA = new Parameters();
			if ($params->smart->Val())
				$params->likeA = self::$session->$likeADefault;
		}
		if ($params->smart->Val()){
			self::$session->Set($likeADefault, $params->likeA->GetInfo()); }
			
			
		$inDefault = 'cmf_' . $modelName . '_default_in';

    	if ($params->in->Count())
    	{
        	$arr = $params->in->GetInfo();
        	$filteredArr = array();
        	foreach ($arr as $key => $value)
        	{
        		if ($key && in_array($key, $tableColumns) && $value !== '_NULL')
        			$filteredArr[$key] = Checker::Escape($value);
        	}
        	if (count($filteredArr) > 0)
        	{
        		$params->in = $filteredArr;
        		if ($params->smart->Val())
        			self::$session->Set($inDefault, $params->in->GetInfo());
        	}
        	else
        		$params->in = new Parameters();
		}
		else
		{
			$params->in = new Parameters();
			if ($params->smart->Val())
				$params->in = self::$session->$inDefault;

			$filteredArr = array();
			
			foreach ($params->in as $key => $value)
			{
				if ($key && in_array($key, $tableColumns) && $value !== '_NULL')
					$filteredArr[$key] = Checker::Escape($value);
			}

			$params->in = $filteredArr;
		}
		if ($params->smart->Val()){
			self::$session->Set($inDefault, $params->in->GetInfo()); }



		self::$session->Save();

		return $params;
	}
	
	
	public static function EditFast($modelName, $formName, $params, &$errors)
	{
        $params = $params->GetInfo();
		
        if (count($params) > 0)
        {
			$checker = new Checker("Config");	
        	foreach ($params as $ID => $subParams)
        	{
        		$subParams = new Parameters($subParams);
        		$subParams->ID = $ID;

        		$errors = self::Validate($subParams, $modelName, $formName, CMF_VALIDATE_FAST);
				
		    	$multilangColumns = self::GetMultilangTableColumns($modelName);
		    	$columns = self::GetNoMultilangTableColumns($modelName);

		    	if ($errors->IsUpdated() && $errors->ID)
		    	{
					$query = $multilangQuery = '';

					$subParams = $errors->GetInfo();
					$f = false;

					foreach ($subParams as $key => $value)
					{

		       			if (in_array($key, $columns))
		       				$query .= $key . " = '" . $value . "', ";
		       			elseif (in_array($key, $multilangColumns))
		       				$multilangQuery .= "`" . $key . "` = '" . $value . "', ";

					}


					$query = preg_replace('#, $#isu', '', $query);
					$multilangQuery = preg_replace('#, $#isu', '', $multilangQuery);

					if ($query != '')
						$res = self::$db->Query('UPDATE `' . $modelName . '` SET ' . $query . ' WHERE ID = ' . $subParams['ID']);

					if ($multilangQuery != '')
					{
						self::$db->Query("UPDATE `" . $modelName . "_ml` SET " . $multilangQuery . ' WHERE lang_ID = ' . Application::$language->ID . ' AND ID = ' . $subParams['ID']);
					}
					
					if($subParams['ID']){
						list($config) = $checker->Get($subParams['ID']);
					}
					// Save not multilang config values to multilang
					if ($multilangQuery != '' and $modelName == "Config" and $config and $config->type !== "string")
					{
						self::$db->Query("UPDATE `" . $modelName . "_ml` SET " . $multilangQuery . ' WHERE ID = ' . $subParams['ID']);
					}

		    	}
        	}
        }


        return true;
	}
	
	public function DeleteIncorrectParams($table, $params){
		
		$content = new Parameters();
		
		$cols = self::GetTableColumns($table);
	
		$param = $params->GetInfo();
	
		foreach($param as $key => $value){
			if(in_array($key, $cols)){
				$content->$key = $value;
			}
		}
		 
		return $content;
		
	}

    public function IfMultilangTable($table)
    {
        if (! is_array(self::$tables)) {
            $result = Database::query("SHOW TABLES FROM `" . Database::$db_name . "`");

            if(! $result){
                echo lang("Ошибка базы, не удалось получить список таблиц\n");
                exit;
            }

            self::$tables = array();

            while ($row = Database::fetch($result)) {
                self::$tables[] = strtolower(array_shift($row));
            }
        }

        return (in_array(strtolower($table . '_ml'), self::$tables));
    }
	
	public static function GetObjectByColumn($modelName, $columnName, $value)
	{
		$ID = Model::$db->Value("SELECT ID FROM `" . $modelName . "` WHERE " . $columnName . " = '" . $value . "'");
       	if (!$ID)
       		return false;
			
		$checker = new Checker($modelName);
		list($object) = $checker->Get($ID);
		
       	return $object;
		
	}
	
	public static function GetObjectColumn($modelName, $ID, $column)
	{
		$values = Model::$db->Value("SELECT ".$column." FROM `" . $modelName . "` WHERE ID = '" . $ID . "'");
       	if (count($values))
       		return $values;
		else
			return false;
	}
	
	public static function UpdateObjectColumn($modelName, $ID, $column, $value)
	{
		$q = Model::$db->Query("UPDATE `" . $modelName . "` SET ".$column." = '".Checker::Escape($value)."'  WHERE ID = '" . $ID . "'");
       	if($q)
       		return true;
		else
			return false;
	}
	
	public static function LoadModelInfo($modelName)
	{
		if (isset(self::$tablesInfo[$modelName]) && is_array(self::$tablesInfo[$modelName]))
			return true;

		$metaPath = (Application::$section !== 'frontend') ? Model::$conf->metaPath : Model::$conf->metaFrontendPath;

		if(!file_exists($metaPath . '/' . $modelName . '.xml')){
			$metaPath = Model::$conf->metaPath;
		}

		if (isset(self::$shema[$modelName])) $shema = self::$shema[$modelName];
		else $shema = self::$shema[$modelName] = simplexml_load_file($metaPath . '/' . $modelName . '.xml');

		$tables = $shema->xpath('/model/table');

        if (count($tables) > 0)
		{
            self::$tablesInfo[$modelName]['isMultilang'] = false;
            self::$tablesInfo[$modelName]['haveRelations'] = false;
            self::$tablesInfo[$modelName]['extends'] = false;
            self::$tablesInfo[$modelName]['tree'] = false;

			$tableAttr = $tables[0]->attributes();

            if ($tableAttr->tree)
            	self::$tablesInfo[$modelName]['tree'] = true;

            if ($tableAttr->extends)
            {
				$metaPath = (Application::$section !== 'frontend') ? Model::$conf->metaPath : Model::$conf->metaFrontendPath;

				if(!file_exists($metaPath . '/' . $modelName . '.xml')){
					$metaPath = Model::$conf->metaPath;
				}
            	self::$tablesInfo[$modelName]['extends'] = $extends = (string)$tableAttr->extends;
            	if (isset(self::$shema[$extends])) $defaultShema = self::$shema[$extends];
				else $defaultShema = self::$shema[$extends] = simplexml_load_file($metaPath . '/' . $extends . '.xml');
				$defaultTables = $defaultShema->xpath('/model/table');
				$tables = array_merge($defaultTables, $tables);
            }

            foreach ($tables as $table)
            {
                $items = $table->xpath('fk');
				if (count($items) > 0)
				{
					$relations = array();
					foreach ($items as $item)
					{
                    	$itemAttributes = $item->attributes();
                    	$recursive = 0;
                    	if (isset($itemAttributes['recursive']))
							$recursive = 1;

                    	$arr = array('name' => (string)$itemAttributes->name,
                    				 'table' => (string)$itemAttributes->table,
                    				 'recursive' => $recursive);
                    	$relations[] = $arr;
					}
					if (!isset(self::$tablesInfo[$modelName]['relations']))
						self::$tablesInfo[$modelName]['relations'] = array();

					self::$tablesInfo[$modelName]['relations'] = array_merge(self::$tablesInfo[$modelName]['relations'], $relations);
					self::$tablesInfo[$modelName]['haveRelations'] = true;
				}

				$items = $table->xpath('item');
				if (count($items) > 0)
				{
					$types = $defaults = $description = $columns = $file_columns = $multilangColumns = $noMultilangColumns = array();
					foreach ($items as $item)
					{
                    	$itemAttributes = $item->attributes();
                    	$columns[] = (string)$itemAttributes['name'];

                    	if (isset($itemAttributes['file']) && $itemAttributes['file'] == 1)
                    		$file_columns[] = (string)$itemAttributes['name'];

                    	$types[(string)$itemAttributes['name']] = (string)$itemAttributes['type'];
						$defaults[(string)$itemAttributes['name']] = (string)$itemAttributes['default'];
						$description[(string)$itemAttributes['name']] = (string)$itemAttributes['description'];

                    	if (isset($itemAttributes['multilang']) && $itemAttributes['multilang'] == 1)
                    	{
                    		self::$tablesInfo[$modelName]['isMultilang'] = true;
                    		$multilangColumns[] = (string)$itemAttributes['name'];
						}
						else
						{
                    		$noMultilangColumns[] = (string)$itemAttributes['name'];
						}
					}

					if (!isset(self::$tablesInfo[$modelName]['file_columns']))
						self::$tablesInfo[$modelName]['file_columns'] = array();

					if (!isset(self::$tablesInfo[$modelName]['columns']))
						self::$tablesInfo[$modelName]['columns'] = array();

					if (!isset(self::$tablesInfo[$modelName]['types']))
						self::$tablesInfo[$modelName]['types'] = array();
						
					if (!isset(self::$tablesInfo[$modelName]['defaults']))
						self::$tablesInfo[$modelName]['defaults'] = array();
						
					if (!isset(self::$tablesInfo[$modelName]['description']))
						self::$tablesInfo[$modelName]['description'] = array();

					if (!isset(self::$tablesInfo[$modelName]['noMultilangColumns']))
						self::$tablesInfo[$modelName]['noMultilangColumns'] = array();

					if (!isset(self::$tablesInfo[$modelName]['multilangColumns']))
						self::$tablesInfo[$modelName]['multilangColumns'] = array();

					self::$tablesInfo[$modelName]['file_columns'] = array_merge(self::$tablesInfo[$modelName]['file_columns'], $file_columns);
					self::$tablesInfo[$modelName]['types'] = array_merge(self::$tablesInfo[$modelName]['types'], $types);
					self::$tablesInfo[$modelName]['defaults'] = array_merge(self::$tablesInfo[$modelName]['defaults'], $defaults);
					self::$tablesInfo[$modelName]['description'] = array_merge(self::$tablesInfo[$modelName]['description'], $description);
					self::$tablesInfo[$modelName]['columns'] = array_merge(self::$tablesInfo[$modelName]['columns'], $columns);
					self::$tablesInfo[$modelName]['noMultilangColumns'] = array_merge(self::$tablesInfo[$modelName]['noMultilangColumns'], $noMultilangColumns);
					self::$tablesInfo[$modelName]['multilangColumns'] = array_merge(self::$tablesInfo[$modelName]['multilangColumns'], $multilangColumns);
				}
            }
		}
		
		$forms = $shema->xpath('/model/form');
		
		if(is_array($forms)){
			foreach($forms as $form){
				$items = $form->xpath('item');
				if(count($items) > 0){
					$formA = $form->attributes();
					foreach($items as $item){
						$itemA = (array)$item->attributes(); 
						self::$tablesInfo[$modelName]['fields'][(string)$form['name']][(string)$item['name']] = $itemA['@attributes'];
					}
				}
			}
		}
		
		return true;
	}
	
	public static function LoadFieldsInfo($modelName, $type)
	{
		if (isset(self::$fieldsInfo[$modelName][$type]) && is_array(self::$fieldsInfo[$modelName][$type]))
			return true;

		$metaPath = (Application::$section !== 'frontend') ? Model::$conf->metaPath : Model::$conf->metaFrontendPath;

		if(!file_exists($metaPath . '/' . $modelName . '.xml')){
			$metaPath = Model::$conf->metaPath;
		}

		if (isset(self::$shema[$modelName])) $shema = self::$shema[$modelName];
		else $shema = self::$shema[$modelName] = simplexml_load_file($metaPath . '/' . $modelName . '.xml');

		$tables = $shema->xpath("/model/form[@name='".$type."']");

        if (count($tables) > 0)
		{
			$tableAttr = $tables[0]->attributes();

            if ($tableAttr->extends)
            {
				$metaPath = (Application::$section !== 'frontend') ? Model::$conf->metaPath : Model::$conf->metaFrontendPath;

				if(!file_exists($metaPath . '/' . $modelName . '.xml')){
					$metaPath = Model::$conf->metaPath;
				}
            	self::$fieldsInfo[$modelName][$type]['extends'] = $extends = (string)$tableAttr->extends;
            	if (isset(self::$shema[$extends])) $defaultShema = self::$shema[$extends];
				else $defaultShema = self::$shema[$extends] = simplexml_load_file($metaPath . '/' . $extends . '.xml');
				$defaultTables = $defaultShema->xpath("/model/form[@name='".$type."']");
				$tables = array_merge($defaultTables, $tables);
            }

            foreach ($tables as $table)
            {
               	$items = $table->xpath('item');
				if (count($items) > 0)
				{
					$types = $columns = $file_columns = $multilangColumns = $noMultilangColumns = array();
					foreach ($items as $item)
					{
                    	$itemAttributes = $item->attributes();
						
						$field = array();
						
						if($itemAttributes['name']) $field["name"] = (string)$itemAttributes['name'];
						if($itemAttributes['type']) $field["type"] = (string)$itemAttributes['type'];
						if($itemAttributes['required']) $field["required"] = (int)$itemAttributes['required'];
						if($itemAttributes['max_length']) $field["max_length"] = (int)$itemAttributes['max_length'];
						if($itemAttributes['min_length']) $field["min_length"] = (int)$itemAttributes['min_length'];
						if($itemAttributes['err_mess']) $field["err_mess"] = (int)$itemAttributes['err_mess'];
						if((self::$tablesInfo[$modelName]["defaults"][$field["name"]] !== "" and $field["type"] !== "Bool") or (intval(self::$tablesInfo[$modelName]["defaults"][$field["name"]]) > 0 and $field["type"] == "Bool")) $field["default"] = self::$tablesInfo[$modelName]["defaults"][$field["name"]];
						if(self::$tablesInfo[$modelName]["description"][$field["name"]] !== "") $field["description"] = self::$tablesInfo[$modelName]["description"][$field["name"]];
						
                    	$fields[$field["name"]] = $field;
					}
					self::$fieldsInfo[$modelName][$type] = $fields;
				}
            }
		}

		return true;
	}
	
	public static function GetColsInfo($modelName)
	{
		$content = array();
		
		$defaultShema = self::$shema[$modelName] = simplexml_load_file($metaPath . '/' . $modelName . '.xml');
		
		$n = 0;
		foreach($defaultShema->table[0]->item as $row_name => $val) {
		
			$item_name = $defaultShema->table[0]->item[$n]->attributes();
			$content[$item_name.""] = array();
			
				foreach($defaultShema->table[0]->item[$n]->attributes() as $name => $value) {
					
					if($name == 'multilang' and $value == '1'){
						$content[$item_name.""][$name.""] = $value;
					}
				}
				
			$n++;

		}
		
		return $content;
	}
	
	public static function GetTableColumns($modelName)
	{
		self::LoadModelInfo($modelName);
		return self::$tablesInfo[$modelName]['columns'];
	}

	public static function IsMultilang($modelName)
	{
		self::LoadModelInfo($modelName);
		return self::$tablesInfo[$modelName]['isMultilang'];
	}

	public static function IsTree($modelName)
	{ 
		self::LoadModelInfo($modelName); 
		return self::$tablesInfo[$modelName]['tree'];
	}

	public static function IsFile($modelName, $column)
	{
		self::LoadModelInfo($modelName);
		return (is_array(self::$tablesInfo[$modelName]['file_columns']) && in_array($column, self::$tablesInfo[$modelName]['file_columns'])) ? true : false;
	}

	public static function HaveRelations($modelName)
	{
		self::LoadModelInfo($modelName);
		return self::$tablesInfo[$modelName]['haveRelations'];
	}

	public static function GetMultilangTableColumns($modelName)
	{
		self::LoadModelInfo($modelName);
		return self::$tablesInfo[$modelName]['multilangColumns'];
	}
	
	public static function isMultilangColumn($modelName, $column)
	{
		$columns = self::GetMultilangTableColumns($modelName);
		if(is_array($columns))
			return in_array($column, $columns);
		else
			return false;
	}

	public static function GetNoMultilangTableColumns($modelName)
	{
		self::LoadModelInfo($modelName);
		return self::$tablesInfo[$modelName]['noMultilangColumns'];
	}

	public static function GetColumnType($modelName, $column)
	{
		self::LoadModelInfo($modelName);
		if (isset(self::$tablesInfo[$modelName]['types'][$column]))
        	return self::$tablesInfo[$modelName]['types'][$column];
		else
			return NULL;
	}

	public static function GetRelations($modelName)
	{
		self::LoadModelInfo($modelName);
		if (self::HaveRelations($modelName))
			return self::$tablesInfo[$modelName]['relations'];
		else
			return array();
	}
	
	public static function GetParentsAs($parent_ID, $type = 'Array', $item = 0, $parent_column = "parent_ID")
	{
		$return = array($parent_ID);
		$result = false;

		if($type == "Array"){
			while($result !== true){
				
				if($item !== 0){
					$table = "Item";
					$column = "category_ID";
					$item = 0;	
				}else{
					$table = "Category";
					$column = $parent_column;
				}
				
				$q = Model::$db->query("SELECT ".$column." FROM `".$table."` WHERE ID = '".$parent_ID."' AND block != 1");
		
				if(Model::$db->num($q) == 0){
					$result = true;
				}else{
					$result = false;
					$val = Model::$db->fetch($q);
					$parent_ID = $val[$column];
					$return[] = $val[$column];
				}
			}
		}
		
		if($type == "String"){
			
			$return = "";
			
			$arr = self::GetParentsAs($parent_ID, 'Array');
			
			$n = 1;
			foreach($arr as $key => $value){
				
				$return = ($n < count($arr)) ? $return.$value.", " : $return.$value;
				$n++;	
			}
		}
		
		return $return;
	}
	
	
	public static function GetChildrenAs($parent_ID, $type = 'Array', $model = 'Category', $parent_column = "parent_ID")
	{
		$return = array($parent_ID);
		$result = false;
		
		if($type == "Array"){
				$q = Model::$db->query("SELECT ID FROM `".$model."` WHERE ".$parent_column." = '".$parent_ID."' AND block != 1");
			
				if(Model::$db->num($q) !== 0){
					$val = Model::$db->fetch($q);
					$parent_ID = $val['ID'];
					do{
						$return = array_merge($return, self::GetChildrenAs($val['ID'], 'Array', $model));
						if(!in_array($val['ID'], $return)) { $return[] = $val['ID']; }
					}while($val = Model::$db->fetch($q));
				}
		}
	
		if($type == "String"){
			
			$return = "";
			
			$arr = self::GetChildrenAs($parent_ID, 'Array', $model);
			
			if(is_array($arr) and count($arr) > 0){
				$n = 1;
				foreach($arr as $key => $value){
					
					$return = ($n < count($arr)) ? $return.$value.", " : $return.$value;
					$n++;	
				}
			}else{
				$return = '';	
			}
		}
		
		return $return;
	}

	public static function GetRelationTableName($modelName, $relColumn)
	{
		$relations = self::GetRelations($modelName);
		if (self::HaveRelations($modelName))
		{
			foreach ($relations as $rel)
			{
				if ($rel['name'] == $relColumn)
					return $rel['table'];
			}
		}

		return NULL;
	}
	
	public static function Validate($params, $modelName, $formName, $validateType = CMF_VALIDATE_FULL)
	{
		$metaPath = (Application::$section !== 'frontend') ? Model::$conf->metaPath : Model::$conf->metaFrontendPath;

		if(!file_exists($metaPath . '/' . $modelName . '.xml')){
			$metaPath = Model::$conf->metaPath;
		}

		if (isset(self::$shema[$modelName])) $shema = self::$shema[$modelName];
		else $shema = self::$shema[$modelName] = simplexml_load_file($metaPath . '/' . $modelName . '.xml');

		$form = $shema->xpath('/model/form[@name="' . $formName . '"]');

        $errors = new Parameters();
        $errors->_extends = false;
        if (count($form) > 0)
		{
			$formAttr = $form[0]->attributes();

            if ($formAttr->extends)
            {
            	$errors->_extends = $extends = (string)$formAttr->extends;
            	if (isset(self::$shema[$extends])) $defaultShema = self::$shema[$extends];
				else $defaultShema = self::$shema[$extends] = simplexml_load_file($metaPath . '/' . $extends . '.xml');
				$defaultForm = $defaultShema->xpath('/model/form[@name="' . $formName . '"]');
				$form = array_merge($defaultForm, $form);
            }

			foreach ($form as $form1)
			{
				$items = $form1->xpath('item');
				if (count($items) > 0)
				{ 
					$datetime_columns = array();
					foreach ($items as $item)
					{
                    	$itemAttributes = $item->attributes();
						$itemUnique = (isset($itemAttributes['unique']) && $itemAttributes['unique']) ? true : false;
						$itemNotSave = (isset($itemAttributes['not_save']) && $itemAttributes['not_save']) ? true : false;
						$itemNotSave2 = (isset($itemAttributes['not_save2']) && $itemAttributes['not_save2']) ? true : false;
						$itemSaveFast = (isset($itemAttributes['save_fast']) && $itemAttributes['save_fast']) ? true : false;
						$itemType = (isset($itemAttributes['type']) && $itemAttributes['type']) ? $itemAttributes['type'] : NULL;
						$itemMatch = (isset($itemAttributes['match']) && $itemAttributes['match']) ? $itemAttributes['match'] : NULL;
						$itemValue = (isset($itemAttributes['value']) && $itemAttributes['value']) ? $itemAttributes['value'] : false;
						$itemCrypt = (isset($itemAttributes['crypt']) && $itemAttributes['crypt']) ? true : false;
						$itemName = $itemAttributes['name'];
						$itemErrMess = (isset($itemAttributes['err_mess']) && $itemAttributes['err_mess']) ? $itemAttributes['err_mess'] : NULL;
                        $itemRequired = (isset($itemAttributes['required']) && $itemAttributes['required']) ? true : false;

                        $itemMinLength = (isset($itemAttributes['min_length'])) ? $itemAttributes['min_length'] : false;
                        $itemMaxLength = (isset($itemAttributes['max_length'])) ? $itemAttributes['max_length'] : false;
						
                        $itemSeo = (isset($itemAttributes['seo']) && $itemAttributes['seo']) ? $itemAttributes['seo'] : false;

						$itemErr = "err_".$itemName;
						$empty = false;
						
						if(self::isMultilangColumn($modelName, $itemName)){
							$modelNameExec =  $modelName."_ml";	
						}else{
							$modelNameExec =  $modelName;	
						}
						
						if (!$itemSaveFast && $validateType == CMF_VALIDATE_FAST) continue;

						if ($itemSeo && $validateType !== CMF_VALIDATE_FAST)
                        { 
							if(trim($params->$itemName->Val()) == ""){
								$seo = explode(",", $itemSeo);
								
								$seocan = array();
								foreach($seo as $seoNameVal){
									if(trim($params->$seoNameVal->Val()) !== "")
										$seocan[] = $seoNameVal;
								} 
								if(count($seocan > 0)){
									$n = 1;
									foreach($seocan as $seoNameVal){
										if($n == 1){
											$params->$itemName = self::CorrectSeoName($modelNameExec, $itemName, $params->$seoNameVal->Val(), ($params->ID->Val() ? $params->ID->Val() : NULL));
										}else{
											$params->$itemName = $params->$itemName->Val().self::CorrectSeoName($modelNameExec, $itemName, " ".$params->$seoNameVal->Val(), ($params->ID->Val() ? $params->ID->Val() : NULL));
										}
										$n++;
									}
								}else{
									$nSeoName = $seo[0];
									$params->$itemName = self::CorrectSeoName($modelNameExec, $itemName, $params->$nSeoName->Val(), ($params->ID-Val() ? $params->ID->Val() : NULL));
								}
							}else{
								$params->$itemName = self::CorrectSeoName($modelNameExec, $itemName, $params->$itemName->Val(), ($params->ID->Val() ? $params->ID->Val() : NULL));	
							}
                        }
						
						if((!$params->$itemName->Val() || trim($params->$itemName->Val()) == "" || is_null($params->$itemName->Val())) && $itemRequired){
							self::SetValidateError($errors, $itemName, lang("Это поле является обязательным"));
						}

						if((!$params->$itemName->Val() || trim($params->$itemName->Val()) == "" || is_null($params->$itemName->Val()))){
							$empty = true; 
						}

						if ($itemType && !$itemValue)
						{
							$checker = new Checker((string)$itemType);
                            /*if (in_array($itemType, array('Int', 'String', 'Double', 'LiteralString', 'Text')))
                            {
                            	if (!$itemMaxLength) $itemMaxLength = 100000;
                        		if (!$itemMinLength) $itemMinLength = -100000;

                        		if ($itemRequired && $itemMinLength == 0)
                        			$itemMinLength = 1;

                        		$checker->SetInterval($itemMinLength . ':' . $itemMaxLength);
                            }*/
							
							if (!$itemMaxLength) $itemMaxLength = 100000;
                        	if (!$itemMinLength) $itemMinLength = -100000;
							
							$old_value = $params->$itemName->Val();
							if(!$empty){
								list($params->$itemName) = $checker->Get($params->$itemName->Val()); 
								if(!$params->$itemName->Val()){
									self::SetValidateError($errors, $itemName, lang("Неверный формат поля"));
								}
							}elseif($itemType == "IntArray" or $itemType == "StringArray" or $itemType == "ArrayValue"){
								list($params->$itemName) = $checker->Get($params->$itemName->GetInfo());
							}
							
							if ($itemType == 'File' && $params->$itemName->Val() instanceof File)
							{
								$params->$itemName = $params->$itemName->Val()->GetValue();
                            }

				    		if ($itemType == 'Datetime')
							{
								$datetime_columns[] = $itemName;
							}
							
                        }
						
						if (!$itemMaxLength) $itemMaxLength = 100000;
                       	if (!$itemMinLength) $itemMinLength = -100000;

                        if ($itemValue)
                        	$params->$itemName = $itemValue;
                        elseif ($old_value === '_NULL' && !$itemRequired)
                        	$params->$itemName = $old_value;

						if(strlen($params->$itemName->Val()) > $itemMaxLength){
							self::SetValidateError($errors, $itemName, lang("Длина превышает допустимое значение в")." ".$itemMaxLength." ".lang("символов"));
						}
						
						if(strlen($params->$itemName->Val()) < $itemMinLength){
							self::SetValidateError($errors, $itemName, lang("Длина не достигает допустимого значения в")." ".$itemMinLength." ".lang("символов"));
						}
						
                        // Уникальность 
                        if (($itemUnique && !$itemNotSave && !self::IsUniqColumn($modelNameExec, $itemName, $params->$itemName->Val(), ($params->ID->Val()?$params->ID->Val():false), self::IfMultilangTable($modelName))) ||
                        	(!is_null($itemMatch) && $params->$itemName->Val() != $params->$itemMatch->Val()) )
                        {
                        	if ($validateType !== CMF_VALIDATE_FAST)
                        	{
								if ($itemErrMess !== null)
                        			self::SetValidateError($errors, $itemName, lang((string) $itemErrMess));
								else
                        			self::SetValidateError($errors, $itemName, lang("Это поле должно быть уникальным"));
                        	}
                        }
						else
                        {
                        	if (!$itemNotSave2)
                        	{
                        		if ($validateType == CMF_VALIDATE_FAST && !is_null($params->$itemName->Val()))
                        		{ 
                        			if (!($itemType == 'Bool' && $params->$itemName->Val() == 0))
                        				$errors->$itemName = $params->$itemName;
									
									if($itemSaveFast){
										$errors->$itemName = $params->$itemName->Val();
									}
                          		}
                        		elseif ($validateType != CMF_VALIDATE_FAST)
                        		{ 
									if($itemType == 'Bool' and (int)$params->$itemName->Val() == 0){
                        				$errors->$itemName = "0";
									}else{
										$errors->$itemName = $params->$itemName;
									}
                                }
                        		if ($itemCrypt && $errors->$itemName->Val())
                        			$errors->$itemName = md5($errors->$itemName->Val());
                        	}
                        }
						
						if($errors->errors->Count() and $errors->errors->$itemName->Count()){
							$errorsArr = $errors->errors->$itemName->GetInfo();
							$itemErrorMess = "err_".$itemName."_mess";
							$errors->$itemErrorMess = $errorsArr[0];
						}
            		}
            	}
    		}
    	}

    	if ($errors->err->Val() == 1 && count($datetime_columns) > 0)
    	{
    		foreach ($datetime_columns as $itemName)
			{
				$errors->$itemName = date(self::$conf->date_format, strtotime($errors->$itemName->Val()));
			}
        }
	
		if(!$errors->err->Val()){
			$errors->welldone = 1;	
		}

    	return $errors;
	}
	
	public static function SetValidateError(&$errors, $itemName, $mess = "")
	{
		$itemName = (string)$itemName;
		
		$itemErr = "err_".$itemName;
		$itemErrMess = "err_".$itemName."_mess";
		
		$errors->err = 1;
		$errors->$itemErr = 1;
		$itemErrors = $errors->errors->$itemName->GetInfo();
		$itemErrors[] = $mess;
		if(trim($mess) !== "") $errors->errors->$itemName = $itemErrors;
	}
	
	public static function GetMaxPos($modelName)
    {
		$maxPos = self::$db->Value("SELECT MAX(pos) FROM `" . $modelName . "`");
		if($maxPos == 1){ $maxPos = 0; }
    	return $maxPos + 10;
    }

    public static function GetNextID($modelName)
    {
    	return self::$db->Value("SELECT MAX(ID) FROM `" . $modelName . "`") + 1;
    }


    public static function GetSmartParameters($modelName)
    {
    	$arr = self::$session->GetInfo();
    	$modelSmartParameters = array();
    	if (count($arr) > 0)
    	{
    		foreach ($arr as $key => $value)
    		{
            	if (preg_match('#^cmf_' . $modelName . '_default_#isu', $key))
            		$modelSmartParameters[str_replace('cmf_' . $modelName . '_default_', '', $key)]	= $value;
    		}
    	}

    	return $modelSmartParameters;
    }
	
	public static function GetRelationsList($modelName, $declude = array(), $data = '')
	{ 
        $content = array(); 
        $relations = self::GetRelations($modelName);
	
		if (count($relations) > 0)
		{
			foreach ($relations as $arr)
			{
				if (in_array($arr['name'], $declude))
					continue;

				$relModel = $arr['table'];
				$relName = $arr['name'];
				$params = new Parameters();
                $params->onPage = 100000;
                $params->rel = 1;

				if ($arr['recursive'] == 1)
				{
					$smartParameters = self::GetSmartParameters($modelName);

					if (isset($smartParameters['where'][$relName])){
						$params->where->$relName = $smartParameters['where'][$relName];
					}elseif($data instanceof Parameters and $data->where->$relName->Val()){ 
						$params->where->$relName = $data->where->$relName->Val();
					}else{
	    				$params->where->$relName = 1; }

	    			if ($params->where->$relName->Val())
	    			{ 
	    				$model = new $relModel($params->where->$relName->Val());
						$content[$arr["name"] . "_block"] = $model->GetPathFilter();
		
					}
				}
				else
				{
					eval('$content[$arr["name"] . "_block"] = ' . $relModel . '::GetList($relModel, $params);');
				}
			}
		}
		return $content;
	}
	
	public static function GetRecursiveRelations($modelName)
	{
		$content = array();
        $relations = self::GetRelations($modelName);
		if (count($relations) > 0)
		{
			foreach ($relations as $arr)
			{
				$relModel = $arr['table'];
				$relName = $arr['name'];

				if ($arr['recursive'] == 1)
				{
					$content[] = $arr;
				}
			}
		} 
		return $content;
	}
	
	public static function GetListTree($modelName, $params, $array, $arrayName = 'items', $multiSelect = '')
	{
		$content = array();	
		
		$where = array('is' => array(), 'not' => array());
		
		if($params->whereThis->Count() > 0) { 
			$where_val = $params->whereThis->GetInfo(); 
			foreach($where_val as $key => $value){
				if($key !== 'not'){
					$where['is'][$key] = $value;
				}else{
					$name = $params->whereThis->not->GetInfo();
					
					foreach($name as $key2 => $value2){
						$val = $params->whereThis->not->$key2->Val();
						$where['not'][$key2] = $value;
					}
				}
			}
		}
		
		if($params->whereThis->Count() > 0 and $multiSelect == '') { 
			throw new Exception(lang("Не указан параметр").' whereThis. [function: GetListTree, controller: ' . $modelName . ']');	
		}
		
		if($multiSelect){
			$multiSelectNew = new Parameters();
			$multiSelect2 = $multiSelect->GetInfo();
			$n = 1;
			foreach($multiSelect2 as $key => $value){ 
				$num = "num".$n;
				$multiSelectNew->$num = $multiSelect->$key;
				$n++;

				if($multiSelectNew->$num->whereThis->Count() > 0) { 
				
					$where[$num] = array('is' => array(), 'not' => array());
				
					$where_val = $multiSelectNew->$num->whereThis->GetInfo(); 
					foreach($where_val as $key => $value){
						if($key !== 'not'){
							$where[$num]['is'][$key] = $value;
						}else{
							$not = $multiSelectNew->$num->whereThis->not->GetInfo();
							
							foreach($not as $key2 => $value2){
								$val = $multiSelectNew->$num->whereThis->not->$key2->Val();
								$where[$num]['not'][$key2] = $value;
							}
						}
					}
				}		
			}
		}

		foreach($array[$arrayName] as $key => $value){
			
			if(!$multiSelect){
				foreach($where['is'] as $key2 => $value2){
					$params->where->$key2 = $array[$arrayName][$key][$value2];	
				}
					
				foreach($where['not'] as $key3 => $value3){
					$params->where->not->$key3 = $array[$arrayName][$key][$value3];
				}
			}

			if($multiSelect){
				$array[$arrayName][$key] = self::multiSelect($modelName, $multiSelectNew, $array[$arrayName][$key]);
			}else{
				eval("$array[$arrayName][$key]['_itemsTree'] = ".$modelName."::GetList($modelName,$params);");
			}
			
		}
	
        return $array;
	}
	
	
	public static function multiSelect($modelName = 'Item', $multiSelect, $array, $count = 1, $arrayKey = ''){
		
		$multiSelect2 = $multiSelect->GetInfo();
	
		if($arrayKey !== ''){ $key = $arrayKey; }else{ $key = "num".$count; }
	
				
				$mSelect = $multiSelect2; 
				foreach($mSelect[$key]['whereThis'] as $key2 => $value2){ 
					$multiSelect->$key->where->$key2 = $array[$value2]; 
				}
				if(isset($mSelect[$key]['whereThis']['not']) and is_array($mSelect[$key]['whereThis']['not'])){	
					foreach($mSelect[$key]['whereThis']['not'] as $key3 => $value3){
						$multiSelect->$key->where->not->$key3 = $array[$value3];
					}
				}
			

			$params = $multiSelect->$key;
			if($params->table->Val()){
				$modelName = $params->table->Val();	
			}
			
			eval("\$array['_itemsTree'] = ".$modelName."::GetList(\$modelName,\$params);");
		
		if($count < count($multiSelect2)){
			$count2 = $count + 1;
			foreach($array['_itemsTree']['items'] as $key2 => $value2){
				$array['_itemsTree']['items'][$key2] = self::multiSelect($modelName, $multiSelect, $array['_itemsTree']['items'][$key2], $count2, "num".$count2);
			}
		}
		$count++;

		return $array;
		
	}

	protected static function CorrectSeoName($modelName, $column, $value, $declude_ID = NULL)
    {
   		if ($value)
   			$value = Utils::RusLat($value);
   		else
   			$value = NULL;

   		if (!preg_match('#^[a-z0-9_-]+$#', $value))
   		{
        	$value = 'auto-' . time();
        }

   		$count = 1;
        $oldValue = $value;
   		while (!self::IsUniqColumn($modelName, $column, $value, $declude_ID, self::IfMultilangTable($modelName)))
   		{
   			$value = $oldValue . '-' . $count;
   			$count++;
   		}

   		return $value;
    }
	
	public static function IsUniqColumn($modelName, $column, $value, $declude_ID = NULL, $lang = false)
	{
		if($lang){
			$langq = " AND lang_ID = '".Application::$language->ID."'";
		}else{
			$langq = "";	
		}
		
		if ($declude_ID) 
			return ((int)self::$db->Value("SELECT COUNT(*) FROM `" . $modelName . "` WHERE " . $column . " = '" . $value . "' AND ID <> '" . $declude_ID . "'") == 0);
		else
			return ((int)self::$db->Value("SELECT COUNT(*) FROM `" . $modelName . "` WHERE " . $column . " = '" . $value . "'") == 0);
	}

}



?>