<?php

if(!function_exists('array_column')) {
    function array_column($array, $field) {
        return array_map(function($item) use ($field) {
            return isset($item[$field])
            ? $item[$field]
            : null;
        }, $array);
    }
}
 
 