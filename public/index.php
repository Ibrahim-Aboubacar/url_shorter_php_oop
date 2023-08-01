<?php
session_start();

use Router\Router;

require './../vendor/autoload.php';

define('BASE_VIEW_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views'
    . DIRECTORY_SEPARATOR);

$router = new Router($_GET['url'] ?? '/');

// HOME
$router->get('/', ['Controllers\HomeController', 'index'], 'home');

// LINKS
$router->get('/links', ['Controllers\LinkController', 'index'], 'links.show')->middleware('auth'); #->middleware('auth');

$router->get('/l/:id', ['Controllers\LinkController', 'short'], 'link.short')->with('id', '[0-9]+');

$router->get('/link/new', ['Controllers\LinkController', 'new'], 'link.create')->middleware('auth');

$router->get('/link/:id', ['Controllers\LinkController', 'edite'], 'links.edite')->middleware('auth')->with('id', '[0-9]+');
$router->post('/link/:id', ['Controllers\LinkController', 'update'], 'links.update')->middleware('auth')->with('id', '[0-9]+');
$router->post('/link/delete/:id', ['Controllers\LinkController', 'delete'], 'links.delete')->middleware('auth')->with('id', '[0-9]+');

$router->post('/link', ['Controllers\LinkController', 'store'], 'link.store')->middleware('auth');

// USER
$router->get('/login', ['Controllers\UserController', 'login'], 'user.login')->middleware('guest');
$router->post('/login', ['Controllers\UserController', 'loginAction'], 'user.login.action')->middleware('guest');

$router->get('/register', ['Controllers\UserController', 'register'], 'user.register')->middleware('guest');
$router->post('/register', ['Controllers\UserController', 'registerAction'], 'user.register.action')->middleware('guest');

$router->get('/logout', ['Controllers\UserController', 'logout'], 'user.logout')->middleware('guest');


// RUN THE APP
$router->run();
