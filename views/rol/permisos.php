<?php
headerAdmin();
partialBreadcrumb();
?>
<div class="contenedor-header">
    <h1>
        Rol <span class="text-primario"><?= htmlspecialchars($rol->getNombre()) ?></span>
    </h1>
    <p><strong>Gestione</strong> los permisos para este <strong>rol</strong>.</p>
</div>

<style>
    .cards-permisos {
        display: none;
    }

    /* Aumentar altura de filas */
    #table-permisos tbody tr {
        height: 50px;
        /* Ajusta a la altura que quieras */
    }

    #table-permisos thead th {
        text-align: center;
        font-weight: normal;
        font-size: var(--font-size-p);
    }

    /* Centrar contenido de las celdas (checkboxes) */
    #table-permisos tbody td {
        vertical-align: middle;
    }

    #table-permisos .formulario-acciones .boton-form {
        font-size: var(--font-size-p);
    }

    /* Vista Móvil */
    @media (max-width: 768px) {

        .tabla-permisos {
            display: none;
        }

        .cards-permisos {
            display: grid;
            gap: 1rem;
        }

        .card-permiso {
            display: flex;
            flex-direction: column;
            gap: 10px;
            border-radius: var(--border-radio-card);
            padding: 1rem;
            background: var(--color-card-bg);
        }

        .card-permiso h3 {
            font-weight: bolder;
            font-size: 17px;
            font-family: var(--font-family-base);
        }

        .acciones {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
        }

        .acciones label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: var(--font-size-xs);
            padding: 0.5rem;
            border-radius: var(--border-radio-card);
            cursor: pointer;
            padding: 10px;
            background-color: var(--color-sidebar);
        }

        .acciones label:hover {
            background-color: var(--color-text);
            color: var(--color-bw-inverted);
        }

        .switch-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        .switch-card input {
            display: none;
        }

        .switch-card .slider {
            width: 50px;
            height: 26px;
            border-radius: 34px;
            background-color: #CCCCCC;
            transition: .4s;
            position: relative;
        }

        .switch-card .slider::before {
            content: "";
            position: absolute;
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: var(--color-sidebar);
            border-radius: 50%;
            transition: .4s;
        }

        .switch-card input:checked+.slider {
            background-color: var(--color-primario);
        }

        .switch-card input:checked+.slider::before {
            transform: translateX(24px);
        }

    }
</style>

