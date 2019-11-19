<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Alokasi_provinsi/update'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $alokasi_provinsi->id; ?>" />
      <h3>UBAH DATA ALOKASI KONTRAK PROVINSI</h3>
      <hr>
      <div class="col_full">
        <label>PROVINSI</label>
        <input type="hidden" name="id_provinsi" value="<?php echo $alokasi_provinsi->id_provinsi;?>" />
        <select id="id_provinsi" class="form-control js-example-basic-single" disabled>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $alokasi_provinsi->id_provinsi ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KABUPATEN/KOTA</label>
        <select name="id_kabupaten" id="id_kabupaten" class="form-control js-example-basic-single">
        </select>
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG</label>
        <input type="text" name="jumlah_barang" id="jumlah_barang" class="form-control numberFilter" value="<?php echo $alokasi_provinsi->jumlah_barang; ?>" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP)</label>
        <input type="text" name="nilai_barang" id="nilai_barang" class="form-control numberFilter" value="<?php echo $alokasi_provinsi->nilai_barang; ?>" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP)</label>
        <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" readonly value="<?php echo ($alokasi_provinsi->jumlah_barang == 0 ? 0 : ($alokasi_provinsi->nilai_barang/$alokasi_provinsi->jumlah_barang)); ?>" />
      </div>
      <div class="col_full">
        <label>REG/CAD</label>
        <select name="regcad" class="form-control">
          <option value="REGULER" <?php echo ($alokasi_provinsi->regcad == 'REGULER' ? 'selected' : ''); ?> >REGULER</option>
          <!-- <option value="CADANGAN" <?php echo ($alokasi_provinsi->regcad == 'CADANGAN' ? 'selected' : ''); ?>>CADANGAN</option> -->
        </select>
      </div>
      <div class="col_full">
        <label>ID</label>
        <select name="dinas" class="form-control">
          <option value="BKB" <?php echo ($alokasi_provinsi->dinas == 'BKB' ? 'selected' : ''); ?> >BKB</option>
          <option value="DINAS" <?php echo ($alokasi_provinsi->dinas == 'DINAS' ? 'selected' : ''); ?> >DINAS</option>
          <option value="DISBUN" <?php echo ($alokasi_provinsi->dinas == 'DISBUN' ? 'selected' : ''); ?> >DISBUN</option>          
          <option value="KODIM" <?php echo ($alokasi_provinsi->dinas == 'KODIM' ? 'selected' : ''); ?> >KODIM</option>
          <option value="KOREM" <?php echo ($alokasi_provinsi->dinas == 'KOREM' ? 'selected' : ''); ?> >KOREM</option>
          <option value="LAPAS" <?php echo ($alokasi_provinsi->dinas == 'LAPAS' ? 'selected' : ''); ?> >LAPAS</option>
          <option value="PROVINSI" <?php echo ($alokasi_provinsi->dinas == 'PROVINSI' ? 'selected' : ''); ?> >PROVINSI</option>
        </select>
      </div>
      <h3>DATA ADDENDUM 1</h3>
      <div class="col_full">
        <label>NO ADDENDUM 1</label>
        <input type="text" name="no_adendum_1" id="no_adendum_1" class="form-control" value="<?php echo $alokasi_provinsi->no_adendum_1; ?>" />
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG REV. 1</label>
        <input type="text" name="jumlah_barang_rev_1" id="jumlah_barang_rev_1" class="form-control numberFilter" value="<?php echo ($alokasi_provinsi->jumlah_barang_rev_1 == 0 ? '' : $alokasi_provinsi->jumlah_barang_rev_1); ?>"  />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP) REV. 1</label>
        <input type="text" name="nilai_barang_rev_1" id="nilai_barang_rev_1" class="form-control numberFilter" value="<?php echo ($alokasi_provinsi->nilai_barang_rev_1 == 0 ? '' : $alokasi_provinsi->nilai_barang_rev_1); ?>" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP) REV. 1</label>
        <input type="text" name="harga_satuan_rev_1" id="harga_satuan_rev_1" class="form-control" readonly value="<?php echo ($alokasi_provinsi->jumlah_barang_rev_1 == 0 ? 0 : ($alokasi_provinsi->nilai_barang_rev_1/$alokasi_provinsi->jumlah_barang_rev_1)); ?>" />
      </div>
      <h3>DATA ADDENDUM 2</h3>
      <div class="col_full">
        <label>NO ADDENDUM 2</label>
        <input type="text" name="no_adendum_2" id="no_adendum_2" class="form-control" value="<?php echo $alokasi_provinsi->no_adendum_2; ?>" />
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG REV. 2</label>
        <input type="text" name="jumlah_barang_rev_2" id="jumlah_barang_rev_2" class="form-control numberFilter" value="<?php echo ($alokasi_provinsi->jumlah_barang_rev_2 == 0 ? '' : $alokasi_provinsi->jumlah_barang_rev_2); ?>" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP) REV. 2</label>
        <input type="text" name="nilai_barang_rev_2" id="nilai_barang_rev_2" class="form-control numberFilter" value="<?php echo ($alokasi_provinsi->nilai_barang_rev_2 == 0 ? '' : $alokasi_provinsi->nilai_barang_rev_2); ?>" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP) REV. 2</label>
        <input type="text" name="harga_satuan_rev_2" id="harga_satuan_rev_2" class="form-control" readonly value="<?php echo ($alokasi_provinsi->jumlah_barang_rev_2 == 0 ? 0 : ($alokasi_provinsi->nilai_barang_rev_2/$alokasi_provinsi->jumlah_barang_rev_2)); ?>" />
      </div>
      <h3>DATA ADDENDUM 3</h3>
      <div class="col_full">
        <label>NO ADDENDUM 3</label>
        <input type="text" name="no_adendum_3" id="no_adendum_3" class="form-control" value="<?php echo $alokasi_provinsi->no_adendum_3; ?>" />
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG REV. 3</label>
        <input type="text" name="jumlah_barang_rev_3" id="jumlah_barang_rev_3" class="form-control numberFilter" value="<?php echo ($alokasi_provinsi->jumlah_barang_rev_3 == 0 ? '' : $alokasi_provinsi->jumlah_barang_rev_3); ?>" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP) REV. 3</label>
        <input type="text" name="nilai_barang_rev_3" id="nilai_barang_rev_3" class="form-control numberFilter" value="<?php echo ($alokasi_provinsi->nilai_barang_rev_3 == 0 ? '' : $alokasi_provinsi->nilai_barang_rev_3); ?>" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP) REV. 3</label>
        <input type="text" name="harga_satuan_rev_3" id="harga_satuan_rev_3" class="form-control" readonly value="<?php echo ($alokasi_provinsi->jumlah_barang_rev_3 == 0 ? 0 : ($alokasi_provinsi->nilai_barang_rev_3/$alokasi_provinsi->jumlah_barang_rev_3)); ?>" />
      </div>
      <h3>KETERANGAN ALOKASI</h3>
      <div class="col_full">
        <label>STATUS ALOKASI</label>
        <select name="status_alokasi" class="form-control">
          <option value="MENUNGGU KONFIRMASI" <?php echo ($alokasi_provinsi->status_alokasi == 'MENUNGGU KONFIRMASI' ? 'selected' : ''); ?> >MENUNGGU KONFIRMASI</option>
          <option value="DATA KONTRAK AWAL" <?php echo ($alokasi_provinsi->status_alokasi == 'DATA KONTRAK AWAL' ? 'selected' : ''); ?> >DATA KONTRAK AWAL</option>
          <option value="DATA ADDENDUM 1" <?php echo ($alokasi_provinsi->status_alokasi == 'DATA ADDENDUM 1' ? 'selected' : ''); ?> >DATA ADDENDUM 1</option>
          <option value="DATA ADDENDUM 2" <?php echo ($alokasi_provinsi->status_alokasi == 'DATA ADDENDUM 2' ? 'selected' : ''); ?> >DATA ADDENDUM 2</option>
          <option value="DATA ADDENDUM 3" <?php echo ($alokasi_provinsi->status_alokasi == 'DATA ADDENDUM 3' ? 'selected' : ''); ?> >DATA ADDENDUM 3</option>
        </select>
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Alokasi_provinsi/index?id_kontrak_provinsi=').$kontrak_provinsi->id; ?>">
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
      LoadKabupaten();

      $('#image_preview_1').hide();
      $('#image_preview_2').hide();

  });

  $('#id_provinsi').change(function() {
      LoadKabupaten();
  });

  function LoadKabupaten()
  {
    $.ajax({
      url: "<?php echo base_url('Kabupaten/GetByProvinsi'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_provinsi : $('#id_provinsi').val()},
      success: function(data) {
          $('#id_kabupaten').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo $alokasi_provinsi->id_kabupaten; ?>'){
              $('#id_kabupaten').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kabupaten"]+"</option>"
              );
            }
            else{
              $('#id_kabupaten').append(
                "<option value='"+data[i]["id"]+"'>"+data[i]["nama_kabupaten"]+"</option>"
              );
            }
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }
  

  $('#jumlah_barang').on('change keyup paste mouseup', function() {
    $('#harga_satuan').val($('#nilai_barang').val() / $('#jumlah_barang').val());
  });

  $('#nilai_barang').on('change keyup paste mouseup', function() {
    $('#harga_satuan').val($('#nilai_barang').val() / $('#jumlah_barang').val());
  });

  $('#jumlah_barang').number( true, 0 );
  $('#nilai_barang').number( true, 0 );
  $('#harga_satuan').number( true, 0 );

  $('#jumlah_barang_rev_1').on('change keyup paste mouseup', function() {
    $('#harga_satuan_rev_1').val($('#nilai_barang_rev_1').val() / $('#jumlah_barang_rev_1').val());
  });

  $('#nilai_barang_rev_1').on('change keyup paste mouseup', function() {
    $('#harga_satuan_rev_1').val($('#nilai_barang_rev_1').val() / $('#jumlah_barang_rev_1').val());
  });

  $('#jumlah_barang_rev_1').number( true, 0 );
  $('#nilai_barang_rev_1').number( true, 0 );
  $('#harga_satuan_rev_1').number( true, 0 );

  $('#jumlah_barang_rev_2').on('change keyup paste mouseup', function() {
    $('#harga_satuan_rev_2').val($('#nilai_barang_rev_2').val() / $('#jumlah_barang_rev_2').val());
  });

  $('#nilai_barang_rev_2').on('change keyup paste mouseup', function() {
    $('#harga_satuan_rev_2').val($('#nilai_barang_rev_2').val() / $('#jumlah_barang_rev_2').val());
  });

  $('#jumlah_barang_rev_2').number( true, 0 );
  $('#nilai_barang_rev_2').number( true, 0 );
  $('#harga_satuan_rev_2').number( true, 0 );

  $('#jumlah_barang_rev_3').on('change keyup paste mouseup', function() {
    $('#harga_satuan_rev_3').val($('#nilai_barang_rev_3').val() / $('#jumlah_barang_rev_3').val());
  });

  $('#nilai_barang_rev_3').on('change keyup paste mouseup', function() {
    $('#harga_satuan_rev_3').val($('#nilai_barang_rev_3').val() / $('#jumlah_barang_rev_3').val());
  });

  $('#jumlah_barang_rev_3').number( true, 0 );
  $('#nilai_barang_rev_3').number( true, 0 );
  $('#harga_satuan_rev_3').number( true, 0 );

  function readURL(input, no) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      $('#image_preview_'+no).show();
        reader.onload = function(e) {
        $('#image_preview_'+no).attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#nama_file_adendum_1").change(function() {
    readURL(this, 1);
  });

  $("#nama_file_adendum_2").change(function() {
    readURL(this, 2);
  });
  
  $("#nama_file_adendum_3").change(function() {
    readURL(this, 3);
  });

  $(".numberFilter").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
      // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
      ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
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