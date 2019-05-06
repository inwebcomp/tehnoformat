<?php
class Statistics extends DatabaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Statistics';
		$this->controllerName = 'statistics';

		parent::__construct($ID);
	}
	
	public function GetVisibleItems(){
		
		$content = Model::$db->ArrayValuesQ("SELECT block, COUNT(ID) count FROM Item GROUP BY block ORDER BY block ASC");
	
		foreach($content as &$value){
			$value["name"] = ($value["block"] == "1") ? lang("Скрыты") : lang("Видны");
			if($value["block"] !== "1"){
				unset($value["block"]);
			}
		}

		return $content;
		
	}
	
	public function GetItemsInCategoriesCount(){
		
		$items = Model::$db->ArrayValuesQ("SELECT category_ID, COUNT(ID) count FROM Item GROUP BY category_ID");
	
		foreach($items as &$value){
			$category = Category::GetMainCategory($value["category_ID"]);
			if(isset($content[$category->ID])){
				$content[$category->ID]["count"] += $value["count"];
			}else{
				$content[$category->ID] = array("category" => $category->title, "count" => $value["count"]);
			}
		}

		return $content;
		
	}
	
	public function GetNewUsers(){
		
		$count = Model::$db->Value("SELECT COUNT(ID) FROM User WHERE DATE(created) > '".date("Y-m-d", time() - 60 * 60 * 24 * 2)."'");	

		return $count;
		
	}
	
	public function GetNewFollowers(){
		
		$count = Model::$db->Value("SELECT COUNT(ID) FROM Followers WHERE DATE(created) > '".date("Y-m-d", time() - 60 * 60 * 24 * 2)."'");	

		return $count;
		
	}

}

?>