<?php

namespace app\core;
use app\controllers\SiteController;
use app\controllers\AuthController;
use app\core\Controller;
use app\core\exceptions\NotFoundExceptions;

    class Router 
    {
        public Request $request;
        public Response $response;

        protected array $routes = [];   //Store all routes ['get' => [r1 => call1], 'post' => [r1 => call1]]

        public function __construct(Request $request, Response $response)   //Initiate Router by Request and Response
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

        public function resolve()   //run the routes
        {
            $path = $this->request->getPath();             // get path of current page from request (url)
            $method = $this->request->getMethod();         // get method of current page from request (get | post)
            $callback = $this->routes[$method][$path] ?? false; //get the callback work from routes by method and path if nothing, assign false

            if($callback === false) {       //if there is no route
                throw new NotFoundExceptions;          // show error page 
                exit();                                  //and stop project
            }
            if(is_string($callback)) {      //if route value is string
                // $callback = [SiteController::class, $callback];
                // Application::$app->controller = new $callback[0](); //
                // $callback[0] = Application::$app->controller;
                // return call_user_func($callback, $this->request);  //show the string.view.php

                return Application::$app->view->view($callback);
            }
            if(is_array($callback)) {       // if route value is array
                $controller = new $callback[0](); //assign Application Controller = $callback[0] obj
                Application::$app->controller = $controller;
                $controller->action = $callback[1];   //assign action to index1 string function name
                $callback[0] = $controller;       //ressign value[0] from class to obj

                foreach($controller->getMiddlewares() as $middleware) {
                    $middleware->execute();
                }
            }

            return call_user_func($callback, $this->request, $this->response);   //call function from associated class
        }

        // public function view($file , $params = []) //run page defined by controllers
        // {
        //     $layoutContent = $this->layoutContent();    //get layout file content
        //     $viewContent = $this->renderView($file, $params);  //get view file content
        //     return str_replace('{{content}}', $viewContent, $layoutContent); //put view file content to defined area of layout file
        // }

        // public function errorView($message) 
        // {
        //     $layoutContent = $this->layoutContent();
            
        //     return str_replace('{{content}}',$message, $layoutContent);
        // }

        // protected function layoutContent()  //call by view()
        // {
        //     $layout = Application::$app->controller->layout ?? Application::$app->layout;    // GET layout
        //     ob_start(); 
        //     include_once Application::$ROOT_DIR . "../views/layouts/{$layout}.php"; // return layout
        //     return ob_get_clean();
        // }

        // protected function renderView($file, $params) // call by view()
        // {
        //     foreach($params as $key => $value) {    // get data pass by user
        //         $$key = $value; //make easy to call by datakey
        //     }
            
        //     ob_start();
        //     include_once Application::$ROOT_DIR . "../views/{$file}.view.php"; //return content page
        //     return ob_get_clean();
        // }
    }

?>