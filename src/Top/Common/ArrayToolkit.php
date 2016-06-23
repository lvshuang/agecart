<?php

namespace Top\Common;

class ArrayToolkit {

    public static function column(array $array, $columnName) 
    {
        if (empty($array)) {
            return array();
        }

        $column = array();
        foreach ($array as $item) {
            if (isset($item[$columnName])) {
                $column[] = $item[$columnName];
            }
        }

        return $column;
    }

    public static function parts(array $array, array $keys) 
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    public static function changes(array $before, array $after) 
    {
        $changes = array('before' => array(), 'after' => array());
        foreach ($after as $key => $value) {
            if (!isset($before[$key])) {
                continue;
            }
            if ($value != $before[$key]) {
                $changes['before'][$key] = $before[$key];
                $changes['after'][$key] = $value;
            }
        }
        return $changes;
    }

    public static function index($name, array $array = null) 
    {
        $indexedArray = array();
        if (empty($array)) {
            return $indexedArray;
        }

        foreach ($array as $item) {
            if (isset($item[$name])) {
                $indexedArray[$item[$name]] = $item;
                continue;
            }
        }
        return $indexedArray;
    }

}
