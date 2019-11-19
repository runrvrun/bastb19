<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('AdminRating/doAdd'); ?>">
      <h3>TAMBAH DATA ADMIN RATING</h3>
      <hr>
      <div class="col_full">
        <label>EMAIL / ID PENGGUNA</label>
        <input type="text" name="id_pengguna" id="id_pengguna" class="form-control" required />
      </div>
      <div class="col_full">
        <label>NAMA LENGKAP</label>
        <input type="text" name="nama" class="form-control" required />
      </div>
      <div class="col_full">
        <label>NO TELEPON</label>
        <input type="text" name="no_telepon" class="form-control numberFilter" required />
      </div>
      <div class="col_full">
        <label>WILAYAH</label> <br>
        <input type="radio" name="wilayah" value="prov" checked> PROVINSI
        <input type="radio" name="wilayah" value="kab" style="margin-left:20px;"> KABUPATEN
      </div>
      <div class="col_full" id="ddlprov">
        <label>PROVINSI</label>
        <select name="id_provinsi" class="form-control js-example-basic-single" id="selprov">
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id.">".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>      
      <div class="col_full" id="ddlkab">
        <label>KABUPATEN/KOTA</label>
        <select name="id_kabupaten" class="form-control js-example-basic-single" id="selkab" disabled="disabled">
        <?php 
          foreach($kabupaten as $kab){
            echo "<option value=".$kab->id.">".$kab->nama_kabupaten."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>NON AKTIFKAN AKUN</label>
        <select name="is_active" class="form-control">
          <option value="1">TIDAK</option>
          <option value="0">YA</option>
        </select>
      </div>
      <div class="col_full">
        <label>PASSWORD</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('AdminRating'); ?>">
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
    $('#ddlkab').toggle();
    $('input[type=radio][name=wilayah]').change(function() {
        if (this.value == 'kab') {
            $('#ddlprov').toggle();
            $('#selprov').prop('disabled', 'disabled');
            $('#ddlkab').toggle();
            $('#selkab').prop('disabled', false);
        }
        else if (this.value == 'prov') {
            $('#ddlkab').toggle();
            $('#selkab').prop('disabled', 'disabled');
            $('#ddlprov').toggle();
            $('#selprov').prop('disabled', false);
        }
    });
  });
</script>