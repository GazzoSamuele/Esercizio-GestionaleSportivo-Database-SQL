<!-- MODALE USERS -->

<div class="modal fade" id="modalDelete<?= (int) $u['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title">Eliminare <?= htmlspecialchars($u['name']) ?>?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                L'utente verrà eliminato in modo definitivo.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="" method="post">
                    <input type="hidden" name="action"  value="delete">
                    <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                    <button type="submit" class="btn btn-danger">Elimina Utente</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODALE PRODOTTI -->

<div class="modal fade" id="modalDeleteProduct<?= (int) $p['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title">Eliminare <?= htmlspecialchars($p['name']) ?>?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Il prodotto verrà eliminato in modo definitivo.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="" method="post">
                    <input type="hidden" name="action"  value="delete">
                    <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
                    <button type="submit" class="btn btn-danger">Elimina Prodotto</button>
                </form>
            </div>
        </div>
    </div>
</div>