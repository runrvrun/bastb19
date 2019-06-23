<link rel="stylesheet" href="<?php echo base_url('assets/css/dropzone.css'); ?> ">

<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('BAPSTHPReguler/doEdit'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $bapsthp_reguler->id; ?>" />
      <h3>INFORMASI DOKUMEN</h3>
      <hr>
      <div class="col_full">
        <label>TAHUN ANGGARAN</label>
        <input type="text" name="tahun_anggaran" id="tahun_anggaran" class="form-control" value="<?php echo $bapsthp_reguler->tahun_anggaran; ?>" readonly/>
      </div>
      <div class="col_full">
        <label>TITIK SERAH</label>
        <select name="titik_serah" id="titik_serah" class="form-control js-example-basic-single" onchange="changeNamaWilayah(this.value);" required>
          <option value="PROVINSI" <?php echo($bapsthp_reguler->titik_serah == 'PROVINSI' ? 'selected' : ''); ?> >PROVINSI</option>
          <option value="KABUPATEN/KOTA" <?php echo($bapsthp_reguler->titik_serah == 'KABUPATEN/KOTA' ? 'selected' : ''); ?> >KABUPATEN/KOTA</option>
          <option value="KODIM/KOREM" <?php echo($bapsthp_reguler->titik_serah == 'KODIM/KOREM' ? 'selected' : ''); ?> >KODIM/KOREM</option>
          <option value="PUSAT" <?php echo($bapsthp_reguler->titik_serah == 'PUSAT' ? 'selected' : ''); ?> >PUSAT</option>
        </select>
      </div>
      <div class="col_full">
        <label>NAMA <span id="wilayah"></span></label>
        <div id="divNamaWilayah">
          <select name="nama_wilayah" id="nama_wilayah" class="form-control js-example-basic-single" required>
          </select>
        </div>
        <input type="text" name="txt_nama_wilayah" id="txt_nama_wilayah" class="form-control" required value="<?php echo $bapsthp_reguler->nama_wilayah; ?>" />
      </div>
      <div class="col_full">
        <label>NO BAP-STHP</label>
        <input type="text" name="no_bapsthp" id="no_bapsthp" class="form-control" required value="<?php echo $bapsthp_reguler->no_bapsthp; ?>" />
      </div>
      <div class="col_full">
        <label>TANGGAL BAP-STHP</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal" id="tanggal" required value="<?php echo date('d-m-Y', strtotime($bapsthp_reguler->tanggal)); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <hr>
      <h3>DATA PARA PIHAK</h3>
      <hr>
      <div class="col_full">
        <label>PIHAK YANG MENYERAHKAN</label>
        <select name="id_penyerah" id="id_penyerah" class="form-control js-example-basic-single" required>
        <?php 
          foreach($penyedia_pusat as $penyedia){
            echo "<option value=".$penyedia->id."  ".($penyedia->id == $bapsthp_reguler->id_penyerah ? 'selected' : '')." >".$penyedia->nama_penyedia_pusat."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penyerah" id="nama_penyerah" class="form-control" required value="<?php echo $bapsthp_reguler->nama_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penyerah" id="jabatan_penyerah" class="form-control" required value="<?php echo $bapsthp_reguler->jabatan_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>NO TELEPON</label>
        <input type="text" name="notelp_penyerah" id="notelp_penyerah" class="form-control" required value="<?php echo $bapsthp_reguler->notelp_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>ALAMAT</label>
        <textarea name="alamat_penyerah" id="alamat_penyerah" class="form-control" required><?php echo $bapsthp_reguler->alamat_penyerah; ?></textarea>
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi_penyerah" id="id_provinsi_penyerah" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $bapsthp_reguler->id_provinsi_penyerah ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
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
        <input type="text" name="pihak_penerima" id="pihak_penerima" class="form-control" required value="<?php echo $bapsthp_reguler->pihak_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penerima" id="nama_penerima" class="form-control" required value="<?php echo $bapsthp_reguler->nama_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penerima" id="jabatan_penerima" class="form-control" required value="<?php echo $bapsthp_reguler->jabatan_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>NO TELEPON</label>
        <input type="text" name="notelp_penerima" id="notelp_penerima" class="form-control" required value="<?php echo $bapsthp_reguler->notelp_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>ALAMAT</label>
        <textarea name="alamat_penerima" id="alamat_penerima" class="form-control" required><?php echo $bapsthp_reguler->alamat_penerima; ?></textarea>
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi_penerima" id="id_provinsi_penerima" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $bapsthp_reguler->id_provinsi_penerima ? 'selected' : '').">".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KABUPATEN/KOTA</label>
        <select name="id_kabupaten_penerima" id="id_kabupaten_penerima" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <hr>
      <h3>DETAIL BARANG</h3>
      <hr>
      <div class="col_full">
        <label>NAMA BARANG</label>
        <select name="nama_barang" id="nama_barang" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
        <label>MERK</label>
        <select name="merk" id="merk" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG</label>
        <input type="text" name="jumlah_barang" id="jumlah_barang" class="form-control numberFilter" required value="<?php echo $bapsthp_reguler->jumlah_barang; ?>" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP)</label>
        <input type="text" name="nilai_barang" id="nilai_barang" class="form-control numberFilter" required value="<?php echo $bapsthp_reguler->nilai_barang; ?>" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP)</label>
        <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" readonly value="<?php echo ($bapsthp_reguler->nilai_barang / $bapsthp_reguler->jumlah_barang); ?>" />
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
        <input type="text" name="nama_mengetahui" id="nama_mengetahui" class="form-control" required value="<?php echo $bapsthp_reguler->nama_mengetahui; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_mengetahui" id="jabatan_mengetahui" class="form-control" required value="<?php echo $bapsthp_reguler->jabatan_mengetahui; ?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('BAPSTHPReguler'); ?>">
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
  function changeNamaWilayah(val){
    $('#wilayah').html(val);

    if(val == 'PUSAT'){
      // $('#txt_nama_wilayah').val("");
      $('#txt_nama_wilayah').show();
      $('#divNamaWilayah').hide();

    }
    else{
      $('#txt_nama_wilayah').hide();
      $('#divNamaWilayah').show();
      LoadNamaWilayah(val);
    }
  }

  function LoadNamaWilayah(val){
    if(val == 'PROVINSI'){
      $.ajax({
        url: "<?php echo base_url('Provinsi/AjaxGetAll'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {},
        success: function(data) {
            $('#nama_wilayah').empty();
            for(var i=0;i<data.length;i++)
            {
              if(data[i]["nama_provinsi"] == '<?php echo $bapsthp_reguler->nama_wilayah; ?>'){
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_provinsi"]+"' selected>"+data[i]["nama_provinsi"]+"</option>"
                );
              }
              else{
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_provinsi"]+"'>"+data[i]["nama_provinsi"]+"</option>"
                );
              }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
      });
    }
    else if(val == 'KABUPATEN/KOTA'){
      $.ajax({
        url: "<?php echo base_url('Kabupaten/AjaxGetAll'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {},
        success: function(data) {
            $('#nama_wilayah').empty();
            for(var i=0;i<data.length;i++)
            {
              if(data[i]["nama_kabupaten"] == '<?php echo $bapsthp_reguler->nama_wilayah; ?>'){
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_kabupaten"]+"' selected>"+data[i]["nama_kabupaten"]+"</option>"
                );
              }
              else{
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_kabupaten"]+"'>"+data[i]["nama_kabupaten"]+"</option>"
                );
              }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
      });
    }
    else if(val == 'KODIM/KOREM'){
      $.ajax({
        url: "<?php echo base_url('KodimKorem/AjaxGetAll'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {},
        success: function(data) {
            $('#nama_wilayah').empty();
            for(var i=0;i<data.length;i++)
            {
              if(data[i]["nama_kodim_korem"] == '<?php echo $bapsthp_reguler->nama_wilayah; ?>'){
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_kodim_korem"]+"' selected>"+data[i]["nama_kodim_korem"]+"</option>"
                );
              }
              else{
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_kodim_korem"]+"'>"+data[i]["nama_kodim_korem"]+"</option>"
                );
              }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
      });
    }
  }

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
            if(data[i]["id"] == '<?php echo $bapsthp_reguler->id_kabupaten_penyerah; ?>'){
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
            if(data[i]["id"] == '<?php echo $bapsthp_reguler->id_kabupaten_penerima; ?>'){
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
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }
  
  $(document).ready(function() {
    LoadBarang();
    changeNamaWilayah('<?php echo $bapsthp_reguler->titik_serah; ?>');

    // $('#txt_nama_wilayah').hide();

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

  $('#id_provinsi_penyerah').change(function() {
    LoadKabupatenPenyerah();
  });

  $('#id_provinsi_penerima').change(function() {
    LoadKabupatenPenerima();
  });

  function LoadBarang(){
    $.ajax({
      url: "<?php echo base_url('JenisBarangPusat/GetByPenyedia'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_penyedia_pusat : $('#id_penyerah').val()},
      success: function(data) {
          $('#nama_barang').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["nama_barang"] == '<?php echo $bapsthp_reguler->nama_barang; ?>'){
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
      data: {nama_barang : $('#nama_barang').val(), id_penyedia_pusat : $('#id_penyerah').val()},
      success: function(data) {
          // $('#merk').val(data);
          $('#merk').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["merk"] == '<?php echo $bapsthp_reguler->merk; ?>'){
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
            if(data[i]["no_kontrak"] == '<?php echo $bapsthp_reguler->no_kontrak; ?>'){
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