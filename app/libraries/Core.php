<?php

// Creates  URL & loads core controller

class Core
{
    protected $currentController = 'pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();
        if (empty($url)) {
            header('location: '. URLROOT . '/posts');
            return;
        }
        // let's check the existence of the controller
        // if it exists let's assign to the current Controller var
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);

            unset($url[0]);
        }
        // require the controller [ ofc if does not exist the default controller is pages

        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check if method exist
        if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
            $this->currentMethod = $url[1];
            unset($url[1]);
        }
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    //  return url as an array  //// URL format : /controller/method/params

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            if (empty($url)) {
                die();
            }
            return  $url;
        }
    }
}
