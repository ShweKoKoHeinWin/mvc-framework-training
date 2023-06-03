<?php

namespace app\core;

    class View
    {
        public $title = '';

        public function view($file , $params = []) //run page defined by controllers
        {
            $viewContent = $this->renderView($file, $params);  //get view file content
            $layoutContent = $this->layoutContent();    //get layout file content
            
            return str_replace('{{content}}', $viewContent, $layoutContent); //put view file content to defined area of layout file
        }

        public function errorView($message) 
        {
            $layoutContent = $this->layoutContent();
            
            return str_replace('{{content}}',$message, $layoutContent);
        }

        protected function layoutContent()  //call by view()
        {
            $layout = Application::$app->controller->layout ?? Application::$app->layout;    // GET layout
            ob_start(); 
            include_once Application::$ROOT_DIR . "../views/layouts/{$layout}.php"; // return layout
            return ob_get_clean();
        }

        protected function renderView($file, $params) // call by view()
        {
            foreach($params as $key => $value) {    // get data pass by user
                $$key = $value; //make easy to call by datakey
            }
            
            ob_start();
            include_once Application::$ROOT_DIR . "../views/{$file}.view.php"; //return content page
            return ob_get_clean();
        }
    }

?>