<!-- MODALE PRODOTTI — modifica -->
<div class="modal fade" id="modalEditProduct<?= (int) $p['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditProduct<?= (int) $p['id'] ?>Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post">
        <div class="modal-header">
          <h2 class="modal-title" id="modalEditProduct<?= (int) $p['id'] ?>Title">Modifica <?= htmlspecialchars($p['name']) ?></h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body form-stack">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
          <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
          <label class="field">Nome
            <input type="text" name="name" class="input" value="<?= htmlspecialchars($p['name']) ?>" required>
          </label>
          <label class="field">URL immagine
            <input type="url" name="immagine" class="input" value="<?= htmlspecialchars($p['image_path']) ?>" required>
          </label>
          <label class="field">Descrizione
            <textarea name="descrizione" class="input" rows="3" required><?= htmlspecialchars($p['description']) ?></textarea>
          </label>
          <div class="field-row">
          <label class="field">Prezzo (€)
            <input type="text" name="prezzo" class="input" value="<?= htmlspecialchars($p['price']) ?>" required>
          </label>
          <label class="field">Disponibilità
            <select name="isActive" class="input">
              <option value="1" <?= (int) $p['is_active'] === 1 ? 'selected' : '' ?>>Disponibile</option>
              <option value="0" <?= (int) $p['is_active'] === 0 ? 'selected' : '' ?>>Non disponibile</option>
            </select>
          </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="button button--line button--sm" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="button button--ink button--sm">Salva modifiche</button>
        </div>
      </form>
    </div>
  </div>
</div>
