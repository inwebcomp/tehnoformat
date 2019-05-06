<?php

class controller_contacts
{
    public function map()
    {
        return [

        ];
    }

    public function items()
    {
        return [
            'contacts' => Contacts::getAll()
        ];
    }
}