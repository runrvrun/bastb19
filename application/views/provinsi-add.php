<div class="col_two_fifth col_centered nobottommargin">
    <div class="well well-lg nobottommargin">
      <form method="post" action="<?php echo base_url('Provinsi/doAdd'); ?>">
        <h3>TAMBAH DATA PROVINSI</h3>
        <hr>
        <div class="col_full">
          <label>KODE</label>
          <input type="text" name="kode_provinsi" class="form-control" required />
        </div>
        <div class="col_full">
          <label>PROVINSI</label>
          <input type="text" name="nama_provinsi" class="form-control" required />
        </div>
        <div class="col_full nobottommargin">
          <a href="<?php echo base_url('Provinsi'); ?>">
            <button type="button" class="button button-3d button-white button-light nomargin mr10">
                Cancel
            </button>
          </a>
          <button type="submit" class="button button-3d nomargin">Save</button>
        </div>
      </form>
    </div>
  </div>