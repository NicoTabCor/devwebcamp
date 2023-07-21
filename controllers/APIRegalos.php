<?php

namespace Controllers;

use Model\Regalo;
use Model\Registro;

class APIRegalos {

  public static function index() {
    // --- VERIFICAR Y REDIRECCIONAR --- //
    if(!is_admin()) {
      json_encode([]);
      return;
    }

    $regalos = Regalo::all();
    
    $array_label = [];
    
    foreach ($regalos as $regalo ) {
      $regalo->total = Registro::total_array(['regalo_id' => $regalo->id, 'paquete_id' => '1']);
      $array_label[] = $regalo->nombre;
    }
    
    echo json_encode($regalos);
    return;
  }
}
