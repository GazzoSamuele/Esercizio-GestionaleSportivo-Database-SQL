<!-- MODALE IMPEGNI -->

<div class="modal fade" id="modalDeleteImpegni<?= (int) $imp['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title">Eliminare <?= htmlspecialchars($imp['titolo']) ?>?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                L'impegno verrà eliminato in modo definitivo.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="" method="post">
                    <input type="hidden" name="action"  value="delete">
                    <input type="hidden" name="impegno_id" value="<?= (int) $imp['id'] ?>">
                    <button type="submit" class="btn btn-danger">Elimina Impegno</button>
                </form>
            </div>
        </div>
    </div>
</div>