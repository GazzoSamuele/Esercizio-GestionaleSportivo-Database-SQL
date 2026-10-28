<!-- MODALE PRODOTTI — conferma eliminazione -->
<div class="modal fade" id="modalDeleteProduct<?= (int) $p['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteProduct<?= (int) $p['id'] ?>Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalDeleteProduct<?= (int) $p['id'] ?>Title">Eliminare <?= htmlspecialchars($p['name']) ?>?</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <p class="modal-text"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Il prodotto verrà eliminato in modo definitivo.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button--line button--sm" data-bs-dismiss="modal">Annulla</button>
                <form action="" method="post">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
                    <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
                    <button type="submit" class="button button--danger button--sm">Elimina prodotto</button>
                </form>
            </div>
        </div>
    </div>
</div>
