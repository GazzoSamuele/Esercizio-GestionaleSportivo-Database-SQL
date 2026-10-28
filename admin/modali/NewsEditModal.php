<!-- MODALE NEWS E COMUNICAZIONI — modifica -->
<div class="modal fade" id="modalEditNews<?= (int) $nw['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditNews<?= (int) $nw['id'] ?>Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post">
        <div class="modal-header">
          <h2 class="modal-title" id="modalEditNews<?= (int) $nw['id'] ?>Title">Modifica <?= htmlspecialchars($nw['title']) ?></h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body form-stack">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
          <input type="hidden" name="news_id" value="<?= (int) $nw['id'] ?>">
          <label class="field">Titolo
            <input type="text" name="title" class="input" value="<?= htmlspecialchars($nw['title']) ?>" required>
          </label>
          <label class="field">Descrizione
            <textarea name="description" class="input" rows="3" required><?= htmlspecialchars($nw['description']) ?></textarea>
          </label>
          <label class="field">Tipo
            <select name="tipo" class="input">
              <option value="notizia" <?= $nw['tipo'] === 'notizia' ? 'selected' : '' ?>>Notizia (sito pubblico)</option>
              <option value="comunicazione" <?= $nw['tipo'] === 'comunicazione' ? 'selected' : '' ?>>Comunicazione (solo soci)</option>
            </select>
          </label>
          <label class="field">URL immagine
            <input type="url" name="image_path" class="input" value="<?= htmlspecialchars($nw['image_path']) ?>" required>
          </label>
          <label class="field">Data
            <input type="date" name="data" class="input" value="<?= htmlspecialchars($nw['data']) ?>" required>
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
