<?php if (!empty($mensaje)): ?>
    <?php
        // Iconos por tipo de mensaje
        $iconos = [
            'success' => 'ri-checkbox-circle-line text-success',
            'danger' => 'ri-close-circle-line text-danger',
            'warning' => 'ri-alert-line text-warning',
            'info' => 'ri-information-line text-info',
            'primary' => 'ri-star-line text-primary',         // Para destacar algo especial
            'secondary' => 'ri-checkbox-blank-circle-line text-secondary', // Neutro, informativo
            'light' => 'ri-sun-line text-light',              // Mensajes suaves
            'dark' => 'ri-moon-line text-dark',               // Mensajes neutros o técnicos
            'question' => 'ri-question-line text-info',       // Para preguntas o dudas
            'update' => 'ri-edit-line text-warning',          // Para mensajes de actualización
            'delete' => 'ri-delete-bin-line text-danger',     // Eliminaciones específicas
            'login' => 'ri-login-circle-line text-success',   // Para login exitoso
            'logout' => 'ri-logout-box-line text-info',       // Para logout
        ];
        $tipo = htmlspecialchars($mensaje['type']);
        $icono = $iconos[$tipo] ?? 'ri-information-line text-info';
    ?>

    <!-- Modal de Bootstrap -->
    <div class="modal fade modal-slide-animate" id="flashModal" tabindex="-1" aria-labelledby="flashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <div class="modal-header border-0 bg-<?= $tipo ?>">
                    <h6 class="modal-title fw-bold" id="flashModalLabel">
                        <?= htmlspecialchars($mensaje['header']) ?>
                    </h6>
                    <button type="button" class="btn-close btn-close-white bg-white rounded-circle p-2" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body text-center">
                    <i class="<?= $icono ?> mb-3"  style="font-size: 6rem;"></i>
                    <p class="mb-0"><?= ($mensaje['message']) ?></p>
                </div>
                
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-sm btn-light d-flex align-items-center gap-1" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Script para abrir la modal automáticamente -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            var flashModal = new bootstrap.Modal(document.getElementById('flashModal'));
            flashModal.show();
        });
    </script>

    
<?php endif; ?>
