<?php

require_once __DIR__ . "/../vendor/autoload.php";

use app\core\Application;
use app\controllers\SiteController;
use app\controllers\AuthController;
use app\models\User;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
    ];

$app = new Application(dirname(__DIR__), $config);

$app->on(Application::EVENT_BEFORE_REQUEST, function(){
    echo "<script>alert('hello')</script>";
});
$app->on(Application::EVENT_AFTER_REQUEST, function(){
    echo "event after";
});
$app->on(Application::EVENT_BEFORE_REQUEST, function(){
    echo "event before1";
});

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

$app->router->get('/about', [SiteController::class, 'about']);


$app->router->get('/login', [AuthController::class, 'logIn']);
$app->router->post('/login', [AuthController::class, 'logIn']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/profile', [AuthController::class, 'profile']);


echo $app->run();
echo "<pre>";
var_dump($app);




?>