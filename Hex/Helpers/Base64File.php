<?php

namespace Hex\Helpers;

class Base64File
{
    public $value;
    public $ext;
    public $content;
    public $path;

    public function __construct($value)
    {
        $this->value = $value;

        $parsed = $this->parse($value);

        $this->ext = $parsed['ext'];
        $this->content = $parsed['content'];
    }

    public function parse($value)
    {
        $info = [];

        $explode = explode(',', $value);
        $format = str_replace(
            [
                'data:image/',
                'data:application/',
                ';',
                'base64',
            ],
            '',
            $explode[0]
        );

        $info['ext'] = $format;
        $info['content'] = $explode[1] ?? false;

        if (! $info['content'])
            return false;

        return $info;
    }

    public function save($path)
    {
        $decoded = base64_decode($this->content);
        
        if (file_put_contents($path, $decoded)) {
            $this->path = $path;
        }
    }

    public function isSaved()
    {
        return !! $this->path;
    }

    public function checkSize($Mb)
    {
        $max = ($Mb * 1024 * 1000 * 4 / 3);

        if (strlen($this->content) > $max)
            return false;

        return true;
    }

    public function checkExtension($only)
    {
        if (! in_array($this->ext, $only))
            return false;

        return true;
    }

    public function remove()
    {
        unlink($this->path);
    }
}