<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;

class PaginasController {
  public static function index(Router $router) {

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

    // --- OBTENER EL TOTAL DE CADA BLOQUE --- //
    $ponentes_total = Ponente::total();
    $conferencias_total = Evento::total('categoria_id', 1);
    $workshops_total = Evento::total('categoria_id', 2);

    // --- OBTENER TODOS LOS PONENTES --- //
    $ponentes = Ponente::all();

    // --- OBTENER EFECTO RANDOM --- //

    $router->render('paginas/index', [
      'titulo' => 'Inicio',
      'eventos' => $eventos_formateados,
      'ponentes_total' => $ponentes_total,
      'conferencias_total' => $conferencias_total,
      'workshops_total' => $workshops_total,
      'ponentes' => $ponentes
    ]);
  }

  public static function evento(Router $router) {
    $router->render('paginas/devwebcamp', [
      'titulo' => 'Sobre DevWebCamp'
    ]);
  }

  public static function paquetes(Router $router) {
    $router->render('paginas/paquetes', [
      'titulo' => 'Paquetes DevWebCamp'
    ]);
  }

  public static function conferencias(Router $router) {



    // $formateados_test = [];
    // $categorias_totales = Categoria::total();

    // // --- ITERA EVENTOS --- //
    // foreach ($eventos as $evento) {

    //   // --- ITERA Y GENERA ARRAY POR CADA CATEGORIA --- //
    //   for ($id = 1; $id <= $categorias_totales; $id++) {

    //     $evento_cat = Categoria::find($id);

    //     // --- REVISA SI LA CATEGORIA DE EVENTO CONCUERDA CON UN GRUPO DE CATEGORIA --- //
    //     if ($evento->categoria_id === $evento_cat->id) {
    //       $dias = Dia::total();

    //       // --- ITERA DIAS DISPONIBLES --- //
    //       for ($dia = 1; $dia <= $dias; $dia++) {
    //         $dia_fetch = Dia::find($dia);

    //         // --- REVISA SI EL EVENTO ACTUAL CORRESPONDE CON CIERTO DIA --- //
    //         if ($evento->dia_id === $dia_fetch->id) {
    //           // --- AGRUPA BAJO ESA MISMA CATEGORIA Y DIA LOS EVENTOS --- //
    //           $evento->categoria = $evento_cat;
    //           $evento->dia = $dia_fetch;
    //           $evento->hora = Hora::find($evento->hora_id);
    //           $evento->ponente = Ponente::find($evento->ponente_id);

    //           $formateados_test[$evento_cat->nombre][$dia_fetch->nombre][] = $evento;
    //         }
    //       }
    //     }
    //   }
    // }

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

    $router->render('paginas/conferencias', [
      'titulo' => 'Conferencias & Workshops',
      'eventos' => $eventos_formateados
    ]);
  }

  public static function error(Router $router) {

    $router->render('paginas/404', [
      'titulo' => 'Página no encontrada'
    ]);
  }
}
