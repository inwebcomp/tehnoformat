<?php

class controller_popups
{
    public function contact()
    {
        return [
            'title' => Infoblock::getText('contacts_form_header'),
            'description' => Infoblock::getText('contacts_form_description1'),
        ];
    }

    public function order()
    {
        return [
            'title' => Infoblock::getText('order_popup_header'),
            'description' => Infoblock::getText('order_popup_description'),
        ];
    }
}