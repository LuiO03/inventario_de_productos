<?php headerAdmin(); ?>

<div class="contenedor-header text-center">
    <h1>Bienvenido, <?= $_SESSION['usuario']['nombre'] ?? 'Usuario' ?> al Panel Administrativo</h1>
    <p>Desde esta sección puedes gestionar todos los módulos del sistema.</p>
</div>

<div class="grid-tarjetas">
    <?php
    $colores = ['rojo', 'verde', 'azul', 'naranja', 'celeste', 'marron', 'gris', 'negro'];

    $tarjetas = [
        ['titulo' => 'Productos', 'total' => $totalProductos ?? 0, 'icono' => 'ri-shirt-line', 'url' => 'producto'],
        ['titulo' => 'Clientes', 'total' => $totalClientes ?? 0, 'icono' => 'ri-account-box-line', 'url' => 'cliente'],
        ['titulo' => 'Usuarios', 'total' => $totalUsuarios ?? 0, 'icono' => 'ri-id-card-line', 'url' => 'usuario'],
        ['titulo' => 'Pedidos', 'total' => $totalPedidos ?? 0, 'icono' => 'ri-shopping-cart-2-line', 'url' => 'pedido'],
        ['titulo' => 'Servicios', 'total' => $totalServicios ?? 0, 'icono' => 'ri-tools-line', 'url' => 'servicio'],
        ['titulo' => 'Blog', 'total' => $totalBlog ?? 0, 'icono' => 'ri-article-line', 'url' => 'blog'],
        ['titulo' => 'Mensajes', 'total' => $totalMensajes ?? 0, 'icono' => 'ri-mail-line', 'url' => 'mensaje'],
    ];

    foreach ($tarjetas as $i => $t) :
        $color = $colores[$i % count($colores)];
        $dark = "bg-{$color}-dark";
        $light = "bg-{$color}-light";
        $coloricon = "text-{$color}-light";
    ?>
        <a href="<?= BASE_URL . $t['url'] ?>" class="targeta <?= $light ?> text-white">
            <div class="tarjeta-icono <?= $dark ?>">
                <i class="<?= $t['icono'] ?> display-4 <?= $coloricon ?>"></i>
            </div>
            <div class="tarjeta-contenido">
                <h1><?= $t['total'] ?></h1>
                <h6><?= $t['titulo'] ?></h6>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php
modalFlash($mensaje);
footerAdmin();
?>