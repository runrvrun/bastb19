<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('AdminLO/doEdit'); ?>">
      <input type="hidden" name="id" value="<?php echo $admin_lo->id; ?>" />
      <h3>UBAH DATA ADMIN LO</h3>
      <hr>
      <div class="col_full">
        <label>EMAIL / ID PENGGUNA</label>
        <input type="text" name="id_pengguna" id="id_pengguna" class="form-control" value="<?php echo $admin_lo->id_pengguna; ?>"  />
      </div>
      <div class="col_full">
        <label>NAMA LENGKAP</label>
        <input type="text" name="nama" class="form-control" value="<?php echo $admin_lo->nama; ?>" />
      </div>
      <div class="col_full">
        <label>NO TELEPON</label>
        <input type="text" name="no_telepon" class="form-control numberFilter" value="<?php echo $admin_lo->no_telepon; ?>" />
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" class="form-control js-example-basic-single">
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $admin_lo->id_provinsi ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>NON AKTIFKAN AKUN</label>
        <select name="is_active" class="form-control">
          <option value="1" <?php echo ($admin_lo->is_active == 1 ? 'selected' : ''); ?> >TIDAK</option>
          <option value="0" <?php echo ($admin_lo->is_active == 0 ? 'selected' : ''); ?> >YA</option>
        </select>
      </div>
      <div class="col_full">
        <label>PASSWORD</label>
        <input type="password" name="password" class="form-control" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('AdminLO'); ?>">
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
  $(".numberFilter").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
         // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
         // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
  });

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
</script>