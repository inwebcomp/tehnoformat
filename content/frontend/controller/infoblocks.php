<?php

class controller_infoblocks
{
    public function banner()
    {
        return [
            'banner_header'    => Infoblock::getText('banner_header'),
            'banner_paragraph' => Infoblock::getText('banner_paragraph'),
        ];
    }

    public function info()
    {
        return [
            'title'       => Infoblock::getText('index_header'),
            'description' => Infoblock::getText('index_description'),
        ];
    }

    public function services()
    {
        return [
            'services' => Service::getAll()
        ];
    }

    public function order()
    {
        return [
            'title'       => Infoblock::getText('order_header'),
            'description' => Infoblock::getText('order_description'),
        ];
    }

    public function advantages()
    {
        return [
            'advantages' => Advantage::getAll(),
            'title'      => Infoblock::getText('advantages_header'),
        ];
    }

    public function steps()
    {
        return [
            'steps' => Step::getAll(),
            'title' => Infoblock::getText('steps_header'),
        ];
    }

    public function contacts_form()
    {
        return [
            'title' => Infoblock::getText('contacts_form_header'),
            'description1' => Infoblock::getText('contacts_form_description1'),
            'description2' => Infoblock::getText('contacts_form_description2'),
        ];
    }

    public function contacts()
    {
        return [];
    }

    public function clients()
    {
        return [
            'clients'     => Client::getAll(),
            'title'       => Infoblock::getText('clients_header'),
            'description' => Infoblock::getText('clients_description'),
        ];
    }
}