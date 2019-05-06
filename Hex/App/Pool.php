<?php

namespace Hex\App;

use Hex\App\Entity;

class Pool
{
    protected $data = array();
    
    public function push($object)
    {
        $this->data[$object->getId()] = $object;
    }

    public function set($id, $object)
    {
        $this->data[$id] = $object;
    }

    /**
     * Unstable method
     */
    public function fill(array $objects)
    {
        $this->data = $objects;
    }

    public function get($id)
    {
        return isset($this->data[$id]) ? $this->data[$id] : null;
    }

    public function has($id)
    {
        return isset($this->data[$id]);
    }

    public function remove($id)
    {
        if (array_key_exists($id, $this->data)) {
            unset($this->data[$id]);
        }
    }
    
    public function getAll()
    {
        return $this->data;
    }

    public function hasAny()
    {
        return (bool) count($this->data);
    }
}