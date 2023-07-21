<?php


namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Regalo;
use Model\Paquete;
use Model\Ponente;
use Model\Usuario;
use Model\Registro;
use Model\Categoria;
use Model\RegistroEvento;

class RegistroController {

  public static function crear(Router $router) {

    if (!is_auth()) {
      header('Location: /');
      return;
    }

    // --- VERIFICAR SI USUARIO ESTA REGISTRADO --- //
    $registro = Registro::where('usuario_id', $_SESSION['id']);

    if (isset($registro) && ($registro->paquete_id === '2' || $registro->paquete_id === '3')) {

      header('Location: /boleto?id=' . urlencode($registro->token));
      return;
    }

    if (isset($registro) && $registro->paquete_id === '1') {
      header('Location: /finalizar-registro/conferencias');
      return;
    }

    $router->render('registro/crear', [
      'titulo' => 'Finalizar Registro'
    ]);
  }

  public static function gratis() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!is_auth()) {
        header('Location: /login');
        return;
      }

      // --- VERIFICAR SI USUARIO ESTA REGISTRADO --- //
      $registro = Registro::where('usuario_id', $_SESSION['id']);

      if (isset($registro) && $registro->paquete_id === "3") {
        header('Location: /boleto?id=' . urlencode($registro->token));
        return;
      }

      // --- CREAR TOKEN --- //
      $token = substr(md5(uniqid(rand(), true)), 0, 8);

      $datos = [
        'paquete_id' => 3,
        'usuario_id' => $_SESSION['id'],
        'pago_id' => '',
        'token' => $token,
      ];

      $registro = new Registro($datos);

      $resultado = $registro->guardar();
      if ($resultado) {
        header('Location: /boleto?id=' . urlencode($registro->token));
        return;
      }
    }
  }

  public static function pagar() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // --- VERIFICAR AUTENTICADO --- //
      if (!is_auth()) {
        header('Location: /login');
        return;
      }
      // --- VALIDAR QUE POST NO VENGA VACIO --- //

      if (empty($_POST)) {
        echo json_encode([]);
        return;
      }

      // --- CREAR TOKEN --- //
      $token = substr(md5(uniqid(rand(), true)), 0, 8);

      // --- CREAR REGISTRO --- //
      $datos = [
        'paquete_id' => $_POST['paquete_id'],
        'pago_id' => $_POST['pago_id'],
        'token' => $token,
        'usuario_id' => $_SESSION['id']
      ];

      try {
        $registro = new Registro($datos);
        
        $resultado = $registro->guardar();
        echo json_encode($resultado);
      } catch (\Throwable $th) {
        echo json_encode(['resultado' => 'error']);
      }
    }
  }

  public static function boleto(Router $router) {

    // --- VALIDAR LA URL --- //
    $id = $_GET['id'];
    if (!$id || strlen($id) !== 8) {
      header('Location: /');
      return;
    }

    $registro = Registro::where('token', $id);

    if (!$registro) {
      header('Location: /');
      return;
    }

    $registro->usuario = Usuario::find($registro->usuario_id);
    $registro->paquete = Paquete::find($registro->paquete_id);

    $router->render('registro/boleto', [
      'titulo' => 'Asistencia DevWebCamp',
      'registro' => $registro
    ]);
  }

  public static function conferencias(Router $router) {

    if (!is_auth()) {
      header('Location: /');
      return;
    }

    $usuario = $_SESSION['id'];
    $registro = Registro::where('usuario_id', $usuario);

    // --- REDIRECCIONAR A SU BOLETO SI YA ESTA SU REGISTRO VIRTUAL --- //
    if (isset($registro) && ($registro->paquete_id === '2' || $registro->paquete_id === '3')) {

      header('Location: /boleto?id=' . urlencode($registro->token));
      return;
    }

    // --- REDIRECCIONAR SINO HAY BOLETO PRESENCIAL --- //
    if ($registro->paquete_id !== '1') {
      header('Location: /');
      return;
    }

    // --- VERIFICAR SI EVENTOS ESTAN REGISTRADOS Y REDIRECCIONAR --- //
    $selecciono = RegistroEvento::where('registro_id',  $registro->id);

    if ($registro->regalo_id && $selecciono) {
      header('Location: /boleto?id=' . urlencode($registro->token));
      return;
    }

    $eventos = Evento::ordenar('hora_id', 'ASC');

    $eventos_formateados = [];

    foreach ($eventos as $evento) {

      $evento->categoria = Categoria::find($evento->categoria_id);
      $evento->dia = Dia::find($evento->dia_id);
      $evento->hora = Hora::find($evento->hora_id);
      $evento->ponente = Ponente::find($evento->ponente_id);

      if ($evento->dia_id === "1" && $evento->categoria_id === "1") {
        $eventos_formateados['conferencias_v'][] = $evento;
      }

      if ($evento->dia_id === "2" && $evento->categoria_id === "1") {
        $eventos_formateados['conferencias_s'][] = $evento;
      }

      if ($evento->dia_id === "1" && $evento->categoria_id === "2") {
        $eventos_formateados['workshops_v'][] = $evento;
      }

      if ($evento->dia_id === "2" && $evento->categoria_id === "2") {
        $eventos_formateados['workshops_s'][] = $evento;
      }
    }

    $regalos = Regalo::all('ASC');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // --- REVISAR AUTENTICACION --- //
      if (!is_auth()) {
        header('Location: /login');
        return;
      }

      $eventos = json_decode($_POST['eventos']);

      // --- VALIDA QUE SE HAYA ENVIADO AL MENOS 1 EVENTO --- //
      if (empty($eventos)) {
        echo json_encode(['resultado' => false]);
        return;
      }

      // --- VALIDA QUE EL REGISTRO CORRESPONDA AL USUARIO AUTENTICADO  --- //
      if (!isset($registro) || $registro->paquete_id !== '1') {
        echo json_encode(['resultado' => false]);
        return;
      }

      // --- VALIDAR DISPONIBILIDAD DE EVENTOS SELECCIONADOS --- //
      $errores_disponib = [];
      $eventos_memoria = [];
      foreach ($eventos as $evento_obj) {
        $evento = Evento::find($evento_obj->id);
        if (!isset($evento) || $evento->disponibles === '0') {

          $errores_disponib[] = 'El Evento ' . $evento_obj->titulo . ' no posee mas cupos disponibles';
        }

        $eventos_memoria[] = $evento;
      }

      if (!empty($errores_disponib)) {
        echo json_encode([
          'resultado' => false,
          'mensaje' => $errores_disponib
        ]);
        return;
      }

      // --- ITERA EN LA MEMORIA LUEGO DE ASEGURARSE DE NO HABER ERRORES DE DISPONIBILIDAD NI EXISTENCIA EN LA DB --- //
      foreach ($eventos_memoria as $evento) {
        // --- ACTUALIZAR DISPONIBLES --- //
        $evento->disponibles -= 1;
        $resultado = $evento->guardar();

        // --- ALMACENAR EL REGISTRO --- //
        $datos = [
          'evento_id' => (int) $evento->id,
          'registro_id' => (int) $registro->id
        ];

        $registro_evento = new RegistroEvento($datos);
        $registro_evento->guardar();
      }

      $registro->sincronizar(['regalo_id' => $_POST['regalo_id']]);

      $resultado = $registro->guardar();

      if ($resultado) {
        echo json_encode([
          'resultado' => $resultado,
          'token' => $registro->token
        ]);
      }
      return;
    }


    $router->render('registro/conferencias', [
      'titulo' => 'Workshops y Conferencias',
      'eventos' => $eventos_formateados,
      'regalos' => $regalos
    ]);
  }
}
