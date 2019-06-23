<link rel="stylesheet" href="<?php echo base_url('assets/css/dropzone.css'); ?> ">

<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('BASTBPusat/doEdit'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $bastb_pusat->id; ?>">
      <h3>INFORMASI DOKUMEN</h3>
      <hr>
      <div class="col_full">
        <label>TAHUN ANGGARAN</label>
        <input type="text" name="tahun_anggaran" id="tahun_anggaran" class="form-control" required value="<?php echo $bastb_pusat->tahun_anggaran; ?>" readonly/>
      </div>
      <div class="col_full">
        <label>KELOMPOK PENERIMA</label>
        <select name="kelompok_penerima" id="kelompok_penerima" class="form-control js-example-basic-single" required>
          <option value="POKTAN" <?php echo($bastb_pusat->kelompok_penerima == 'POKTAN' ? 'selected' : ''); ?> >POKTAN</option>
          <option value="GAPOKTAN" <?php echo($bastb_pusat->kelompok_penerima == 'GAPOKTAN' ? 'selected' : ''); ?> >GAPOKTAN</option>
          <option value="UPJA" <?php echo($bastb_pusat->kelompok_penerima == 'UPJA' ? 'selected' : ''); ?> >UPJA</option>
          <option value="BRIGADE" <?php echo($bastb_pusat->kelompok_penerima == 'BRIGADE' ? 'selected' : ''); ?> >BRIGADE</option>
          <option value="LAINNYA" <?php echo($bastb_pusat->kelompok_penerima == 'LAINNYA' ? 'selected' : ''); ?> >LAINNYA</option>
        </select>
      </div>
      <div class="col_full">
        <label>NO BASTB</label>
        <input type="text" name="no_bastb" id="no_bastb" class="form-control" value="<?php echo $bastb_pusat->no_bastb; ?>" required />
      </div>
      <div class="col_full">
        <label>TANGGAL BASTB</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal" id="tanggal" required value="<?php echo date('d-m-Y', strtotime($bastb_pusat->tanggal)); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <hr>
      <h3>DATA PARA PIHAK</h3>
      <hr>
      <div class="col_full">
        <label>PIHAK YANG MENYERAHKAN</label>
        <input type="text" name="pihak_penyerah" id="pihak_penyerah" class="form-control" required value="<?php echo $bastb_pusat->pihak_penyerah; ?>" />
        </select>
      </div>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penyerah" id="nama_penyerah" class="form-control" required value="<?php echo $bastb_pusat->nama_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penyerah" id="jabatan_penyerah" class="form-control" required value="<?php echo $bastb_pusat->jabatan_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>NO TELEPON</label>
        <input type="text" name="notelp_penyerah" id="notelp_penyerah" class="form-control" required value="<?php echo $bastb_pusat->notelp_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>ALAMAT</label>
        <textarea name="alamat_penyerah" id="alamat_penyerah" class="form-control" required><?php echo $bastb_pusat->alamat_penyerah; ?></textarea>
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi_penyerah" id="id_provinsi_penyerah" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $bastb_pusat->id_provinsi_penyerah ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KABUPATEN/KOTA</label>
        <select name="id_kabupaten_penyerah" id="id_kabupaten_penyerah" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <hr>
      <div class="col_full">
        <label>PIHAK YANG MENERIMA</label>
        <input type="text" name="pihak_penerima" id="pihak_penerima" class="form-control" required value="<?php echo $bastb_pusat->pihak_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penerima" id="nama_penerima" class="form-control" required value="<?php echo $bastb_pusat->nama_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penerima" id="jabatan_penerima" class="form-control" required value="<?php echo $bastb_pusat->jabatan_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>NO TELEPON</label>
        <input type="text" name="notelp_penerima" id="notelp_penerima" class="form-control" required value="<?php echo $bastb_pusat->notelp_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>ALAMAT</label>
        <textarea name="alamat_penerima" id="alamat_penerima" class="form-control" required><?php echo $bastb_pusat->alamat_penerima; ?></textarea>
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi_penerima" id="id_provinsi_penerima" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $bastb_pusat->id_provinsi_penerima ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KABUPATEN/KOTA</label>
        <select name="id_kabupaten_penerima" id="id_kabupaten_penerima" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
        <label>KECAMATAN</label>
        <select name="id_kecamatan_penerima" id="id_kecamatan_penerima" class="form-control js-example-basic-single" >
        </select>
      </div>
      <div class="col_full">
        <label>KELURAHAN/DESA</label>
        <select name="id_kelurahan_penerima" id="id_kelurahan_penerima" class="form-control js-example-basic-single" >
        </select>
      </div>
      <hr>
      <h3>DETAIL BARANG</h3>
      <hr>
      <div class="col_full">
        <label>NAMA BARANG</label>
        <select name="nama_barang" id="nama_barang" class="form-control js-example-basic-single" required value="<?php echo $bastb_pusat->nama_barang; ?>">
        </select>
      </div>
      <div class="col_full">
        <label>MERK</label>
        <select name="merk" id="merk" class="form-control js-example-basic-single" required value="<?php echo $bastb_pusat->merk; ?>">
        </select>
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG</label>
        <input type="text" name="jumlah_barang" id="jumlah_barang" class="form-control numberFilter" required value="<?php echo $bastb_pusat->jumlah_barang; ?>" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP)</label>
        <input type="text" name="nilai_barang" id="nilai_barang" class="form-control numberFilter" required value="<?php echo $bastb_pusat->nilai_barang; ?>" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP)</label>
        <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" readonly value="<?php echo($bastb_pusat->nilai_barang / $bastb_pusat->jumlah_barang); ?>" />
      </div>
      <hr>
      <h3>INFORMASI KONTRAK</h3>
      <hr>
      <div class="col_full">
        <label>REFERENSI NO KONTRAK</label>
        <select name="no_kontrak" id="no_kontrak" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <hr>
      <h3>PIHAK MENGETAHUI</h3>
      <hr>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_mengetahui" id="nama_mengetahui" class="form-control" required value="<?php echo $bastb_pusat->nama_mengetahui; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_mengetahui" id="jabatan_mengetahui" class="form-control" required value="<?php echo $bastb_pusat->jabatan_mengetahui; ?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('BASTBPusat'); ?>">
          <button type="button" class="button button-3d button-white button-light nomargin mr10">
              Cancel
          </button>
        </a>
        <button type="submit" id="submit-all" class="button button-3d nomargin">Save</button>
      </div>
    </form>
  </div>
