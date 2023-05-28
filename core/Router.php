<?php

namespace app\core;

class Router
{


    public  Request  $request;
    protected array $routes = [];
    public Response $response;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;

    }


    public function xavito ()
    {
        echo  "Sou desenvolvedor a mais de 1 ano";
    }
    public function resolve()
    {
       $path = $this->request->getPath();
       $method = $this->request->getMethod();

       $callback = $this->routes[$method][$path] ?? false;

       if ($callback === false)
       {
           $this->response->setStatusCode(404);
           return  "Não encontrado";

       }

       if (is_string($callback)) {
           return $this->renderViews($callback);
       }

       return call_user_func($callback);

    }

    public function  renderViews($views)
    {
        $layoutContent = $this->layoutContent();
        $viewsContent = $this->renderOnlyView($views);
        return str_replace('[contant]', $viewsContent, $layoutContent);

    }

    protected function layoutContent()
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layout/main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($views)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/$views.php";
        return ob_get_clean();

    }


}