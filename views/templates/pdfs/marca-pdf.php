<!DOCTYPE html>
<html>

<head>
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="/../../../public/images/logos/logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Pdf - Lista de Marcas</title>
</head>
<style>
        h2 {
            text-align: center;
            font-size: 30px;
        }

        body {
            font-family: "Poppins", sans-serif;
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
        .imagen-pdf{
            text-align:start; 
            margin-bottom: 10px;
            
        }
        img{
            width: 50px;
        }
    </style>
<body>
    <div class="imagen-pdf">
        <img src="<?= __DIR__ . '/../../../public/images/logos/logo.png'; ?>" alt="Logo" width="120">
    </div>
    <h2>Listado de Marcas</h2>
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
            <?php foreach ($marcas as $marca): ?>
                <tr>
                    <td><?= $marca->getId(); ?></td>
                    <td><?= $marca->getNombre(); ?></td>
                    <td><?= $marca->getDescripcion(); ?></td>
                    <td><?= $marca->getEstado() ? 'Activo' : 'Inactivo'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>