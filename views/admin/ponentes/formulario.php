<fieldset class="formulario__fieldset">
  <legend class="formulario__legend">Información Personal</legend>
  <div class="formulario__campo">
    <label for="nombre" class="formulario__label">Nombre</label>
    <input
      id="nombre" 
      name="nombre"
      type="text"
      class="formulario__input"
      placeholder="Nombre Ponente"
      value="<?php echo $ponente->nombre ?? ''; ?>"
    >
  </div>

  <div class="formulario__campo">
    <label for="apellido" class="formulario__label">Apellido</label>
    <input
      id="apellido" 
      name="apellido"
      type="text"
      class="formulario__input"
      placeholder="Apellido Ponente"
      value="<?php echo $ponente->apellido ?? ''; ?>"
    >
  </div>

  <div class="formulario__campo">
    <label for="ciudad" class="formulario__label">Ciudad</label>
    <input
      id="ciudad" 
      name="ciudad"
      type="text"
      class="formulario__input"
      placeholder="Tu Ciudad"
      value="<?php echo $ponente->ciudad ?? ''; ?>"
    >
  </div>

  <div class="formulario__campo">
    <label for="pais" class="formulario__label">País</label>
    <input
      id="pais" 
      name="pais"
      type="text"
      class="formulario__input"
      placeholder="Tu País"
      value="<?php echo $ponente->pais ?? ''; ?>"
    >
  </div>

  <div class="formulario__campo">
    <label for="imagen" class="formulario__label">Imagen</label>
    <div class="formulario__imagenes">
      <?php if($ponente->imagen) { ?>
        
        <div class="formulario__imagen">
          <p class="formulario__imagen-texto">Imagen Actual</p>
          <picture class="formulario__carga">
            <source 
              srcset="<?php echo '/imagenes/speakers/' . $ponente->imagen ;?>.webp" 
              type="image/webp" 
            >
            <source 
              srcset="<?php echo '/imagenes/speakers/' . $ponente->imagen ;?>.png" 
              type="image/png" 
            >
            <img
              class="formulario__carga"
              src="<?php echo '/imagenes/speakers/' . $ponente->imagen . '.png' ;?>" alt="imagen_ponente"
              type="image/png"
            >
          </picture>
        </div>
      <?php } ?>
    </div> 
    <input
      id="imagen" 
      name="imagen"
      type="file"
      class="formulario__input formulario__input--imagen"
      accept="image/*"
    >
  </div>
</fieldset>

<fieldset class="formulario__fieldset">
  <legend class="formulario__legend">
    Información Extra
  </legend>

  <div class="formulario__campo">
    <label for="tags_input" class="formulario__label">Áreas de Experiencia( separados por como)</label>
    <input
      id="tags_input" 
      type="text"
      class="formulario__input formulario__input--areas"
      placeholder="Ej. Node.js, PHP, CSS, Laravel, UX/UI"
    >

    <ul id="tags" class="formulario__listado"></ul>
    <input type="hidden" name="tags" value="<?php echo $ponente->tags ?? ''; ?>">
  </div>

</fieldset>

<fieldset class="formulario__fieldset">
  <legend class="formulario__legend">Redes Sociales</legend>

  <div class="formulario__campo">
    <div class="formulario__contenedor-icono">
      <div class="formulario__icono">
        <i class="fa-brands fa-facebook"></i>
      </div>
      <input
        name="redes[facebook]"
        placeholder="Facebook"
        type="text"
        class="formulario__input--redes"
        value="<?php echo $redes->facebook ?? ''; ?>"
      >
    </div>
  </div>

  <div class="formulario__campo">
    <div class="formulario__contenedor-icono">
      <div class="formulario__icono">
        <i class="fa-brands fa-twitter"></i>
      </div>
      <input
        name="redes[twitter]"
        placeholder="Twitter"
        type="text"
        class="formulario__input--redes"
        value="<?php echo $redes->twitter ?? ''; ?>"
      >
    </div>
  </div>

  <div class="formulario__campo">
    <div class="formulario__contenedor-icono">
      <div class="formulario__icono">
        <i class="fa-brands fa-youtube"></i>
      </div>
      <input
        name="redes[youtube]"
        placeholder="Youtube"
        type="text"
        class="formulario__input--redes"
        value="<?php echo $redes->youtube ?? ''; ?>"
      >
    </div>
  </div>

  <div class="formulario__campo">
    <div class="formulario__contenedor-icono">
      <div class="formulario__icono">
        <i class="fa-brands fa-instagram"></i>
      </div>
      <input
        name="redes[instagram]"
        placeholder="Instagram"
        type="text"
        class="formulario__input--redes"
        value="<?php echo $redes->instagram ?? ''; ?>"
      >
    </div>
  </div>

  <div class="formulario__campo">
    <div class="formulario__contenedor-icono">
      <div class="formulario__icono">
        <i class="fa-brands fa-tiktok"></i>
      </div>
      <input
        name="redes[tiktok]"
        placeholder="Tiktok"
        type="text"
        class="formulario__input--redes"
        value="<?php echo $redes->tiktok ?? ''; ?>"
      >
    </div>
  </div>

  <div class="formulario__campo">
    <div class="formulario__contenedor-icono">
      <div class="formulario__icono">
        <i class="fa-brands fa-github"></i>
      </div>
      <input
        name="redes[github]"
        placeholder="GitHub"
        type="text"
        class="formulario__input--redes"
        value="<?php echo $redes->github ?? ''; ?>"
      >
    </div>
  </div>
</fieldset>

