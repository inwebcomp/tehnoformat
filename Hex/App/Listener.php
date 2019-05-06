<?php

namespace Hex\App;

abstract class Listener
{
    /**
     * @var Event
     */
    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    abstract public function handle();
}