</div>

<script> 

  function LoadKabupatenPenyerah()
  {
    $.ajax({
      url: "<?php echo base_url('Kabupaten/GetByProvinsi'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_provinsi : $('#id_provinsi_penyerah').val()},
      success: function(data) {
          $('#id_kabupaten_penyerah').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo $bastb_pusat->id_kabupaten_penyerah; ?>'){
              $('#id_kabupaten_penyerah').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kabupaten"]+"</option>"
              );
            }
            else{
              $('#id_kabupaten_penyerah').append(
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

  function LoadKabupatenPenerima()
  {
    $.ajax({
      url: "<?php echo base_url('Kabupaten/GetByProvinsi'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_provinsi : $('#id_provinsi_penerima').val()},
      success: function(data) {
          $('#id_kabupaten_penerima').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo $bastb_pusat->id_kabupaten_penerima; ?>'){
              $('#id_kabupaten_penerima').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kabupaten"]+"</option>"
              );
            }
            else{
              $('#id_kabupaten_penerima').append(
                "<option value='"+data[i]["id"]+"'>"+data[i]["nama_kabupaten"]+"</option>"
              );
            }
          }
          LoadKecamatanPenerima();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }

  function LoadKecamatanPenerima()
  {
    $.ajax({
      url: "<?php echo base_url('Kecamatan/GetByKabupaten'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_provinsi : $('#id_provinsi_penerima').val(), id_kabupaten : $('#id_kabupaten_penerima').val()},
      success: function(data) {
          $('#id_kecamatan_penerima').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo $bastb_pusat->id_kecamatan_penerima; ?>'){
              $('#id_kecamatan_penerima').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kecamatan"]+"</option>"
              );
            }
            else{
              $('#id_kecamatan_penerima').append(
                "<option value='"+data[i]["id"]+"'>"+data[i]["nama_kecamatan"]+"</option>"
              );
            }
          }
          LoadKelurahanPenerima();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }

  function LoadKelurahanPenerima()
  {
    $.ajax({
      url: "<?php echo base_url('Kelurahan/GetByKecamatan'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_provinsi : $('#id_provinsi_penerima').val(), id_kabupaten : $('#id_kabupaten_penerima').val(), id_kecamatan : $('#id_kecamatan_penerima').val()},
      success: function(data) {
          $('#id_kelurahan_penerima').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo $bastb_pusat->id_kelurahan_penerima; ?>'){
              $('#id_kelurahan_penerima').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kelurahan"]+"</option>"
              );
            }
            else{
              $('#id_kelurahan_penerima').append(
                "<option value='"+data[i]["id"]+"'>"+data[i]["nama_kelurahan"]+"</option>"
              );
            }
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }
  
  $(document).ready(function() {
    LoadBarang();

    $('#image_preview').hide();

    LoadKabupatenPenyerah();
    LoadKabupatenPenerima();

  });

  $('.input-daterange').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });

  $('#id_penyerah').change(function() {
    LoadBarang()
  });

  $('#nama_barang').change(function() {
    LoadMerk();
  });

  $('#merk').change(function() {
    LoadKontrak();
  });

  $('#id_provinsi_penyerah').change(function() {
    LoadKabupatenPenyerah();
  });

  $('#id_provinsi_penerima').change(function() {
    LoadKabupatenPenerima();
  });

  $('#id_kabupaten_penerima').change(function() {
    LoadKecamatanPenerima();
  });

  $('#id_kecamatan_penerima').change(function() {
    LoadKelurahanPenerima();
  });

  function LoadBarang(){
    $.ajax({
      url: "<?php echo base_url('JenisBarangPusat/GetByPenyedia'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_penyedia_pusat : 'all'},
      success: function(data) {
          $('#nama_barang').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["nama_barang"] == '<?php echo $bastb_pusat->nama_barang; ?>'){
              $('#nama_barang').append(
                "<option value='"+data[i]["nama_barang"]+"' selected>"+data[i]["nama_barang"]+"</option>"
              );
            }
            else{
              $('#nama_barang').append(
                "<option value='"+data[i]["nama_barang"]+"'>"+data[i]["nama_barang"]+"</option>"
              );
            }
          }
          LoadMerk();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }

  function LoadMerk(){
    $.ajax({
      url: "<?php echo base_url('JenisBarangPusat/GetMerk'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {nama_barang : $('#nama_barang').val(), id_penyedia_pusat : 'all'},
      success: function(data) {
          // $('#merk').val(data);
          $('#merk').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["merk"] == '<?php echo $bastb_pusat->merk; ?>'){
              $('#merk').append(
                "<option value='"+data[i]["merk"]+"' selected>"+data[i]["merk"]+"</option>"
              );
            }
            else{
              $('#merk').append(
                "<option value='"+data[i]["merk"]+"'>"+data[i]["merk"]+"</option>"
              );
            }
          }
          LoadKontrak();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert("Data Not Found");
      }
    });
  }

  function LoadKontrak(){
    $.ajax({
      url: "<?php echo base_url('KontrakPusat/GetKontrakByBarang'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {nama_barang : $('#nama_barang').val(), merk : $('#merk').val()},
      success: function(data) {
          $('#no_kontrak').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["no_kontrak"] == '<?php echo $bastb_pusat->no_kontrak; ?>'){
              $('#no_kontrak').append(
                "<option value='"+data[i]["no_kontrak"]+"' selected>"+data[i]["no_kontrak"]+"</option>"
              );
            }
            else{
              $('#no_kontrak').append(
                "<option value='"+data[i]["no_kontrak"]+"'>"+data[i]["no_kontrak"]+"</option>"
              );
            }
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert("Data Not Found");
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

  function addCommas(nStr) {
      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }

  function readURL(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      $('#image_preview').show();
      reader.onload = function(e) {
        $('#image_preview').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#image_upload").change(function() {
    readURL(this);
  });

  $(".numberFilter").keydown(function (e) {
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