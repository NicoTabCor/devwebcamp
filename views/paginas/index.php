<?php
include_once __DIR__ . '/conferencias.php';
?>

<section class="resumen">
  <div class="resumen__grid">

    <div class="resumen__bloque" data-aos="fade-right">
      <p class="resumen__texto resumen__texto--numero" data-contador="<?php echo $ponentes_total; ?>">0</p>
      <p class="resumen__texto">Speakers</p>
    </div>

    <div class="resumen__bloque" data-aos="fade-left">
      <p class="resumen__texto resumen__texto--numero" data-contador="<?php echo $conferencias_total; ?>">0</p>
      <p class="resumen__texto">Conferencias</p>
    </div>

    <div class="resumen__bloque" data-aos="fade-right">
      <p class="resumen__texto resumen__texto--numero" data-contador="<?php echo $workshops_total; ?>">0</p>
      <p class="resumen__texto">Workshops</p>
    </div>

    <div class="resumen__bloque" data-aos="fade-left">
      <p class="resumen__texto resumen__texto--numero" data-contador="500">0</p>
      <p class="resumen__texto">Asistentes</p>
    </div>

  </div>
</section>

<section class="speakers">
  <h2 class="speakers__heading">Speakers</h2>
  <p class="speakers__descripcion">Conoce a nuestros expertos de DevWebCamp</p>
  <div class="speakers__grid">
    <?php
    foreach ($ponentes as $ponente) { ?>
      <div class="speaker" data-aos=<?php echo aos_animacion(); ?> data-aos-delay="25" data-aos-duration="650" data-aos-easing="ease-in" data-aos-mirror="true" data-aos-anchor-placement="top-center">

        <picture>
          <source srcset="<?php echo '/imagenes/speakers/' . $ponente->imagen; ?>.webp" type="image/webp">
          <source srcset="<?php echo '/imagenes/speakers/' . $ponente->imagen; ?>.png" type="image/png">
          <img class="speaker__imagen" loading="lazy" src="<?php echo '/imagenes/speakers/' . $ponente->imagen . '.png'; ?>" alt="imagen_evento" type="image/png">
        </picture>

        <p class="speaker__nombre"><?php echo $ponente->nombre . " " . $ponente->apellido; ?></p>
        <p class="speaker__ubicacion"><?php echo $ponente->ciudad . ", " . $ponente->pais; ?></p>

        <!-- REDES -->
        <nav class="speaker-redes">
          <?php
          $redes = json_decode($ponente->redes);
          ?>

          <?php if (!empty($redes->facebook)) { ?>
            <a class="speaker-redes__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->facebook; ?>">
              <span class="speaker-redes__ocultar">Facebook</span>
            </a>
          <?php } ?>

          <?php if (!empty($redes->twitter)) { ?>
            <a class="speaker-redes__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->twitter; ?>">
              <span class="speaker-redes__ocultar">Twitter</span>
            </a>
          <?php } ?>

          <?php if (!empty($redes->youtube)) { ?>
            <a class="speaker-redes__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->youtube; ?>">
              <span class="speaker-redes__ocultar">YouTube</span>
            </a>
          <?php } ?>

          <?php if (!empty($redes->instagram)) { ?>
            <a class="speaker-redes__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->instagram; ?>">
              <span class="speaker-redes__ocultar">Instagram</span>
            </a>
          <?php } ?>

          <?php if (!empty($redes->tiktok)) { ?>
            <a class="speaker-redes__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->tiktok; ?>">
              <span class="speaker-redes__ocultar">Tiktok</span>
            </a>
          <?php } ?>

          <?php if (!empty($redes->github)) { ?>
            <a class="speaker-redes__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->github; ?>">
              <span class="speaker-redes__ocultar">Github</span>
            </a>
          <?php } ?>
        </nav>

        <!-- TAGS -->
        <ul class="speaker__listado"></ul>

        <input type="hidden" name="tags" value="<?php echo $ponente->tags ?? ''; ?>">
      </div>
    <?php } ?>
  </div>
</section>

<div id="mapa" class="mapa"></div>

<section class="boletos">
  <h2 class="boletos__heading">Boletos & Precios</h2>
  <p class="boletos__descripcion">Precios para DevWebCamp</p>

  <div class="boletos__grid">

    <div class="boleto boleto--presencial" data-aos="zoom-in-right">
      <h4 class="boleto__logo">&#60;DevWebCamp/></h4>
      <p class="boleto__plan">Presencial</p>
      <p class="boleto__precio">$199</p>
    </div>

    <div class="boleto boleto--virtual" data-aos="zoom-in-left">
      <h4 class="boleto__logo">&#60;DevWebCamp/></h4>
      <p class="boleto__plan">Virtual</p>
      <p class="boleto__precio">$49</p>
    </div>

    <div class="boleto boleto--gratis" data-aos="zoom-in-right">
      <h4 class="boleto__logo">&#60;DevWebCamp/></h4>
      <p class="boleto__plan">Gratis</p>
      <p class="boleto__precio">$0</p>
    </div>

  </div>

  <div class="boleto__enlace-contenedor">
    <a href="/paquetes" class="boleto__enlace">Ver Paquetes</a>
  </div>
</section>