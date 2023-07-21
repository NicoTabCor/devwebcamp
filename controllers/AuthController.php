<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;
use Model\Entradas;

class AuthController {
  public static function login(Router $router) {

    $alertas = [];
    $limite = 3;
    $adicional =  60 * 60;
    $mensaje_limite = "Limite de intentos alcanzado, intentelo en";
    $usuario = new Usuario();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = new Usuario($_POST);
      $ip = getIP();
      $datos_entrada = [
        'ip' => $ip,
        'entrada' => time()
      ];

      $alertas = $usuario->validarLogin();

      if (empty($alertas)) {
        // Verificar quel el usuario exista
        $usuario = Usuario::where('email', $usuario->email);

        // --- Revisar cantidad de intentos realizados por el mismo IP --- //
        $conteo = Entradas::conteo($ip);
        $total_conteo = intval($conteo['total_conteo']);
        $ultimo = intval($conteo['ultimo']);

        // --- Genener tiempo calculado a partir del ultimo intento registrado antes de alcanzar el limite --- //
        $tiempo_maximo = $ultimo + $adicional;
        if ($tiempo_maximo < time()) {
          // --- Borrar todos los intentos y reiniciar contador si se llego al tiempo maximo --- //
          Entradas::resetear($ip);
          $total_conteo = 0;
        }

        // --- Generar hora donde se liberara el bloqueo --- //
        if ($total_conteo > $limite) {
          $expiracion = date('H:i:s', intval($tiempo_maximo));
        }

        // --- Permitir validacion sino hay limite de intentos --- //
        if (!isset($expiracion)) {
          if (!$usuario || !$usuario->confirmado) {
            Usuario::setAlerta('error', 'Usuario no existe o no esta confirmado');

            if (!$usuario->confirmado) {
              // --- Generar una entrada en la base de datos si se encuentra un error --- //
              $entrada = new Entradas($datos_entrada);
              $entrada->guardar();
              $conteo = Entradas::conteo($ip);
              $total_conteo = intval($conteo['total_conteo']);
              $ultimo = intval($conteo['ultimo']);

              // --- Verificar numero de intentos --- //
              if ($total_conteo > $limite) {
                $tiempo_maximo = $ultimo + $adicional;
                $expiracion = date('H:i:s', intval($tiempo_maximo));

                Usuario::setAlerta('error', $mensaje_limite . " " . $expiracion);
              }
            }
          } else {
            // El Usuario existe
            if (password_verify($_POST['password'], $usuario->password)) {
              session_start();
              // Iniciar la sesión
              $_SESSION['id'] = $usuario->id;
              $_SESSION['nombre'] = $usuario->nombre;
              $_SESSION['apellido'] = $usuario->apellido;
              $_SESSION['email'] = $usuario->email;
              $_SESSION['admin'] = $usuario->admin ?? null;
              
              if ($usuario->admin) {
                header('Location: /admin/dashboard');
              } else {
                header('Location: /finalizar-registro');
              }
            } else {
              Usuario::setAlerta('error', 'Password Incorrecto');

              // --- Generar una entrada en la base de datos si se encuentra un error --- //
              $entrada = new Entradas($datos_entrada);
              $entrada->guardar();
              $conteo = Entradas::conteo($ip);
              $total_conteo = intval($conteo['total_conteo']);
              $ultimo = intval($conteo['ultimo']);

              // --- Verificar numero de intentos --- //
              if ($total_conteo > $limite) {
                $tiempo_maximo = $ultimo + $adicional;
                $expiracion = date('H:i:s', intval($tiempo_maximo));

                Usuario::setAlerta('error', $mensaje_limite . " " . $expiracion);
              }
            }
          }
        } else {
          Usuario::setAlerta('error', $mensaje_limite . " " . $expiracion);
        }
      }
    }

    $alertas = Usuario::getAlertas();

