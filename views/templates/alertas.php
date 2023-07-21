<?php 
  foreach( $alertas as $key => $tipo_lista ) {
    foreach( $tipo_lista as $alerta ) { 
  ?>
      <div class="alerta alerta__<?php echo $key; ?>">
        <?php echo $alerta; ?>
      </div> 
  <?php 
    }
  }
?>