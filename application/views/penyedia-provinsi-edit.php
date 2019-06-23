<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('PenyediaProvinsi/doEdit'); ?>" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $penyedia_provinsi->id; ?>">
      <h3>UBAH DATA PENYEDIA TP PROVINSI</h3>
      <hr>
      <div class="col_full">
        <label>KODE</label>
        <input type="text" name="kode_penyedia_provinsi" class="form-control" readonly value="<?php echo $penyedia_provinsi->kode_penyedia_provinsi?>" />
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $penyedia_provinsi->id_provinsi ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>NAMA PENYEDIA</label>
        <input type="text" name="nama_penyedia_provinsi" class="form-control" required value="<?php echo $penyedia_provinsi->nama_penyedia_provinsi?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('PenyediaProvinsi'); ?>">
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