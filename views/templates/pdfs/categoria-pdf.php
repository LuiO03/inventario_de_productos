<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pdf - Lista de Categorías</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
        }

        .header-pdf {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        img {
            width: 40px;
        }

        .header-title {
            flex: 1;
            text-align: center;
        }

        h2 {
            margin: 0;
            font-size: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
            padding: 6px;
        }
    </style>
</head>

<body>
    <!-- Encabezado con logo y título -->
    <div class="header-pdf">
        <div class="logo">
            <img src="<?= __DIR__ . '/../../../public/images/logos/logo.png'; ?>" alt="Logo">
        </div>
        <div class="header-title">
            <h2>Listado de Categorías</h2>
        </div>
        <div style="width:70px;"></div> <!-- Espacio para balancear -->
    </div>

    <!-- Tabla -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= $categoria->getId(); ?></td>
                    <td><?= $categoria->getNombre(); ?></td>
                    <td><?= $categoria->getDescripcion(); ?></td>
                    <td><?= $categoria->getEstado() ? 'Activo' : 'Inactivo'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
