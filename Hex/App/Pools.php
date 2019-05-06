<?php

namespace Hex\App;

class Pools
{
    public static $pools;

    /**
     * @param $name
     * @return Pool
     */
    public function find($name)
    {
        if (! isset(self::$pools[$name])) {
            self::$pools[$name] = new Pool();
        }

        return self::$pools[$name];
    }
}