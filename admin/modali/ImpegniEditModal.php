<!-- MODALE IMPEGNI — modifica -->
<div class="modal fade" id="modalEditImpegni<?= (int) $imp['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditImpegni<?= (int) $imp['id'] ?>Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post">
        <div class="modal-header">
          <h2 class="modal-title" id="modalEditImpegni<?= (int) $imp['id'] ?>Title">Modifica <?= htmlspecialchars($imp['titolo']) ?></h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body form-stack">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
          <input type="hidden" name="impegno_id" value="<?= (int) $imp['id'] ?>">
          <label class="field">Titolo
            <input type="text" name="titolo" class="input" value="<?= htmlspecialchars($imp['titolo']) ?>" required>
          </label>
          <label class="field">Descrizione
            <textarea name="descrizione" class="input" rows="3" required><?= htmlspecialchars($imp['descrizione']) ?></textarea>
          </label>
          <label class="field">Tipo di impegno
            <select name="tipo" class="input">
              <option value="allenamento" <?= $imp['tipo'] === 'allenamento' ? 'selected' : '' ?>>Allenamento</option>
              <option value="partita" <?= $imp['tipo'] === 'partita' ? 'selected' : '' ?>>Partita</option>
              <option value="coppa" <?= $imp['tipo'] === 'coppa' ? 'selected' : '' ?>>Coppa</option>
              <option value="riunione" <?= $imp['tipo'] === 'riunione' ? 'selected' : '' ?>>Riunione</option>
              <option value="altro" <?= $imp['tipo'] === 'altro' ? 'selected' : '' ?>>Altro</option>
            </select>
          </label>
          <div class="field-row">
          <label class="field">Data
            <input type="date" name="data" class="input" value="<?= htmlspecialchars($imp['data']) ?>" required>
          </label>
          <label class="field">Ora
            <input type="time" name="ora" class="input" value="<?= htmlspecialchars($imp['ora']) ?>" required>
          </label>
          </div>
          <label class="field">Luogo
            <input type="text" name="luogo" class="input" value="<?= htmlspecialchars($imp['luogo']) ?>" required>
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
