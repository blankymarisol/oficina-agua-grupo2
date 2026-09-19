<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h6 fw-bold text-primary mb-0">Tarifas</h2>

    <a href="<?= site_url('tarifas/nuevo') ?>" class="btn btn-primary btn-sm">
        + Nueva tarifa
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Monto por unidad</th>
                    <th>Vigente desde</th>
                    <th>Vigente hasta</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($tarifas as $tarifa): ?>

                    <?php
                    $hoy = date('Y-m-d');

                    $activa = (int) $tarifa['activo'] === 1;

                    $futura =
                     $activa
                     && $tarifa['vigente_desde'] > $hoy;

                    $vigente =
                       $activa
                     && $tarifa['vigente_desde'] <= $hoy
                     && (
                           empty($tarifa['vigente_hasta'])
                          || $tarifa['vigente_hasta'] >= $hoy
                     );
                    ?>

                    <tr>
                        <td>
                            Q <?= number_format((float) $tarifa['monto_por_unidad'], 2) ?>
                        </td>

                        <td>
                            <?= esc($tarifa['vigente_desde']) ?>
                        </td>

                        <td>
                            <?= !empty($tarifa['vigente_hasta'])
                                ? esc($tarifa['vigente_hasta'])
                                : 'Sin fecha de fin' ?>
                        </td>

                        <td>
                          <?php if ($futura): ?>

                              <span class="badge text-bg-info">
                                  Programada
                             </span>

                            <?php elseif ($vigente): ?>

                                <span class="badge text-bg-success">
                                    Vigente
                               </span>

                         <?php else: ?>

                               <span class="badge text-bg-secondary">
                                   Vencida
                                </span>

                            <?php endif; ?>
                        </td>

                        <td class="text-end">
    <a
        href="<?= site_url('tarifas/editar/' . $tarifa['id']) ?>"
        class="btn btn-sm btn-outline-primary"
    >
        Editar
    </a>

    <?php if ((int) $tarifa['activo'] === 1): ?>
    <form id="form-desactivar-tarifa-<?= $tarifa['id'] ?>"
          action="<?= site_url('tarifas/desactivar/' . $tarifa['id']) ?>" method="post" class="d-inline">
        <?= csrf_field() ?>
    </form>
    <button type="button" class="btn btn-sm btn-outline-danger"
            data-bs-toggle="modal" data-bs-target="#modalConfirmarTarifa"
            data-form-id="form-desactivar-tarifa-<?= $tarifa['id'] ?>">
        Desactivar
    </button>
<?php endif; ?>
</td>
                    </tr>

                <?php endforeach; ?>

                <?php if (empty($tarifas)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay tarifas registradas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalConfirmarTarifa" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar desactivación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Está segura de que deseas desactivar esta tarifa?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnConfirmarTarifa">Sí, desactivar</button>
      </div>
    </div>
  </div>
</div>

<?= $this->section('scripts') ?>
<script>
  (function () {
    const modal = document.getElementById('modalConfirmarTarifa');
    const btnConfirmar = document.getElementById('btnConfirmarTarifa');
    let formActualId = null;

    modal.addEventListener('show.bs.modal', function (evento) {
      formActualId = evento.relatedTarget.getAttribute('data-form-id');
    });

    btnConfirmar.addEventListener('click', function () {
      if (formActualId) {
        document.getElementById(formActualId).submit();
      }
    });
  })();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>