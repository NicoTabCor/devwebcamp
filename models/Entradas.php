<?php

namespace Model;

class Entradas extends ActiveRecord {

  protected static $tabla = 'entradas';
  protected static $columnasDB = ['id', 'ip', 'entrada'];

  public $id;
  public $ip;
  public $entrada;

  public function __construct($args = []) {
    $this->id = $args['id'] ?? null;
    $this->ip = $args['ip'] ?? '';
    $this->entrada = $args['entrada'] ?? '';
  }

  public static function conteo($ip) {

    $query = "SELECT COUNT(*) AS total_conteo FROM " . static::$tabla . " WHERE ip = '$ip';";
    $query .= "SELECT MAX(entrada) AS ultimo FROM entradas WHERE ip = '$ip';";

    self::$db->multi_query($query);
    $devolver = [];
    do {
      $resultado = self::$db->store_result();
      $arr = $resultado->fetch_assoc();
      $keys = array_keys($arr);
      $primerKey = array_shift($keys);

      $devolver[$primerKey] = array_shift($arr);
    } while (self::$db->more_results() && self::$db->next_result());

    return $devolver;
  }

  public static function resetear($ip) {
    $query = "DELETE FROM " . static::$tabla . " WHERE ip = '" . $ip . "'";
    $resultado = self::$db->query($query);
    return $resultado;
  }
}
