<?php

use Hex\App\Auth;

class controller_pages
{
    public function info($object)
    {
        $page = Application::$mainObjectData ?? Pages::one($object);

        return $page->toArray();
    }

    public function save() {
        if (! Auth::logined() and !Auth::getCurrentUser()->isAdmin()) {
            header('HTTP/1.1 403 Forbidden');
            return;
        }

        $ID = $_POST['ID'];
        $value = $_POST['value'];

        $page = Pages::one($ID);

        if (! $page) {
            header('HTTP/1.1 404 Not Found');
            return;
        }

        Pages::updateField($page->ID,'text', $value);
    }
}