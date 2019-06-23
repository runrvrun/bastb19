<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('KontrakPusat/doAddDetail'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id_kontrak_pusat" value="<?php echo $kontrak_pusat->id; ?>" />
      <h3>TAMBAH DATA KONTRAK DETAIL PUSAT</h3>
      <hr>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" id="id_provinsi" class="form-control js-example-basic-single">
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id.">".$prov->nama_provinsi."</option>";
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
        <input type="text" name="jumlah_barang_detail" id="jumlah_barang_detail" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP)</label>
        <input type="text" name="nilai_barang_detail" id="nilai_barang_detail" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP)</label>
        <input type="text" name="harga_satuan_detail" id="harga_satuan_detail" class="form-control" readonly />
      </div>
      <div class="col_full">
        <label>REG/CAD</label>
        <select name="regcad" class="form-control">
          <option value="REGULER" selected>REGULER</option>
          <option value="CADANGAN">CADANGAN</option>
        </select>
      </div>
      <div class="col_full">
        <label>ID</label>
        <select name="dinas" class="form-control">
          <option value="BKB">BKB</option>
          <option value="DINAS" selected>DINAS</option>
          <option value="DISBUN">DISBUN</option>          
          <option value="KODIM">KODIM</option>
          <option value="KOREM">KOREM</option>
          <option value="LAPAS">LAPAS</option>
          <option value="PUSAT">PUSAT</option>
        </select>
      </div>
      <h3>DATA ADENDUM 1</h3>
      <div class="col_full">
        <label>NO ADENDUM 1</label>
        <input type="text" name="no_adendum_1" id="no_adendum_1" class="form-control" />
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG REV. 1</label>
        <input type="text" name="jumlah_barang_rev_1" id="jumlah_barang_rev_1" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP) REV. 1</label>
        <input type="text" name="nilai_barang_rev_1" id="nilai_barang_rev_1" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP) REV. 1</label>
        <input type="text" name="harga_satuan_rev_1" id="harga_satuan_rev_1" class="form-control" readonly />
      </div>
      <h3>DATA ADENDUM 2</h3>
      <div class="col_full">
        <label>NO ADENDUM 2</label>
        <input type="text" name="no_adendum_2" id="no_adendum_2" class="form-control" />
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG REV. 2</label>
        <input type="text" name="jumlah_barang_rev_2" id="jumlah_barang_rev_2" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP) REV. 2</label>
        <input type="text" name="nilai_barang_rev_2" id="nilai_barang_rev_2" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP) REV. 2</label>
        <input type="text" name="harga_satuan_rev_2" id="harga_satuan_rev_2" class="form-control" readonly />
      </div>
      <h3>KETERANGAN ALOKASI</h3>
      <div class="col_full">
        <label>STATUS ALOKASI</label>
        <select name="status_alokasi" class="form-control">
          <option value="MENUNGGU KONFIRMASI" selected>MENUNGGU KONFIRMASI</option>
          <option value="DATA KONTRAK AWAL">DATA KONTRAK AWAL</option>
          <option value="DATA ADENDUM 1">DATA ADENDUM 1</option>
          <option value="DATA ADENDUM 2">DATA ADENDUM 2</option>
        </select>
      </div>
      <!-- <h3>UPLOAD FOTO/DOKUMEN ADENDUM</h3>
      <div class="col_full">
        <label>ADENDUM 1</label>
        <input type='file' name="nama_file_adendum_1" id="nama_file_adendum_1" accept="image/*" />
        <img id="image_preview_1" src="#" alt="Preview Image" width="250px" />
      </div>
      <div class="col_full">
        <label>ADENDUM 2</label>
        <input type='file' name="nama_file_adendum_2" id="nama_file_adendum_2" accept="image/*" />
        <img id="image_preview_2" src="#" alt="Preview Image" width="250px" />
      </div> -->
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('KontrakPusat/ShowDetail?id=').$kontrak_pusat->id; ?>">
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
            $('#id_kabupaten').append($('<option></option>').attr('value', data[i]["id"]).text(data[i]["nama_kabupaten"]));
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }
  

  $('#jumlah_barang_detail').on('change keyup paste mouseup', function() {
    $('#harga_satuan_detail').val($('#nilai_barang_detail').val() / $('#jumlah_barang_detail').val());
  });

  $('#nilai_barang_detail').on('change keyup paste mouseup', function() {
    $('#harga_satuan_detail').val($('#nilai_barang_detail').val() / $('#jumlah_barang_detail').val());
  });

  $('#jumlah_barang_detail').number( true, 0 );
  $('#nilai_barang_detail').number( true, 0 );
  $('#harga_satuan_detail').number( true, 0 );

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