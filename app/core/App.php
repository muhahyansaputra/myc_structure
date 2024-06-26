<?php 

class App{
    private $controller = 'Home';
    private $method = 'index';
    private $args = [];

    public function __construct()
    {
        $url = $this->parse_url();
        if(empty($url)){
            require_once '../app/controllers/'.$this->controller.'.php';
            $this->controller = new $this->controller;
        }else{
            if(file_exists('../app/controllers/'.$url[0].'.php')){
                $this->controller = $url[0];
                unset($url[0]);
            }
            require_once '../app/controllers/'.$this->controller.'.php';
            $this->controller = new $this->controller;

            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }

            if(!empty($url)){
                $this->args = array_values($url);
            }
        }
        call_user_func_array([$this->controller, $this->method], $this->args);
    }

    public function parse_url(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url,FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}