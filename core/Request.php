<?php

namespace app\core;
/**
 * @package app\core;
 */

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $questionMarkPosition = strpos($path, '?'); // strpos() returns FALSE if there's no '?'
        if ($questionMarkPosition === false){   // this means the REQUEST is using GET method
            return $path;
        }
        // second parameter { 0 } is the starting index, 
        // third parameter { $questionMarkPosition } represent the length of the substring.
        return substr($path, 0, $questionMarkPosition);  
    }


    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
