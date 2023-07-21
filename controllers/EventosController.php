<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;
use Classes\Paginacion;

class EventosController {

  public static function index(Router $router) {

    // --- COMPROBAR AUTORIZACION --- //
    if (!is_admin()) {
      header('Location: /login');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $pagina = $_POST['pagina'];

      if ($pagina) {
        header("Location: ?page=$pagina");
      }
    }

    $pagina_actual = $_GET['page'] ?? null;
    $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
    $eventos_por_pagina = 5;
    $numero_limite_paginacion = 5;
    $total_eventos = Evento::total();
    $paginacion = new Paginacion($pagina_actual, $eventos_por_pagina, $total_eventos, $numero_limite_paginacion);
    $max_paginas = $paginacion->total_paginas();

    if (!$pagina_actual || $pagina_actual < 1 || $pagina_actual > $max_paginas) {
      header('Location: /admin/eventos?page=1');
    }

    $eventos = $eventos = Evento::fetch_records_lazy($eventos_por_pagina, $paginacion->offset());

    foreach ($eventos as $evento) {
      $evento->categoria = Categoria::find($evento->categoria_id);
      $evento->dia = Dia::find($evento->dia_id);
      $evento->ponente = Ponente::find($evento->ponente_id);
      $evento->hora = Hora::find($evento->hora_id);
    }

    $router->render('admin/eventos/index', [
      'titulo' => 'Conferencias y Workshop',
      'eventos' => $eventos,
      'paginacion' => $paginacion->paginacion()
    ]);
  }

  public static function crear(Router $router) {

    // --- COMPROBAR AUTORIZACION --- //
    if (!is_admin()) {
      header('Location: /login');
    }

    $alertas = [];

    $categorias = Categoria::all();
    $dias = Dia::all('ASC');
    $horas = Hora::all('ASC');

    $evento = new Evento;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // --- COMPROBAR AUTORIZACION --- //
      if (!is_admin()) {
        header('Location: /login');
      }

      $evento->sincronizar($_POST);

      $alertas = $evento->validar();

      if (empty($alertas)) {
        $resultado = $evento->guardar();
        if ($resultado->resultado) {
          header('Location: /admin/eventos');
        }
      }
    }

    $router->render('admin/eventos/crear', [
      'titulo' => 'Registra un Evento',
      'alertas' => $alertas,
      'categorias' => $categorias,
      'dias' => $dias,
      'horas' => $horas,
      'evento' => $evento
    ]);
  }

  public static function editar(Router $router) {

    // --- COMPROBAR AUTORIZACION --- //
    if (!is_admin()) {
      header('Location: /login');
    }

    $alertas = [];

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id) {
      header('Location: /admin/eventos');
    }

    $categorias = Categoria::all();
    $dias = Dia::all('ASC');
    $horas = Hora::all('ASC');

    $evento = Evento::find($id);
    $evento->ponente = Ponente::find($evento->ponente_id);

    if (!$evento) {
      header('Location: /admin/eventos');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // --- COMPROBAR AUTORIZACION --- //
      if (!is_admin()) {
        header('Location: /login');
      }

      $evento->sincronizar($_POST);

      $alertas = $evento->validar();

      if (empty($alertas)) {
        $resultado = $evento->guardar();

        if ($resultado) {
          header('Location: /admin/eventos');
        }
      }
    }

    $router->render('admin/eventos/editar', [
      'titulo' => 'Editar Eventos',
      'alertas' => $alertas,
      'categorias' => $categorias,
      'dias' => $dias,
      'horas' => $horas,
      'evento' => $evento
    ]);
  }

  public static function eliminar() {
    // --- COMPROBAR AUTORIZACION --- //
    if (!is_admin()) {
      header('Location: /login');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // --- COMPROBAR AUTORIZACION --- //
      if (!is_admin()) {
        header('Location: /login');
      }

      // --- VERIFICAR QUE PETICION POST PROVENGA SOLO DE LA PAGINA DE EVENTOS GENERAL --- //
      $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
      if ($_SERVER["HTTP_ORIGIN"] !== 'http://localhost:3000' || !$id) {
        header('Location: /admin/eventos');
      }

      $evento = Evento::find($id);

      if (!isset($evento)) {
        header('Location: /admin/eventos');
      }

      $resultado = $evento->eliminar();
      if ($resultado) {
        header('Location: /admin/eventos');
      }
    }
  }
}
