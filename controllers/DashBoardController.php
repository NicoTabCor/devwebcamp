<?php

namespace Controllers;

use MVC\Router;
use Model\Evento;
use Model\Usuario;
use Model\Registro;

class DashBoardController {

  public static function index(Router $router) {

    session_start();

    // --- OBTENER ULTIMOS REGISTROS --- //
    $registros = Registro::get(5);

    foreach ($registros as $registro) {
      $registro->usuario = Usuario::find($registro->usuario_id);
    }

    // --- CALCULAR INGRESOS --- //
    $total_presencial = Registro::total('paquete_id', 1);
    $total_virtual = Registro::total('paquete_id', 2);
    $total_a_pagar = $total_presencial * 199.99 + $total_virtual * 49.99;

    // --- OBTENER EVENTOS CON MAS Y MENOS CUPOS DISPONIBLES --- //
    $menos_cupos = Evento::ordenar_limite('disponibles', 'ASC', 5);

    $mas_cupos = Evento::ordenar_limite('disponibles', 'DESC', 5);

    $router->render('admin/dashboard/index', [
      'titulo' => 'Panel de Administración',
      'registros' => $registros,
      'ingresos' => $total_a_pagar,
      'menos_cupos' => $menos_cupos,
      'mas_cupos' => $mas_cupos
    ]);
  }
}
