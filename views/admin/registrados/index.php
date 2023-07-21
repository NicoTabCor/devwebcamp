<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
  <a class="dashboard__boton" href="/admin/ponentes/crear">
    <i class="fa-solid fa-circle-plus"></i>
    Añadir Ponente
  </a>
</div>

<div class="dashboard__contenedor">
  <?php if (!empty($registros)) { ?>
    <table class="tabla">
      <thead class="tabla__thead">
        <tr>
          <th scope="col" class="table__th">Nombre</th>
          <th scope="col" class="table__th">Email</th>
          <th scope="col" class="table__th">Plan</th>

        </tr>
      </thead>

      <tbody class="tabla__tbody">
        <?php foreach ($registros as $registro) : ?>
          <tr class="tabla__tr">
            <td class="tabla__td">
              <?php echo $registro->usuario->nombre . " " . $registro->usuario->apellido; ?>
            </td>

            <td class="tabla__td">
              <?php echo $registro->usuario->email; ?>
            </td>

            <td class="tabla__td">
            <?php echo $registro->paquete->nombre; ?>
            </td>
          </tr>

        <?php endforeach; ?>
      </tbody>
    </table>
  <?php } else { ?>
    <p class="text-center">No hay Registros Aún</p>
  <?php } ?>
</div>

<?php
echo $paginacion;
?>