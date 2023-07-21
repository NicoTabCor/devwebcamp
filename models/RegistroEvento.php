<?php

namespace Model;

class RegistroEvento extends ActiveRecord {
  protected static $tabla = 'registro_eventos';
  protected static $columnasDB = ['id', 'registro_id', 'evento_id'];

  public $id;
  public $registro_id;
  public $evento_id;

  public function __construct($args = []) {
    $this->id = $args['id'] ?? null;
    $this->registro_id = $args['registro_id'] ?? '';
    $this->evento_id = $args['evento_id'] ?? '';
  }
}