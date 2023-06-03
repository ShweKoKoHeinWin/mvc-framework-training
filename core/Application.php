<?php

namespace app\core;
use app\core\db\Database;

    class Application 
    {
        const EVENT_BEFORE_REQUEST = 'beforeRequest';
        const EVENT_AFTER_REQUEST = 'afterRequest';

        protected array $eventListeners = [];

        public static string $ROOT_DIR;
        public string $userClass;
        public Request $request;
        public Router $router;
        public Response $response;
        public static Application $app;
        public Session $session;
        public ?Controller $controller = null;
        public ?UserModel $user;
        public Database $db;
        public string $layout = 'main';
        public View $view;

        public function __construct($rootDir, array $config)   //Start project in public/index
        {
            self::$ROOT_DIR = $rootDir;     //$rootDir = public/index
            self::$app = $this;             //call project
            
            $this->request = new Request();   //initiate Request
            $this->response = new Response(); //initiate Response
            $this->session = new Session(); //initiate session
            $this->router = new Router($this->request, $this->response);    //initiate Router()
            $this->db = new Database($config['db']);     //initiate Database
            $this->view = new View;

            $this->userClass = $config['userClass'];
            $primaryValue = $this->session->get('user');
            if($primaryValue) {
                $primaryKey = $this->userClass::primaryKey();
                $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
            } else {
                $this->user = null;
            }
            
        }

        public static function isGuest()
        {
            return !self::$app->user; 
        }

        public function getController()
        {
            return $this->controller;
        }

        public function setController(Controller $controller)
        {
            return $this->controller = new Controller;
        }

        public function run()
        {
            $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
            $this->triggerEvent(self::EVENT_AFTER_REQUEST);
            try {
                return $this->router->resolve();
            } catch(\Exception $e) {
                $this->response->setStatusCode($e->getCode()); 
                return $this->view->view('error', ['exceptions' => $e]);
            }
            
        }

        public function triggerEvent($eventName)
        {
            $callbacks = $this->eventListeners[$eventName] ?? [];
            foreach($callbacks as $callback) {
                call_user_func($callback);
            };
        }


        public function login(UserModel $user)
        {
            $this->user = $user;
            $primaryKey = $user->primaryKey();
            $primaryValue = $user->{$primaryKey};
            $this->session->set('user', $primaryValue);
            return true;
        }

        public function logout()
        {
            $this->user = null;
            $this->session->remove('user');
        }

        public function on($eventName, $callback)
        {
            $this->eventListeners[$eventName][] = $callback;
        }
    }

?>