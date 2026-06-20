<!-- MODALE NEWS E COMUNICAZIONI -->

<div class="modal fade" id="modalDeleteNews<?= (int) $nw['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title">Eliminare <?= htmlspecialchars($nw['title']) ?>?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                La comunicazione verrà eliminata in modo definitivo.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="" method="post">
                    <input type="hidden" name="action"  value="delete">
                    <input type="hidden" name="news_id" value="<?= (int) $nw['id'] ?>">
                    <button type="submit" class="btn btn-danger">Elimina Comunicazione</button>
                </form>
            </div>
        </div>
    </div>
</div>