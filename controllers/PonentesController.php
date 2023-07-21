<?php


namespace Controllers;

use Classes\Paginacion;
use MVC\Router;
use Model\Ponente;
use Intervention\Image\ImageManagerStatic as image;

class PonentesController {

  public static function index(Router $router) {
    // --- VALIDAR Y REDIRECCIONAR --- //
    if (!is_admin()) {
      header('Location: /login');
    }

    // --- AL HACER SUBMIT CON BUSCADOR  --- //
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $pagina = $_POST['pagina'];

      if ($pagina) {
        header("Location: ?page=$pagina");
      }
    }

    // --- PAGINA ACTUAL --- //
    $pagina_actual = $_GET['page'] ?? null;
    $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

    if (!$pagina_actual ||  $pagina_actual < 1) {
      header('Location: /admin/ponentes?page=1');
    }

    $total_ponentes = Ponente::total();
    $ponentes_por_pagina = 4;
    $numero_limite_paginacion = 3;

    // --- INICIO DE PAGINACION --- //
    $paginacion = new Paginacion($pagina_actual, $ponentes_por_pagina, $total_ponentes, $numero_limite_paginacion);

    // --- TOTAL PAGINAS A MOSTRAR --- //
    $total_paginas = $paginacion->total_paginas();

    if ($pagina_actual < 1 || !$pagina_actual || $pagina_actual > $total_paginas) {
      header('Location: /admin/ponentes?page=1');
    }

    // --- OFFSET SEGUN PAGINA --- //
    $offset = $paginacion->offset();

    $ponentes = Ponente::fetch_records($ponentes_por_pagina, $offset);

    $paginacion = $paginacion->paginacion();

    // --- RENDER --- //
    $router->render('admin/ponentes/index', [
      'titulo' => 'Ponentes / Conferencistas',
      'ponentes' => $ponentes,
      'paginacion' => $paginacion
    ]);
  }

  public static function crear(Router $router) {
    // --- Proteger GET --- //
    if (!is_admin()) {
      header('Location: /login');
    }

    $alertas = [];

    $ponente = new Ponente();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // --- Proteger POST --- //
      if (!is_admin()) {
        header('Location: /login');
      }
      // --- Imagen --- //
      if (!empty($_FILES['imagen']['tmp_name'])) {
        // --- Crear nombre para archivo --- //
        $nombre_imagen = md5(uniqid(strval(rand()), true));

        // --- Inicializar Intervention --- //
        $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
        $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

        // --- Agrega al post para pasar la validacion --- //
        $_POST['imagen'] = $nombre_imagen;
      }
      // --- Sincronizar --- //
      $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);
      $ponente->sincronizar($_POST);

      // --- Validar --- //
      $alertas = $ponente->validar();

      if (empty($alertas)) {
        // --- Crear Carpeta sino existe --- //
        if (!is_dir(URL_SPEAKERS)) {
          mkdir(URL_SPEAKERS, 0755, true);
        }
        // --- Almacenar en Servidor --- //
        $imagen_png->save(URL_SPEAKERS . $nombre_imagen . '.png');
        $imagen_webp->save(URL_SPEAKERS . $nombre_imagen . '.webp');

        // --- Guardar en BD --- //
        $resultado = $ponente->guardar();

        if ($resultado) {
          header('Location: /admin/ponentes');
        }
      }
    }

    $redes = json_decode($ponente->redes);

    $router->render('admin/ponentes/crear', [
      'titulo' => 'Registrar Ponente',
      'alertas' => $alertas,
      'ponente' => $ponente,
      'redes' => $redes
    ]);
  }

  public static function editar(Router $router) {

    // --- Proteger GET --- //
    if (!is_admin()) {
      header('Location: /login');
    }
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if (!$id) header('Location: /admin/ponentes');

    $alertas = [];
    // --- Obtener ponente del ID --- //
    $ponente = Ponente::where('id', $id);

    if (!$ponente) header('Location: /admin/ponentes');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // --- Proteger POST --- //
      if (!is_admin()) {
        header('Location: /login');
      }

      if (!empty($_FILES['imagen']['tmp_name'])) {
        // --- Crear nombre para archivo --- //
        $nombre_imagen = md5(uniqid(strval(rand()), true));

        // --- Inicializar Intervention --- //
        $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
        $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

        // --- Agrega al post para pasar la validacion --- //
        $_POST['imagen'] = $nombre_imagen;
      }
      // --- Sincronizar --- //
      $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

      $ponente->sincronizar($_POST);
      $alertas = $ponente->validar();

      if (empty($alertas)) {
        if (isset($nombre_imagen)) {
          $imagen_png->save(URL_SPEAKERS . $nombre_imagen . '.png', 80, 'png');
          $imagen_webp->save(URL_SPEAKERS . $nombre_imagen . '.webp', 80, 'webp');
        }

        $resultado = $ponente->guardar();

        if ($resultado) {
          header('Location: /admin/ponentes');
        }
      }
    }

    $redes = json_decode($ponente->redes);
    $router->render('/admin/ponentes/editar', [
      'titulo' => 'Editar Ponente',
      'alertas' => $alertas,
      'ponente' => $ponente ?? null,
      'redes' => $redes
    ]);
  }

  public static function eliminar() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // --- Proteger POST --- //
      if (!is_admin()) {
        header('Location: /login');
      }

      $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
      if ($_SERVER["HTTP_ORIGIN"] !== 'http://localhost:3000' || !$id) {
        header('Location: /admin/ponentes');
      }

      $ponente = Ponente::find($id);

      if (!isset($ponente)) {
        header('Location: /admin/ponentes');
      }

      $resultado = $ponente->eliminar();

      if ($resultado) {
        header('Location: /admin/ponentes');
      }
    }
  }
}
