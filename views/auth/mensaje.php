<main class="auth">
  <h2 class="auth__heading"><?php echo $titulo; ?></h2>
  <p class="auth__text <?php echo $mensaje ? 'auth__text--correcto' : '' ?>">
    <?php 
      echo $mensaje;
    ?>
  </p>
</main>