    // Render a la vista 
    $router->render('auth/login', [
      'titulo' => 'Iniciar Sesión',
      'alertas' => $alertas,
      'restante' => $restante ?? '',
      'usuario' => $usuario
    ]);
  }

  public static function logout() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      session_start();
      $_SESSION = [];
      header('Location: /login');
    }
  }

  public static function registro(Router $router) {
    $alertas = [];
    $usuario = new Usuario;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $usuario->sincronizar($_POST);

      $alertas = $usuario->validar_cuenta();

      if (empty($alertas)) {

        // --- Agregar Recaptcha --- //
        $ip = getIP();
        $captcha = $_POST['g-recaptcha-response'];
        $secretkey = "6LeGqtQjAAAAAP2MUOxwej8lE-xOVQsYaylNV0ox
        ";

        $postdata = http_build_query(
          array(
            'secret' => $secretkey,
            'response' => $captcha,
            'remoteip' => $ip
          )
        );

        $opts = array(
          'http' =>
          array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
          )
        );

        $context  = stream_context_create($opts);
        $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, $context);
        $atributos = json_decode($respuesta, true);

        $existeUsuario = Usuario::where('email', $usuario->email);

        if ($existeUsuario) {
          Usuario::setAlerta('error', 'El Usuario ya esta registrado');
        } elseif ($atributos['success'] === false) {
          Usuario::setAlerta('error', 'Error de Captcha');
        } else {
          // Hashear el password
          $usuario->hashPassword();

          // Eliminar password2
          unset($usuario->password2);

          // Generar el Token
          $usuario->crearToken();
          // Crear un nuevo usuario
          $resultado =  $usuario->guardar();
          // Enviar email
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarConfirmacion();

          $mensaje = 'Hemos enviado instrucciones a tu email para que puedas utilizar y activar tu cuenta DevWebCamp';
          $titulo = 'Cuenta Creada Correctamente';

          session_start();
          $_SESSION['mensaje'] = $mensaje;
          $_SESSION['titulo'] = $titulo;

          if ($resultado) {
            header('Location: /mensaje');
          }
        }
      }
    }

    $alertas = Usuario::getAlertas();
    // Render a la vista
    $router->render('auth/registro', [
      'titulo' => 'Crea tu cuenta en DevWebcamp',
      'usuario' => $usuario,
      'alertas' => $alertas
    ]);
  }

  public static function olvide(Router $router) {
    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = new Usuario($_POST);
      $alertas = $usuario->validarEmail();

      if (empty($alertas)) {
        // Buscar el usuario
        $usuario = Usuario::where('email', $usuario->email);

        if ($usuario && $usuario->confirmado) {

          // Generar un nuevo token
          $usuario->crearToken();
          unset($usuario->password2);

          // Actualizar el usuario
          $usuario->guardar();

          // Enviar el email
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarInstrucciones();


          // Imprimir la alerta
          // Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

          $alertas['exito'][] = 'Hemos enviado las instrucciones a tu email';
        } else {

          // Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');

          $alertas['error'][] = 'El Usuario no existe o no esta confirmado';
        }
      }
    }

    // Muestra la vista
    $router->render('auth/olvide', [
      'titulo' => 'Olvide mi Password',
      'alertas' => $alertas
    ]);
  }

  public static function restablecer(Router $router) {

    $token = s($_GET['token']);

    $token_valido = true;
    if (!$token) header('Location: /login');

    // Identificar el usuario con este token
    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
      Usuario::setAlerta('error', 'Token No Válido, intenta de nuevo');
      $token_valido = false;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Añadir el nuevo password
      $usuario->sincronizar($_POST);

      // Validar el password
      $alertas = $usuario->validarPassword();

      if (empty($alertas)) {
        // Hashear el nuevo password
        $usuario->hashPassword();

        // Eliminar el Token
        $usuario->token = null;

        // --- Eliminar campo de password repetido --- //
        unset($usuario->password2);

        // Guardar el usuario en la BD
        $resultado = $usuario->guardar();

        // Redireccionar
        if ($resultado) {

          session_start();
          $_SESSION['titulo'] = 'Reestablecer Password';
          $_SESSION['mensaje'] = 'Password Restablecido Correctamente';
          header("Location: /mensaje");
        }
      }
    }

    $alertas = Usuario::getAlertas();

    // Muestra la vista
    $router->render('auth/restablecer', [
      'titulo' => 'Restablecer Password',
      'alertas' => $alertas,
      'token_valido' => $token_valido
    ]);
  }

  public static function mensaje(Router $router) {

    session_start();

    $mensaje = $_SESSION['mensaje'];
    $titulo = $_SESSION['titulo'];

    if (!$mensaje) header("Location: /login");

    if (!isset($_SESSION['id'])) {
      session_abort();
    } else {
      unset($_SESSION['mensaje']);
    }

    $router->render('auth/mensaje', [
      'titulo' => $titulo,
      'mensaje' => $mensaje
    ]);
  }

  public static function confirmar(Router $router) {

    $token = s($_GET['token']);

    if (!$token) header('Location: /login');

    // Encontrar al usuario con este token
    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
      // No se encontró un usuario con ese token
      Usuario::setAlerta('error', 'Token No Válido, la cuenta no se ha confirmado');
    } else {
      // Confirmar la cuenta
      $usuario->confirmado = 1;
      $usuario->token = '';
      unset($usuario->password2);

      // Guardar en la BD
      $usuario->guardar();

      Usuario::setAlerta('exito', 'Cuenta Comprobada Exitosamente');
    }

    $router->render('auth/confirmar', [
      'titulo' => 'Confirma tu cuenta DevWebcamp',
      'alertas' => Usuario::getAlertas()
    ]);
  }
}