<div class="contenedor">
    <div class="d-flex justify-content-between">
        <button type="button" id="activarTodos" class="boton bg-success">
            <span class="boton-icon"><i class="ri-check-line"></i></span>
            <span class="boton-text">Activar todos</span>
        </button>
        <button type="button" id="desactivarTodos" class="boton bg-danger">
            <span class="boton-icon"><i class="ri-close-line"></i></span>
            <span class="boton-text">Desactivar todos</span>
        </button>
    </div>
    <form action="<?= BASE_URL ?>rol/guardarPermisos/<?= $rol->getId() ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= generarTokenCSRF(); ?>">

        <!-- ✅ Vista Desktop (Tabla) -->
        <table id="table-permisos" class="w-100 mb-3 tabla-permisos">
            <thead>
                <tr>
                    <th>Módulo</th>
                    <th class="text-info"><i class="ri-eye-2-fill"></i> Ver</th>
                    <th class="text-success"><i class="ri-add-circle-fill"></i> Crear</th>
                    <th class="text-warning"><i class="ri-edit-circle-fill"></i> Editar</th>
                    <th class="text-danger"><i class="ri-delete-bin-fill"></i> Eliminar</th>
                    <th class="text-primary"><i class="ri-toggle-fill"></i> Estado</th>
                    <th class="color-text"><i class="ri-file-excel-2-fill"></i> Exportar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $permisosPorEntidad = [];
                foreach ($permisos as $p) {
                    $permisosPorEntidad[$p['entidad']][] = $p;
                }

                foreach ($permisosPorEntidad as $entidad => $acciones): ?>
                    <tr>
                        <td><?= ucfirst($entidad) ?></td>
                        <?php foreach (['ver', 'crear', 'editar', 'eliminar', 'cambiar_estado', 'exportar'] as $accion):
                            $permiso = array_filter($acciones, fn($a) => $a['accion'] === $accion);
                            $permiso = reset($permiso);
                            $checked = in_array($permiso['id'], $permisosAsignados) ? 'checked' : '';
                        ?>
                            <td class="text-center">
                                <label class="switch-tabla">
                                    <input type="checkbox"
  id="switch-permiso-<?= $permiso['id'] ?>"
  class="toggle-estado permiso-desktop"
  name="permisos[]"
  value="<?= $permiso['id'] ?>"
  <?= $checked ?>>
                                    <span class="slider"></span>
                                </label>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- ✅ Vista Móvil (Cards) -->
        <div class="cards-permisos mb-3">
            <?php foreach ($permisosPorEntidad as $entidad => $acciones): ?>
                <div class="card-permiso">
                    <h3><?= ucfirst($entidad) ?></h3>
                    <div class="acciones">
                        <?php
                        $accionesMap = [
                            'ver' => 'Ver',
                            'crear' => 'Crear',
                            'editar' => 'Editar',
                            'eliminar' => 'Eliminar',
                            'cambiar_estado' => 'Estado',
                            'exportar' => 'Exportar'
                        ];
                        foreach ($accionesMap as $accion => $label):
                            $permiso = array_filter($acciones, fn($a) => $a['accion'] === $accion);
                            $permiso = reset($permiso);
                            $checked = in_array($permiso['id'], $permisosAsignados) ? 'checked' : '';
                        ?>
                            <label class="switch-card">
                                <?= $label ?>
                                <input type="checkbox"
  id="switch-permiso-mob-<?= $permiso['id'] ?>"
  class="toggle-estado permiso-mobile"
  name="permisos[]"
  value="<?= $permiso['id'] ?>"
  <?= $checked ?>>
                                <span class="slider"></span>
                            </label>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Botones -->
        <div class="formulario-acciones edit">
            <a href="<?= BASE_URL ?>rol/" class="boton-form boton-volver">
                <i class="ri-arrow-left-circle-fill"></i> Volver
            </a>
            <button type="submit" class="boton-form boton-agregar">
                <i class="ri-save-3-fill"></i> Guardar
            </button>
        </div>
    </form>
</div>
<script>
  function setActiveView() {
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    document.querySelectorAll('.permiso-desktop').forEach(cb => cb.disabled = isMobile);
    document.querySelectorAll('.permiso-mobile').forEach(cb => cb.disabled = !isMobile);
  }

  function enabledPermisos() {
    return Array.from(document.querySelectorAll('input[name="permisos[]"]'))
      .filter(cb => !cb.disabled);
  }

  document.addEventListener('DOMContentLoaded', () => {
    setActiveView();

    // Botones maestro: solo tocan los habilitados
    document.getElementById('activarTodos').addEventListener('click', () => {
      enabledPermisos().forEach(cb => cb.checked = true);
    });

    document.getElementById('desactivarTodos').addEventListener('click', () => {
      enabledPermisos().forEach(cb => cb.checked = false);
    });
  });

  // Recalcular al cambiar tamaño (ej. abrir devtools responsive)
  window.addEventListener('resize', setActiveView);
</script>

<script>
    document.getElementById('activarTodos').addEventListener('click', () => {
        document.querySelectorAll('input.toggle-estado').forEach(cb => cb.checked = true);
    });
    document.getElementById('desactivarTodos').addEventListener('click', () => {
        document.querySelectorAll('input.toggle-estado').forEach(cb => cb.checked = false);
    });
    document.querySelectorAll('td').forEach(td => {
        const checkbox = td.querySelector('input.toggle-estado');
        if (checkbox) {
            td.style.cursor = 'pointer';
            td.addEventListener('click', (e) => {
                // Evita que el click en el propio checkbox/label duplique el toggle
                if (e.target.tagName.toLowerCase() !== 'input' && e.target.tagName.toLowerCase() !== 'span') {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    });

</script>

<?php
footerAdmin();
?>