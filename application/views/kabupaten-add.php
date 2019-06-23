<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Kabupaten/doAdd'); ?>" style="font-color:black;">
      <h3>TAMBAH DATA KABUPATEN/KOTA</h3>
      <hr>
      <div class="col_full">
        <label>KODE</label>
        <input type="text" name="kode_kabupaten" class="form-control" required />
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id.">".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KABUPATEN/KOTA</label>
        <input type="text" name="nama_kabupaten" class="form-control" required />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Kabupaten'); ?>">
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