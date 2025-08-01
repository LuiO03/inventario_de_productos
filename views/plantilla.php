<?php 
    require_once "views/templates/layouts/header.php";
?>
<main id="contenido-principal">
      <div class="background">

        <span class="ball"></span>
        <span class="ball"></span>
        <span class="ball"></span>
        <span class="ball"></span>
        <span class="ball"></span>
        <span class="ball"></span>
      </div>
  <?php include $contenido; // aquí va el módulo que se carga ?>
</main>
<?php
    footerAdmin();
    
?>
