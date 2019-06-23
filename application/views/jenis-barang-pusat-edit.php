<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('JenisBarangPusat/doEdit'); ?>" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $jenis_barang_pusat->id; ?>" />
      <h3>UBAH DATA JENIS BARANG PUSAT</h3>
      <hr>
      <div class="col_full">
        <label>JENIS BARANG</label>
        <input type="text" name="jenis_barang" class="form-control" required value="<?php echo $jenis_barang_pusat->jenis_barang; ?>" />
      </div>
      <div class="col_full">
        <label>NAMA BARANG</label>
        <input type="text" name="nama_barang" class="form-control" required value="<?php echo $jenis_barang_pusat->nama_barang; ?>" />
      </div>
      <div class="col_full">
        <label>MERK</label>
        <input type="text" name="merk" class="form-control" required value="<?php echo $jenis_barang_pusat->merk; ?>" />
      </div>
      <div class="col_full">
        <label>PENYEDIA</label>
        <select name="id_penyedia_pusat" class="form-control js-example-basic-single" required>
        <?php 
          foreach($penyedia_pusat as $penyedia){
            echo "<option value=".$penyedia->id." ".($penyedia->id == $jenis_barang_pusat->id_penyedia_pusat ? 'selected' : '')." >".$penyedia->nama_penyedia_pusat."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KODE BARANG</label>
        <input type="text" name="kode_barang" class="form-control" required value="<?php echo $jenis_barang_pusat->kode_barang; ?>" />
      </div>
      <div class="col_full">
        <label>AKUN</label>
        <input type="text" name="akun" class="form-control" required value="<?php echo $jenis_barang_pusat->akun; ?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('JenisBarangPusat'); ?>">
          <button type="button" class="button button-3d button-white button-light nomargin mr10">
              Cancel
          </button>
        </a>
        <button type="submit" class="button button-3d nomargin">Save</button>
      </div>
    </form>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
</script>