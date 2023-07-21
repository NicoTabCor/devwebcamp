<?php

declare(strict_types=1);

namespace Classes;

class Paginacion {

  public $pagina_actual;
  public $registros_por_pagina;
  public $total_registros;
  public $limite_paginas;

  public function __construct(int $pagina_actual = 1, int $registros_por_pagina = 10, int $total_registros = 0, ?int $limite_paginas = null) {

    $this->pagina_actual = (int) $pagina_actual;
    $this->registros_por_pagina = (int) $registros_por_pagina;
    $this->total_registros = (int) $total_registros;
    $this->limite_paginas = (int) $limite_paginas;
  }

  public function offset(): int {
    return ($this->pagina_actual - 1) *  $this->registros_por_pagina;
  }

  public function total_paginas(): float {
    return ceil($this->total_registros / $this->registros_por_pagina);
  }

  public function pagina_anterior() {
    $anterior = $this->pagina_actual - 1;

    return ($anterior > 0) ? $anterior : false;
  }

  public function pagina_siguiente() {
    $siguiente = $this->pagina_actual + 1;

    return ($siguiente <= $this->total_paginas()) ? $siguiente : false;
  }

  public function enlace_anterior() {
    $visibilidad = '';

    if (!$this->pagina_anterior()) {
      $visibilidad = "style=\"visibility: hidden\"";
    }
    $html = "<a $visibilidad data-id=\"anterior\" href=\"?page={$this->pagina_anterior()}\" class=\"paginacion__enlace paginacion__enlace--texto\">&laquo Previo</a>";
    return $html;
  }

  public function enlace_siguiente() {
    $visibilidad = '';
    if (!$this->pagina_siguiente()) {
      $visibilidad = "style=\"visibility: hidden\"";
    }

    $html = "<a $visibilidad data-id=\"siguiente\" href=\"?page={$this->pagina_siguiente()}\" class=\"paginacion__enlace paginacion__enlace--texto\">Siguiente &raquo</a>";

    return $html;
  }

  public function enlaces_numeros() {
    $html = '';
    $total_paginas = $this->total_paginas();
    
    $distancia = intval(floor($this->limite_paginas / 2));
    if ($total_paginas > 2) {
      $html .= "<div class=\"paginacion__paginas\">";

      $numeros_paginacion = min($this->limite_paginas, $this->total_paginas());

      for ($pagina = 0; $pagina < $numeros_paginacion; $pagina++) {

        $pagina_real = $this->pagina_actual - $distancia + $pagina;

        if($this->limite_paginas !== 2) {
          if($total_paginas - $this->pagina_actual < $distancia) {
            $numero_medio = $total_paginas - $distancia;
            $distancia_actual = $this->pagina_actual - $numero_medio;
            $pagina_real -= $distancia_actual;
          }
        }
        

        if ($pagina_real == 0 || $pagina_real < 0) {
          $numeros_paginacion += 1;
          continue;
        }
        
        if ($this->pagina_actual == $pagina_real) {
          $html .= "<span class=\"paginacion__pagina paginacion__pagina--marcado\">$pagina_real</span>";
        } else {
          $html .= "<a href=\"?page=$pagina_real\" class=\"paginacion__pagina paginacion__pagina--numero \">$pagina_real</a>";
        }
      }

      $html .= "</div>";
    }

    return $html;
  }

  public function buscador() {
    $html = '';

    if ($this->total_paginas() > $this->limite_paginas) {
      $html .= "<form class=\"buscador\" method=\"POST\">";
      $html .= "<div class=\"buscador__contenedor-buscador\">";
      $html .= "<label for=\"buscador\" class=\"buscador__label\">Seleccionar Página</label>";
      $html .= "<div class=\"buscador__search\">";
      $html .= "<input name=\"pagina\" id=\"buscador\" type=\"number\" placeholder=\"Maximo de páginas es: " . $this->total_paginas() . "\" class=\"buscador__input buscador__input--search\">";
      $html .= "<input type=\"submit\" class=\"buscador__submit buscador__submit--buscador\" value=\"Ir\">";
      $html .= "</div>";
      $html .= "</div>";
      $html .= "</form>";
    }

    return $html;
  }

  public function paginacion() {
    $html = '';
    if ($this->total_paginas() > 1) {
      $html .= "<div class=\"paginacion\">";
      $html .= $this->enlace_anterior();
      $html .= $this->enlaces_numeros();
      $html .= $this->enlace_siguiente();
      $html .= "</div>";
      $html .= $this->buscador();
    }

    return $html;
  }
}
