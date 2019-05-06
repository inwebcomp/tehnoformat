<?php

class controller_testimonial extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Testimonial';
        $this->controllerName = 'testimonial';

        // Notifications
        $this->notifications = true;
    }

    public function _notification(){
        $count = Database::value("SELECT COUNT(*) FROM Testimonial WHERE block = 1");

        return ($count > 0) ? $count : NULL;
    }

    public function items($params)
    {
        if(!$params['order'] and (string)Model::$session->cmf_Item_default_order == "")
            $params['order '] = "pos";
        if(!$params['orderDirection'] and (string)Model::$session->cmf_Item_default_orderDirection == "")
            $params['orderDirection '] = "DESC";

        return parent::items($params);
    }
}