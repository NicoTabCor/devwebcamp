<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
  <a class="dashboard__boton" href="/admin/eventos/crear">
    <i class="fa-solid fa-circle-plus"></i>
    Añadir Evento
  </a>
</div>

<div class="dashboard__contenedor">
  <?php if (!empty($eventos)) { ?>
    <table class="tabla">
      <thead class="tabla__thead">
        <tr>
          <th scope="col" class="table__th">Evento</th>
          <th scope="col" class="table__th">Categoría</th>
          <th scope="col" class="table__th">Día y Hora</th>
          <th scope="col" class="table__th">Ponente</th>
          <th scope="col" class="table__th"></th>
        </tr>
      </thead>

      <tbody class="tabla__tbody">
        <?php foreach ($eventos as $evento) : ?>
          <tr class="tabla__tr">
            <td class="tabla__td">
              <?php echo $evento->nombre; ?>
            </td>

            <td class="tabla__td">
              <?php echo $evento->categoria->nombre; ?>
            </td>

            <td class="tabla__td">
              <?php echo $evento->dia->nombre . ", " . $evento->hora->hora; ?>
            </td>

            <td class="tabla__td">
              <?php echo $evento->ponente->nombre; ?>
            </td>

            <td class="tabla__td tabla__td--acciones ">
              <div class="tabla__acciones-contenedor">
                <a class="tabla__accion tabla__accion--editar" href="/admin/eventos/editar?id=<?php echo $evento->id; ?>">
                  <i class="fa-solid fa-pencil"></i>
                  Editar
                </a>

                <form method="POST" action="/admin/eventos/eliminar">
                  <input name="id" type="hidden" value="<?php echo $evento->id; ?>">
                  <button class="tabla__accion tabla__accion--eliminar" type="submit">
                    <i class="fa-solid fa-circle-xmark"></i>
                    Eliminar
                  </button>
                </form>
              </div>
            </td>

          </tr>

        <?php endforeach; ?>
      </tbody>
    </table>
  <?php } else { ?>
    <p class="text-center">No hay Eventos Aún</p>
  <?php } ?>
</div>

<?php
echo $paginacion;
?>