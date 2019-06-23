<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('KodimKorem/doAdd'); ?>">
      <h3>TAMBAH DATA KODIM/KOREM</h3>
      <hr>
      <div class="col_full">
        <label>BRIGADE</label>
        <select name="brigade" class="form-control" required>
          <option value="KODIM">KODIM</option>
          <option value="KOREM">KOREM</option>
        </select>
      </div>
      <div class="col_full">
        <label>KODIM/KOREM</label>
        <input type="text" name="nama_kodim_korem" class="form-control" required />
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