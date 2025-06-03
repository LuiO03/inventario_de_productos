<?php
// Cargar automáticamente todos los helpers del directorio "helpers"

$helperFiles = glob('helpers/*.php');

foreach ($helperFiles as $file) {
    require_once $file;
}
