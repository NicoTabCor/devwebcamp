<fieldset class="formulario__fieldset">
  <legend class="formulario__legend">Información Evento</legend>

  <div class="formulario__campo">
    <label for="nombre" class="formulario__label">Nombre Evento</label>
    <input id="nombre" name="nombre" type="text" class="formulario__input" placeholder="Nombre Evento" value="<?php echo $evento->nombre; ?>">
  </div>

  <div class="formulario__campo">
    <label for="descripcion" class="formulario__label">Descripción Evento ( Se eliminarion espacios iniciales y finales )</label>
    <textarea rows="5" id="descripcion" name="descripcion" class="formulario__input formulario__textarea" placeholder="Descripción Evento"><?php echo $evento->descripcion; ?></textarea>
    <div class="formulario__contador"></div>
  </div>

  <div class="formulario__campo">
    <label for="categorias" class="formulario__label">Categoría o Tipo de Evento</label>
    <select class="formulario__select" id="categoria" name="categoria_id">
      <option value="" selected disabled>
        --- Seleccionar ---
      </option>

      <?php foreach ($categorias as $categoria) { ?>
        <option <?php
                echo ($evento->categoria_id == $categoria->id) ? 'selected' : '';
                ?> value="<?php echo $categoria->id; ?>">
          <?php echo $categoria->nombre; ?>
        </option>
      <?php } ?>

    </select>
  </div>

  <div class="formulario__campo">
    <label for="categorias" class="formulario__label">Selecciona el día</label>

    <div class="formulario__radio">
      <?php foreach ($dias as $dia) { ?>
        <div class="formulario__dia">
          <label for="<?php echo strtolower($dia->nombre); ?>">
            <?php echo $dia->nombre; ?>
          </label>
          <input type="radio" id="<?php echo strtolower($dia->nombre); ?>" name="dia" value="<?php echo $dia->id; ?>" <?php echo ($dia->id === $evento->dia_id) ? 'checked' : ''; ?>>
        </div>

      <?php } ?>
    </div>

    <input type="hidden" name="dia_id" value="<?php echo $evento->dia_id; ?>">
  </div>

  <div id="horas" class="formulario__campo">
    <label class="formulario__label" for="">Seleccionar un Horario Disponible</label>

    <ul class="horas">
      <?php foreach ($horas as $hora) { ?>

        <li class="horas__hora horas__hora--disallowed" data-hora-id="<?php echo $hora->id; ?>">
          <?php echo $hora->hora; ?>
        </li>

      <?php } ?>
    </ul>

    <input type="hidden" name="hora_id" value="<?php echo $evento->hora_id ?>">
  </div>
</fieldset>

<fieldset class="formulario__fieldset">
  <legend class="formulario__legend">Información Extra</legend>
  <div class="formulario__campo">
    <label for="ponente" class="formulario__label">Ponente</label>
    <input id="ponente" type="text" class="formulario__input" placeholder="Buscar Ponente" value="">

    <div class="formulario__imagenes">
      <?php if (isset($evento->ponente->imagen)) { ?>

        <div class="formulario__imagen">
          <p class="formulario__imagen-texto">Ponente Actual</p>
          <picture class="formulario__carga">
            <source srcset="<?php echo '/imagenes/speakers/' . $evento->ponente->imagen; ?>.webp" type="image/webp">
            <source srcset="<?php echo '/imagenes/speakers/' . $evento->ponente->imagen; ?>.png" type="image/png">
            <img class="formulario__carga" src="<?php echo '/imagenes/speakers/' . $evento->ponente->imagen . '.png'; ?>" alt="imagen_ponente" type="image/png">
          </picture>
        </div>
      <?php } ?>
    </div>

    <ul id="listado-ponentes" class="listado-ponentes"></ul>

    <input type="hidden" name="ponente_id" value="<?php echo $evento->ponente_id; ?>">
  </div>

  <div class="formulario__campo">
    <label for="disponibles" class="formulario__label">Lugares Disponibles</label>
    <input min="1" id="disponibles" name="disponibles" type="number" class="formulario__input" placeholder="Ej: 20" value="<?php echo $evento->disponibles; ?>">
  </div>

</fieldset>