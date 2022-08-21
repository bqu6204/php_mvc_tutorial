<?php

namespace app\core;

/**
 * @package app\core;
 */



class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    // $routes array will look like :
    // ['get'] ['/']
    //         ['/contact']
    // ['post']['product']
    //         ['user']

    /**
     * Adds two numbers together.
     * @param \app\core\Request $request
     * @param \app\core\Response $response
     * @returns {number} Sum of numbers a and b
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderview('_404');
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if(is_array($callback)){
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }
        return call_user_func($callback, $this->request);
        // call_user_func會調用第一個參數，並將其餘參數傳遞給第一個參數使用。
        // 若欲調用class中的method，可將其放入array(str)，eg.[ 'Class' , 'method' ]。
        // 此處第一個參數為 [ 'app\controllers\SiteController' , 'handleContact' ]
        // 第二個參數為 $this->request 。
        // 因此會執行 SiteController->handleContact($this->request)
    }

    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/{$layout}.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value){
            $$key = $value;
            // 1. $key ＝= 'name'; 
            // 2. ${$key} ＝= $$key ＝= $name;  （ 在兩邊前面都加上一個錢符號。）
            // 3. $$key = $value; ( 等同於 $name = $value; )
            // 如此 {view}.php 中的變數（如 echo $name; ）即可直接使用。
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/{$view}.php";
        return ob_get_clean();
    }
}
