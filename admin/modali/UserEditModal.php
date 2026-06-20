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