<main class="auth">
  <h2 class="auth__heading"><?php echo $titulo; ?></h2>
  <p class="auth__text">Recupera el acceso a tu cuenta</p>

  <form method="POST" class="formulario">
    <div class="formulario__campo">
      <label for="email" class="formulario__label">Email</label>
      <input 
        type="email"
        class="formulario__input"
        placeholder="Tu Email"
        id="email"
        name="email"
      >
    </div>

    <input type="submit" class="formulario__submit" value="Enviar Instrucciones">
  </form>

  <div class="acciones">
    <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? Inicia sesión</a>
    <a href="/registro" class="acciones__enlace">¿Aún no tienes cuenta? Obtener una</a>
  </div>
</main>