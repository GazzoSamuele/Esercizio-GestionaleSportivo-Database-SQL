<!-- MODALE IMPEGNI -->

<div class="modal fade" id="modalEditImpegni<?= (int) $imp['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-start">
      <form action="" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Modifica <?= htmlspecialchars($imp['titolo']) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action"  value="edit">
          <input type="hidden" name="impegno_id" value="<?= (int) $imp['id'] ?>">

          <div class="mb-3">
            <label>Titolo</label>
            <input type="text" name="titolo" class="form-control" value="<?= htmlspecialchars($imp['titolo']) ?>" required>
          </div>

          <div class="mb-3">
            <label>Descrizione</label>
            <textarea name="descrizione" class="form-control" required><?= htmlspecialchars($imp['descrizione']) ?></textarea>
          </div>

          <div class="mb-3">
            <label>Tipo di impegno</label>
            <select name="tipo" class="form-select">
              <option value="allenamento" <?= $imp['tipo'] === 'allenamento' ? 'selected' : '' ?>>Allenamento</option>
              <option value="partita" <?= $imp['tipo'] === 'partita' ? 'selected' : '' ?>>Partita</option>
              <option value="coppa" <?= $imp['tipo'] === 'coppa' ? 'selected' : '' ?>>Coppa</option>
              <option value="riunione" <?= $imp['tipo'] === 'riunione' ? 'selected' : '' ?>>Riunione</option>
              <option value="altro" <?= $imp['tipo'] === 'altro' ? 'selected' : '' ?>>Altro</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Data</label>
            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($imp['data']) ?>" required>
          </div>

          <div class="mb-3">
            <label>Ora</label>
            <input type="time" name="ora" class="form-control" value="<?= htmlspecialchars($imp['ora']) ?>" required>
          </div>

          <div class="mb-3">
            <label for="luogo">Luogo</label>
            <input type="text" name="luogo" class="form-control" value="<?= htmlspecialchars($imp['luogo']) ?>" required>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="btn btn-warning">Salva Impegno</button>
        </div>
      </form>
    </div>
  </div>
</div>