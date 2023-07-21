<?php

use Classes\Paginacion;

function debuguear($variable): string {
  echo "<pre>";
  var_dump($variable);
  echo "</pre>";
  exit;
}
function s($html): string {
  $s = htmlspecialchars($html);
  return $s;
}

function getIP(): string {
  if (isset($_SERVER['HTTP_CLIENT_IP']) && array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];

  } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ips = array_map('trim', $ips);
    $ip = $ips[0];
    
  } else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
  }

  $ip = filter_var($ip, FILTER_VALIDATE_IP);
  $ip = ($ip === false) ? '0.0.0.0' : $ip;

  return $ip;
}

function pagina_actual(string $path): bool {
  return str_contains($_SERVER['PATH_INFO'] ?? '/', $path) ? true : false;
}

function is_auth(): bool {
  if (!isset($_SESSION)) {
    session_start();
  }

  return isset($_SESSION['id']) && !empty($_SESSION);
}

function is_admin(): bool {
  if (!isset($_SESSION)) {
    session_start();
  }

  return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

function redireccionar($admin): void {
  if ($admin === '0') {
    header('Location: /finalizar-registro');
  } elseif ($admin === '1') {
    header('Location: /admin/ponentes');
  }
}

function mensaje(string $tipo): string {

  switch ($tipo) {
    case '1':
      $mensaje = 'Ponente Registrado Correctamente';
      return $mensaje;
      break;

    case '2':
      $mensaje = 'Ponente Actualizado Correctamente';
      return $mensaje;
      break;

    case '3':
      $mensaje = 'Ponente Eliminado Correctamente';
      return $mensaje;
      break;
  }
}

function aos_animacion(): string {
  $efectos = [
    'fade',
    'fade-up',
    'fade-down',
    'fade-left',
    'fade-right',
    'fade-up-right',
    'fade-up-left',
    'fade-down-right',
    'fade-down-left',
    'zoom-in',
    'zoom-in-up',
    'zoom-in-down',
    'zoom-in-left',
    'zoom-in-right',
    'zoom-out',
    'zoom-out-up',
    'zoom-out-down',
    'zoom-out-left',
    'zoom-out-right'
  ];

  return $efectos[array_rand($efectos)];
}
