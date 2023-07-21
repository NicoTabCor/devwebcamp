<?php
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\APIEventos;
use Controllers\APIRegalos;
use Controllers\APIPonentes;
use Controllers\AuthController;
use Controllers\EventosController;
use Controllers\PaginasController;
use Controllers\RegalosController;
use Controllers\PonentesController;
use Controllers\RegistroController;
use Controllers\DashBoardController;
use Controllers\RegistradosController;

$router = new Router();
// --- AUTH --- //
// --- LOGIN --- //
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// --- CREAR CUENTA --- //
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

// --- FORMULARIO OLVIDE MI PASSWORD --- //
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

// --- COLOCAR NUEVO PASSWORD --- //
$router->get('/restablecer', [AuthController::class, 'restablecer']);
$router->post('/restablecer', [AuthController::class, 'restablecer']);

// --- CONFIRMAR CUENTA --- //
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);

// --- ADMIN ZONA PRIVADA --- //
// --- DASHBOARD --- //
$router->get('/admin/dashboard', [DashBoardController::class, 'index']);

// --- PONENTES --- //
$router->get('/admin/ponentes', [PonentesController::class, 'index']);
$router->post('/admin/ponentes', [PonentesController::class, 'index']);
$router->get('/admin/ponentes/crear', [PonentesController::class, 'crear']);
$router->post('/admin/ponentes/crear', [PonentesController::class, 'crear']);
$router->get('/admin/ponentes/editar', [PonentesController::class, 'editar']);
$router->post('/admin/ponentes/editar', [PonentesController::class, 'editar']);
$router->post('/admin/ponentes/eliminar', [PonentesController::class, 'eliminar']);

// --- API PONENTES --- //
$router->get('/api/ponentes', [APIPonentes::class, 'index']);

// $router->get('/api/ponente', [APIPonentes::class, 'ponente']); Ruta relacionada a una segunda forma de mostrar ponentes en admin para mostrar ponente actual

// --- EVENTOS --- //
$router->get('/admin/eventos', [EventosController::class, 'index']);
$router->post('/admin/eventos', [EventosController::class, 'index']);
$router->get('/admin/eventos/crear', [EventosController::class, 'crear']);
$router->post('/admin/eventos/crear', [EventosController::class, 'crear']);
$router->get('/admin/eventos/editar', [EventosController::class, 'editar']);
$router->post('/admin/eventos/editar', [EventosController::class, 'editar']);
$router->post('/admin/eventos/eliminar', [EventosController::class, 'eliminar']);

// --- API EVENTOS --- //
$router->get('/api/eventos-horarios', [APIEventos::class, 'index']);

// --- REGISTRADOS --- //
$router->get('/admin/registrados', [RegistradosController::class, 'index']);
$router->post('/admin/registrados', [RegistradosController::class, 'index']);

// --- REGALOS --- //
$router->get('/admin/regalos', [RegalosController::class, 'index']);

// --- API REGALOS --- //
$router->get('/api/regalos', [APIRegalos::class, 'index']);

// --- REGISTRO DE USUARIOS --- // 
$router->get('/finalizar-registro', [RegistroController::class, 'crear']);
$router->post('/finalizar-registro/gratis', [RegistroController::class, 'gratis']);
$router->post('/finalizar-registro/pagar', [RegistroController::class, 'pagar']);
$router->get('/finalizar-registro/conferencias', [RegistroController::class, 'conferencias']);
$router->post('/finalizar-registro/conferencias', [RegistroController::class, 'conferencias']);

// --- BOLETO VIRTUAL --- //
$router->get('/boleto', [RegistroController::class, 'boleto']);

// --- AREA PUBLICA --- //
$router->get('/', [PaginasController::class, 'index']);
$router->get('/devwebcamp', [PaginasController::class, 'evento']);
$router->get('/paquetes', [PaginasController::class, 'paquetes']);
$router->get('/workshops-conferencias', [PaginasController::class, 'conferencias']);
$router->get('/404', [PaginasController::class, 'error']);

$router->comprobarRutas();
