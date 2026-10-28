<!-- MODALE UTENTI — modifica -->
<div class="modal fade" id="modalEdit<?= (int) $u['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEdit<?= (int) $u['id'] ?>Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post">
        <div class="modal-header">
          <h2 class="modal-title" id="modalEdit<?= (int) $u['id'] ?>Title">Modifica <?= htmlspecialchars($u['name']) ?></h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body form-stack">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
          <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
          <label class="field">Nome
            <input type="text" name="name" class="input" value="<?= htmlspecialchars($u['name']) ?>" required>
          </label>
          <label class="field">Email
            <input type="email" name="email" class="input" value="<?= htmlspecialchars($u['email']) ?>" required>
          </label>
          <label class="field">Ruolo
            <select name="role" class="input">
              <option value="client" <?= $u['role'] === 'client' ? 'selected' : '' ?>>Socio</option>
              <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Amministratore</option>
            </select>
          </label>
        </div>
        <div class="modal-footer">
          <button type="button" class="button button--line button--sm" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="button button--ink button--sm">Salva modifiche</button>
        </div>
      </form>
    </div>
  </div>
</div>
