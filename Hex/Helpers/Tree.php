<?php

namespace Hex\Helpers;

class Tree
{
    public static $ID_field = 'ID';
    public static $parent_ID_field = 'parent_ID';
    public static $level_key = '_level';

    public static function sort($items)
    {       
        $parents = $new = array();

        foreach ($items as $item) {
            if ((int) $item[self::$parent_ID_field])
                $parents[$item[self::$parent_ID_field]][] = $item;
        }
    
        foreach ($items as $item) {
            if ((int) $item[self::$parent_ID_field] == 0) {
                $item[self::$level_key] = 0;
                $new[] = $item;

                $childs = self::findChilds($item[self::$ID_field], $parents, 1);

                if (isset($childs) and count($childs))
                    foreach ($childs as $child)
                        $new[] = $child;
            }
        }

        return $new;
    }

    public static function findChilds($id, $parents, $level = 0)
    {
        $new = array();

        if (isset($parents[$id])) {
            foreach ($parents[$id] as $item) {
                $item[self::$level_key] = $level;
                $new[] = $item;

                $childs = self::findChilds($item[self::$ID_field], $parents, $level + 1);

                if (count($childs))
                    foreach ($childs as $child)
                        $new[] = $child;
            }
        }

        return $new;
    }
}
