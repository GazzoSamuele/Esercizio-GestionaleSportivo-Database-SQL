<!-- MODALE NEWS E COMUNICAZIONI -->

<div class="modal fade" id="modalEditNews<?= (int) $nw['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-start">
      <form action="" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Modifica <?= htmlspecialchars($nw['title']) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action"  value="edit">
          <input type="hidden" name="news_id" value="<?= (int) $nw['id'] ?>">

          <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($nw['title']) ?>" required>
          </div>

          <div class="mb-3">
            <label>Descrizione</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($nw['description']) ?></textarea>
          </div>

          <div class="mb-3">
            <label>Tipo di Comunicazione</label>
            <select name="tipo" class="form-select">
              <option value="notizia" <?= $nw['tipo'] === 'notizia' ? 'selected' : '' ?>>News</option>
              <option value="comunicazione" <?= $nw['tipo'] === 'comunicazione' ? 'selected' : '' ?>>Comunicazione</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Immagine</label>
            <input type="url" name="image_path" class="form-control" value="<?= htmlspecialchars($nw['image_path']) ?>" required>
          </div>

          <div class="mb-3">
            <label>Data Della Modifica</label>
            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($nw['data']) ?>" required>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="btn btn-warning">Salva Comunicazione</button>
        </div>
      </form>
    </div>
  </div>
</div>