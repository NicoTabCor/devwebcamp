<main class="auth">
  <h2 class="auth__heading"><?php echo $titulo; ?></h2>
  <p class="auth__text">Genera tu nuevo password</p>

  <?php 
    require_once __DIR__ . '/../templates/alertas.php';

    
    if ($token_valido) { 
  ?>
      <form method="POST" class="formulario">
        <div class="formulario__campo">
          <label for="email" class="formulario__label">
            Nuevo Password
          </label>
          
          <input 
            type="password"
            class="formulario__input"
            placeholder="Tu Nuevo Password"
            id="password"
            name="password"
          >
        </div>

        <div class="formulario__campo">
          <label for="password2" class="formulario__label">
            Repetir Nuevo Password
          </label>
          
          <input 
            type="password"
            class="formulario__input"
            placeholder="Repite Tu Nuevo Password"
            id="password2"
            name="password2"
          >
        </div>

        <input type="submit" class="formulario__submit" value="Guardar Nuevo Password">
      </form>
  <?php
    }
  ?>

  
</main>