<main class="devwebcamp">
  <h2 class="devwebcamp__heading"><?php echo $titulo; ?></h2>
  <p class="devwebcamp__descripcion">Conoce la conferencia más importante de Latinoamérica</p>

  <div class="devwebcamp__grid" data-aos=<?php echo aos_animacion() ?>>
    <div class="devwebcamp__imagen" >
      <picture>
        <source srcset="build/img/sobre_devwebcamp.avif" type="image/avif">
        <source srcset="build/img/sobre_devwebcamp.webp" type="image/webp">
        <img loading="lazy" width="200" height="300" src="build/img/sobre_devwebcamp.jpg" alt="imagen-heading" data-aos=<?php echo aos_animacion() ?>>
      </picture>
    </div>

    <div class="debwebcamp__contenido">
      <p class="devwebcamp__texto">Fusce semper tincidunt eros, ut auctor sapien pharetra in. Curabitur tempor erat eros, sit amet sagittis magna lobortis sit amet. In vestibulum quam non diam maximus efficitur. Donec venenatis velit ac gravida pulvinar.</p>

      <p class="devwebcamp__texto">Aliquam erat volutpat. Fusce semper lacus id dui commodo placerat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis in lobortis dui. Vestibulum at dui sit amet magna tempus finibus ut id mauris. Donec lacinia, libero sed lobortis ultricies, enim magna commodo nisl, ut vestibulum orci lectus in nisl.</p>
    </div>
  </div>
</main>







