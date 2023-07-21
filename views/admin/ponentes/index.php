<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
  <a class="dashboard__boton" href="/admin/ponentes/crear">
    <i class="fa-solid fa-circle-plus"></i>
    Añadir Ponente
  </a>
</div>

<div class="dashboard__contenedor">
  <?php if (!empty($ponentes)) { ?>
    <table class="tabla">
      <thead class="tabla__thead">
        <tr>
          <th scope="col" class="table__th">Nombre</th>
          <th scope="col" class="table__th">Ubicacion</th>
          <th scope="col" class="table__th">Imagen</th>
          <th scope="col" class="table__th">Acciones</th>

        </tr>
      </thead>

      <tbody class="tabla__tbody">
        <?php foreach ($ponentes as $ponente) : ?>
          <tr class="tabla__tr">
            <td class="tabla__td">
              <?php echo $ponente->nombre . " " . $ponente->apellido; ?>
            </td>

            <td class="tabla__td">
              <?php echo $ponente->ciudad . ", " . $ponente->pais; ?>
            </td>

            <td class="tabla__td">
              <img class="tabla__imagen" src="/imagenes/speakers/<?php echo $ponente->imagen; ?>.png" alt="Imagen-Ponente">
            </td>

            <td class="tabla__td tabla__td--acciones ">
              <div class="tabla__acciones-contenedor">
                <a class="tabla__accion tabla__accion--editar" href="/admin/ponentes/editar?id=<?php echo $ponente->id; ?>">
                  <i class="fa-solid fa-user-pen"></i>
                  Editar
                </a>

                <form method="POST" action="/admin/ponentes/eliminar">
                  <input name="id" type="hidden" value="<?php echo $ponente->id; ?>">
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
    <p class="text-center">No hay Ponentes Aún</p>
  <?php } ?>
</div>

<?php
echo $paginacion;
?>