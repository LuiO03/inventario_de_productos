<!DOCTYPE html>
<html>

<head>
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="<?= APP_LOGO ?>" type="image/x-icon">
    <meta charset="UTF-8">
    <style>
        h2 {
            text-align: center;
            font-size: 20px;
        }

        body {
            font-family: "Lato", sans-serif;
            
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: black;
            color: white;
            text-align: center;
            font-size: 13px;
        }

        td {
            font-size: 12px;
            text-align: start;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        img {
            width: 60px;
            height: auto;
        }
    </style>
</head>

<body>
    <h2>Listado de Marcas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($marcas as $marca): ?>
                <tr>
                    <td><?= $marca->getId(); ?></td>
                    <td><?= $marca->getNombre(); ?></td>
                    <td><?= $marca->getDescripcion(); ?></td>
                    <td><?= $marca->getEstado() ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <?php
                        $img = $marca->getImagen();
                        $ruta = 'public/images/marcas/' . $img;
                        if (file_exists($ruta)):
                        ?>
                            <img src="<?= $ruta ?>" alt="logo marca">
                        <?php else: ?>
                            No imagen
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>