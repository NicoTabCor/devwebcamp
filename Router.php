<?php
namespace MVC;

class Router {
  public array $getRoutes = [];
  public array $postRoutes = [];

  public function get($url, $fn) {
    $this->getRoutes[$url] = $fn;
  }

  public function post($url, $fn) {
    $this->postRoutes[$url] = $fn;
  }

  public function comprobarRutas() {

    $url_actual = explode( '?', $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI']);
    $url_actual = $url_actual[0];

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
      $fn = $this->getRoutes[$url_actual] ?? null;
    } else {
      $fn = $this->postRoutes[$url_actual] ?? null;
    }

    if ($fn) {
      call_user_func($fn, $this);
    } else {
      header('Location: /404');
    }
  }

  public function render($view, $datos = []) {
    foreach ($datos as $key => $value) {
      $$key = $value;
    }

    ob_start();

    include_once __DIR__ . "/views/$view.php";

    $contenido = ob_get_clean(); // Limpia el Buffer
    
    $admin = str_contains($_SERVER['REQUEST_URI'] ?? '/', 'admin');

    if ($admin) {
      include_once __DIR__ . '/views/admin-layout.php';
    } else {
      $auth = is_auth();
      $admin = is_admin();
      include_once __DIR__ . '/views/layout.php';
    }
  }
}
