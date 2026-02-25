<?php

class Controller{
    public function model($model){
        require "../app/models/" . $model . ".php";
        return new $model();

    }
    public function view($view, $data = []){
        if(file_exists("../app/views/" . $view . ".php")){
            require "../app/views/" . $view . ".php";
        }
        else{
//            show if view cant be found
            die("View doesn't exist");
        }
    }
    protected function isApiRequest() {
        return (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) ||
            (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'));
    }

    protected function sendJson($data, $code = 200) {
        header("Content-Type: application/json");
        http_response_code($code);
        echo json_encode($data);
        exit;
    }


    public function handleResponse($data, $status, $view = null) {
        if ($this->isApiRequest()) {
            $this->sendJson($data, $status);
        } else {
            if ($view) {
                $this->view($view, $data);
            } else {
                $this->sendJson($data, $status);
            }
        }
    }
}