<div class="col_two_fifth col_centered nobottommargin">
    <div class="well well-lg nobottommargin">
      <form method="post" action="<?php echo base_url('PenyediaPusat/doEdit'); ?>">
        <input type="hidden" name="id" value="<?php echo $penyedia_pusat->id; ?>">
        <h3>UBAH DATA PENYEDIA PUSAT</h3>
        <hr>
        <div class="col_full">
          <label>KODE</label>
          <input type="text" name="kode_penyedia_pusat" class="form-control" value="<?php echo $penyedia_pusat->kode_penyedia_pusat; ?>" readonly />
        </div>
        <div class="col_full">
          <label>NAMA PENYEDIA</label>
          <input type="text" name="nama_penyedia_pusat" class="form-control" required value="<?php echo $penyedia_pusat->nama_penyedia_pusat; ?>" />
        </div>
        <div class="col_full nobottommargin">
          <a href="<?php echo base_url('PenyediaPusat'); ?>">
            <button type="button" class="button button-3d button-white button-light nomargin mr10">
                Cancel
            </button>
          </a>
          <button type="submit" class="button button-3d nomargin">Save</button>
        </div>
      </form>
    </div>
  </div>