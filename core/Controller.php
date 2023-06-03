<?php

namespace app\core;

    class Controller 
    {
        protected array $middlewares = [];
        public string $action = '';
        public string $layout = 'main'; // default layout to 'views/layouts/main'
        public function setLayout($layout)  //change layout from controllers
        {
            $this->layout = $layout;
        }

        public function render($view, $parems = [])    
        {
            return Application::$app->view->view($view, $parems); // call display function from view class of project with (view filename and data array)
        }

        public function registerMiddleware($middleware)
        {
            $this->middlewares[] = $middleware;
        }

        public function getMiddlewares() : array
        {
            return $this->middlewares;
        }
    }

?>