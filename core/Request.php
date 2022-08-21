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
        if ($questionMarkPosition === false) {   // this means the REQUEST is using GET method
            return $path;
        }
        // second parameter { 0 } is the starting index, 
        // third parameter { $questionMarkPosition } represent the length of the substring.
        return substr($path, 0, $questionMarkPosition);
    }


    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(){
        return $this->method()==='get';

    }

    public function isPost(){
        return $this->method()==='post';
        
    }


    public function getBody()
    {
        $body = [];
        if ($this->method() === 'get') {
            foreach($_GET as $key => $value){
                $body[$key] = filter_input(INPUT_GET,$key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->method() === 'post') {
            foreach($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}
