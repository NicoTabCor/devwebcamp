<main class="agenda">
  <h2 class="agenda__heading">Workshops & Conferencias</h2>
  <p class="agenda__descripcion">Talleres y Conferencias dictados por expertos en Desarrollo Web</p>

  <div class="eventos">
    <h3 class="eventos__heading">&lt;Conferencias /></h3>

    <p class="eventos__fecha">Viernes 5 de Octubre</p>

    <div class="eventos__listado slider swiper" data-aos="flip-up">
      <div class="swiper-wrapper">
        <?php foreach ($eventos['conferencias_v'] as $evento) {
          include __DIR__ . '/../templates/evento.php';
        }; ?>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div> <!-- Sliders Viernes -->

    <p class="eventos__fecha">Sabado 6 de Octubre</p>

    <div class="eventos__listado slider swiper" data-aos="flip-up">
      <div class="swiper-wrapper">
        <?php foreach ($eventos['conferencias_s'] as $evento) {
          include __DIR__ . '/../templates/evento.php';
        }; ?>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div> <!-- Sliders Sabado -->
  </div> <!-- Conferencias -->

  <div class="eventos eventos--workshops">
    <h3 class="eventos__heading">&lt;Workshops /></h3>

    <p class="eventos__fecha">Viernes 5 de Octubre</p>

    <div class="eventos__listado slider swiper" data-aos="flip-up">
      <div class="swiper-wrapper">
        <?php foreach ($eventos['workshops_v'] as $evento) {
          include __DIR__ . '/../templates/evento.php';
        }; ?>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div> <!-- Sliders Viernes -->

    <p class="eventos__fecha">Sabado 6 de Octubre</p>

    <div class="eventos__listado slider swiper" data-aos="flip-up">
      <div class="swiper-wrapper">
        <?php foreach ($eventos['workshops_s'] as $evento) {
          include __DIR__ . '/../templates/evento.php';
        }; ?>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div> <!-- Sliders Sabado -->

    </div>
  </div> <!-- Workshops -->


</main>