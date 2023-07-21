<?php

namespace Controllers;

use MVC\Router;
use Model\Paquete;
use Model\Usuario;
use Model\Registro;
use Classes\Paginacion;

class RegistradosController {

  public static function index(Router $router) {
    // --- VALIDAR Y REDIRECCIONAR --- //
    if (!is_admin()) {
      header('Location: /login');
    }

    // --- AL HACER SUBMIT CON BUSCADOR  --- //
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // --- VALIDAR Y REDIRECCIONAR --- //
      if (!is_admin()) {
        header('Location: /login');
      }
      
      $pagina = $_POST['pagina'];

      if ($pagina) {
        header("Location: ?page=$pagina");
      }
    }
    // --- PAGINA ACTUAL --- //
    $pagina_actual = $_GET['page'] ?? null;
    $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
    $total_registros = Registro::total();
    $registros_por_pagina = 3;
    $numero_limite_paginacion = 3;

    // --- INICIO DE PAGINACION --- //
    $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total_registros, $numero_limite_paginacion);

    // --- TOTAL PAGINAS A MOSTRAR --- //
    $total_paginas = $paginacion->total_paginas();


    if ($pagina_actual < 1 || !$pagina_actual || $pagina_actual > $total_paginas) {
      header('Location: /admin/registrados?page=1');
    }

    // --- OFFSET SEGUN PAGINA --- //
    $offset = $paginacion->offset();

    $registros = Registro::fetch_records_lazy($registros_por_pagina, $offset);

    // --- PAGINACION QUE SE MUESTRA EN VISTA --- //
    $paginacion = $paginacion->paginacion();

    // --- CRUCE DE DATOS --- //
    foreach ($registros as $registro) {
      $registro->usuario = Usuario::find($registro->usuario_id);
      $registro->paquete = Paquete::find($registro->paquete_id);
    }

    // --- RENDER --- //
    $router->render('admin/registrados/index', [
      'titulo' => 'Usuarios Registrados',
      'registros' => $registros,
      'paginacion' => $paginacion
    ]);
  }
}
