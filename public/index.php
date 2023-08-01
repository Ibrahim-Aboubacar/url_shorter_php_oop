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
$router->get('/links', ['Controllers\LinkController', 'index'], 'links.show')->withAuth();

$router->get('/l/:id', ['Controllers\LinkController', 'short'], 'link.short')->with('id', '[0-9]+');

$router->get('/link/new', ['Controllers\LinkController', 'new'], 'link.create')->withAuth();

$router->get('/link/:id', ['Controllers\LinkController', 'edite'], 'links.edite')->withAuth()->with('id', '[0-9]+');
$router->post('/link/:id', ['Controllers\LinkController', 'update'], 'links.update')->withAuth()->with('id', '[0-9]+');
$router->post('/link/delete/:id', ['Controllers\LinkController', 'delete'], 'links.delete')->withAuth()->with('id', '[0-9]+');

$router->post('/link', ['Controllers\LinkController', 'store'], 'link.store')->withAuth();

// USER
$router->get('/login', ['Controllers\UserController', 'login'], 'user.login');
$router->post('/login', ['Controllers\UserController', 'loginAction'], 'user.login.action');

$router->get('/register', ['Controllers\UserController', 'register'], 'user.register');
$router->post('/register', ['Controllers\UserController', 'registerAction'], 'user.register.action');

$router->get('/logout', ['Controllers\UserController', 'logout'], 'user.logout');


$router->run();
