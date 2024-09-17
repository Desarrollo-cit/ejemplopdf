<?php
require_once __DIR__ . '/../includes/app.php';


use Controllers\EmailController;
use Controllers\FTPController;
use Controllers\ReporteController;
use MVC\Router;
use Controllers\AppController;
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class, 'index']);
$router->get('/pdf', [ReporteController::class, 'pdf']);
$router->get('/email', [EmailController::class, 'email']);
$router->get('/subir', [FTPController::class, 'subir']);
// $router->post('/API/subir', [FTPController::class, 'subirAPI']);
$router->post('/API/subir', [FTPController::class, 'subirLocalAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
