<?php

class App{
    protected $controller = "Login";
    protected $method = "index";
    protected $params = [];

    function __construct(){
        $url = $this->parseUrl();
//        controller check
        if(isset($url[0]) && file_exists("../app/controllers/" . ucfirst($url[0]) . ".php")){
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

//        method check
        require_once "../app/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller;
        if(isset($url[1])){
            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }

//        params
        $this->params = $url ? array_values($url) : [];

//        call everything
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    public function parseUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
