<!-- MODALE USERS -->

<div class="modal fade" id="modalEdit<?= (int) $u['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-start">
      <form action="" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Modifica <?= htmlspecialchars($u['name']) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action"  value="edit">
          <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
          <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($u['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Ruolo</label>
            <select name="role" class="form-select">
              <option value="client" <?= $u['role'] === 'client' ? 'selected' : '' ?>>Client</option>
              <option value="admin"  <?= $u['role'] === 'admin'  ? 'selected' : '' ?>>Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="btn btn-warning">Salva modifiche</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODALE PRODOTTI -->

<div class="modal fade" id="modalEditProduct<?= (int) $p['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-start">
      <form action="" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Modifica <?= htmlspecialchars($p['name']) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action"  value="edit">
          <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
          <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($p['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Immagine</label>
            <input type="url" name="immagine" class="form-control" value="<?= htmlspecialchars($p['image_path']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Descrizione</label>
            <textarea name="descrizione" class="form-control"<?= htmlspecialchars($p['description']) ?> required></textarea>
          </div>
          <div class="mb-3">
            <label>Prezzo</label>
            <input type="text" name="prezzo" class="form-control" value="<?= htmlspecialchars($p['price']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Disponibilità</label>
            <select name="isActive" class="form-select">
              <option value="1" <?= (int) $p['is_active'] === 1 ? 'selected' : '' ?>>Disponibile</option>
              <option value="0" <?= (int) $p['is_active'] === 0 ? 'selected' : '' ?>>Non Disponibile</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="btn btn-warning">Salva modifiche</button>
        </div>
      </form>
    </div>
  </div>
</div>