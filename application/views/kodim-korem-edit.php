<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('KodimKorem/doEdit'); ?>">
      <input type="hidden" name="id" value="<?php echo $kodim_korem->id; ?>">
      <h3>UBAH DATA KODIM/KOREM</h3>
      <hr>
      <div class="col_full">
        <label>BRIGADE</label>
        <select name="brigade" class="form-control" required>
          <option value="KODIM" <?php echo ($kodim_korem->brigade == 'KODIM' ? 'selected' : ''); ?> >KODIM</option>
          <option value="KOREM" <?php echo ($kodim_korem->brigade == 'KOREM' ? 'selected' : ''); ?> >KOREM</option>
        </select>
      </div>
      <div class="col_full">
        <label>KODIM/KOREM</label>
        <input type="text" name="nama_kodim_korem" class="form-control" required value="<?php echo $kodim_korem->nama_kodim_korem; ?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('KodimKorem'); ?>">
          <button type="button" class="button button-3d button-white button-light nomargin mr10">
              Cancel
          </button>
        </a>
        <button type="submit" class="button button-3d nomargin">Save</button>
      </div>
    </form>
  </div>
</div>