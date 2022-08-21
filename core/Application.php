<?php

namespace app\core;

/**
 * @package app\core;
 */


class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app; //declared for Router class to set httpStatusCode;

    public function __construct($rootPath)
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        self::$ROOT_DIR = $rootPath;
        self::$app = $this; // for Router class  to set httpStatusCode;
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}
