<?php
session_start();

use Router\Router;

require './../vendor/autoload.php';

define('BASE_VIEW_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);

$router = new Router($_GET['url'] ?? '/');

// HOME
$router->get('/', [Controllers\HomeController::class, 'index'], 'home');

// LINKS
$router->get('/links', [Controllers\LinkController::class, 'index'], 'links.show')->withMiddleware('auth');

$router->get('/l/:id', [Controllers\LinkController::class, 'short'], 'link.short')->with('id', '[0-9]+');

$router->get('/link/new', [Controllers\LinkController::class, 'new'], 'link.create')->withMiddleware('auth');

$router->get('/link/:id', [Controllers\LinkController::class, 'edite'], 'links.edite')->withMiddleware('auth')->with('id', '[0-9]+');
$router->put('/link/:id', [Controllers\LinkController::class, 'update'], 'links.update')->withMiddleware('auth')->with('id', '[0-9]+');
$router->delete('/link/delete/:id', [Controllers\LinkController::class, 'delete'], 'links.delete')->withMiddleware('auth')->with('id', '[0-9]+');
$router->delete('/link/:delete/:id', [Controllers\LinkController::class, 'delete'])->with('id', '[0-9]+');

$router->post('/link', [Controllers\LinkController::class, 'store'], 'link.store')->withMiddleware('auth');

// USER
$router->get('/login', [Controllers\UserController::class, 'login'], 'user.login')->withMiddleware('guest');
$router->put('/login', [Controllers\UserController::class, 'loginAction'], 'user.login.action')->withMiddleware('guest');

$router->get('/register', [Controllers\UserController::class, 'register'], 'user.register')->withMiddleware('guest');
$router->post('/register', [Controllers\UserController::class, 'registerAction'], 'user.register.action')->withMiddleware('guest');

$router->get('/logout', [Controllers\UserController::class, 'logout'], 'user.logout')->withMiddleware('auth');

// $router->prefix('/link', function ($router) {

//     $router->get('/link/:id', ['Controllers\LinkController', 'edite'], 'links.edite')->withMiddleware('auth')->with('id', '[0-9]+');
//     $router->post('/link/:id', ['Controllers\LinkController', 'update'], 'links.update')->withMiddleware('auth')->with('id', '[0-9]+');
//     $router->post('/link/delete/:id', ['Controllers\LinkController', 'delete'], 'links.delete')->withMiddleware('auth')->with('id', '[0-9]+');
//     $router->post('/link', ['Controllers\LinkController', 'store'], 'link.store')->withMiddleware('auth');

// });

$router->routeList();

// RUN THE APP
$router->run();
