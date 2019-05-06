<?php

class controller_statistics extends crud_controller_tree
{
    public function __construct()
    {
        $this->modelName = 'Statistics';
        $this->controllerName = 'statistics';
    }

    public function dashboard()
    {
        $content = [];

        $params = Application::$params;

        foreach ($params as $key => $value) {
            if (trim($value) !== '') {
                $content['_param_' . $key] = $value;
            }
        }

        return $content;
    }

    public function visitors($num = 21, $interval = 1)
    {
        $content = [];

        $num = intval($num);
        $num = ($num > 0) ? $num : 21;

        $interval = intval($interval);
        $interval = ($interval > 0) ? $interval : 1;

        $visits = Visits::GetVisitsCountByInterval($interval, $num);

        for ($i = 0;$i < $num;$i++) {
            $day = $i * $interval;
            $date = date('Y-m-d', time() - 3600 * 24 * $day);

            $vDate = Utils::DateToShortDate($date);
            if ($interval > 1) {
                $date2 = date('Y-m-d', time() - 3600 * 24 * ($day + $interval));
                $vDate = Utils::DateToShortDate($date2) . ' - ' . Utils::DateToShortDate($date);
            }

            $content['items'][$date] = ['date' => $vDate, 'count' => intval($visits[$date]['count'])];
        }

        $content['items'] = array_reverse($content['items']);

        $content['interval'] = $interval;

        return $content;
    }

    public function orders($num = 21, $interval = 1)
    {
        $content = [];

        $num = intval($num);
        $num = ($num > 0) ? $num : 21;

        $interval = intval($interval);
        $interval = ($interval > 0) ? $interval : 1;

        $visits = Orders::GetOrdersCountByInterval($interval, $num);

        for ($i = 0;$i < $num;$i++) {
            $day = $i * $interval;
            $date = date('Y-m-d', time() - 3600 * 24 * $day);

            $vDate = Utils::DateToShortDate($date);
            if ($interval > 1) {
                $date2 = date('Y-m-d', time() - 3600 * 24 * ($day + $interval));
                $vDate = Utils::DateToShortDate($date2) . ' - ' . Utils::DateToShortDate($date);
            }

            $content['items'][$date] = ['date' => $vDate, 'count' => intval($visits[$date]['count'])];
        }

        $content['items'] = array_reverse($content['items']);

        $content['interval'] = $interval;

        return $content;
    }

    public function browser()
    {
        $content = [];

        $browsers = Visits::GetBrowsers($interval, $num);

        $content['items'] = $browsers;

        return $content;
    }

    public function visible_items()
    {
        $content = [];

        $content['items'] = Statistics::GetVisibleItems();

        return $content;
    }

    public function categories()
    {
        $content = [];

        $content['items'] = Statistics::GetItemsInCategoriesCount();

        return $content;
    }

    public function online()
    {
        $content = [];

        $content['value'] = Visits::GetOnlineVisitors();

        return $content;
    }

    public function new_users()
    {
        $content = [];

        $content['value'] = Statistics::GetNewUsers();

        return $content;
    }

    public function new_visitors()
    {
        $content = [];

        $content['value'] = Visits::GetNewVisitors();

        return $content;
    }

    public function new_followers()
    {
        $content = [];

        $content['value'] = Statistics::GetNewFollowers();

        return $content;
    }

    public function courses_subscription($num = 21, $interval = 1)
    {
        $content = [];

        $num = intval($num);
        $num = ($num > 0) ? $num : 21;

        $interval = intval($interval);
        $interval = ($interval > 0) ? $interval : 1;

        $visits = Statistics::getCount($interval, $num);

        for ($i = 0;$i < $num;$i++) {
            $day = $i * $interval;
            $date = date('Y-m-d', time() - 3600 * 24 * $day);

            $vDate = Utils::DateToShortDate($date);
            if ($interval > 1) {
                $date2 = date('Y-m-d', time() - 3600 * 24 * ($day + $interval));
                $vDate = Utils::DateToShortDate($date2) . ' - ' . Utils::DateToShortDate($date);
            }

            $content['items'][$date] = ['date' => $vDate, 'count' => intval($visits[$date]['count'])];
        }

        $content['items'] = array_reverse($content['items']);

        $content['interval'] = $interval;

        return $content;
    }

    public function user_statistics($person_ID)
    {
        $content = [];

        $content['items'] = Statistics::getCountByPersonID($person_ID, 100000);

        foreach ($content['items'] as &$item) {
			$course = Item::find($item['course_ID']);

			if ($course and $course->real()) {
				$item['course_title'] = $course->title;
			}
        }

		$content['all'] = Statistics::getAllCount($person_ID);

        return $content;
    }
}
