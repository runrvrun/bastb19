<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Bastb_persediaan/update'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $bastb_persediaan->id; ?>" />
      <h3>INFORMASI DOKUMEN</h3><hr/>
      <?php foreach($cols as $key=>$val){ ?>
        <?php if($val['column'] == 'pihak_penyerah'){ ?>
          <h3>DATA PARA PIHAK</h3><hr/>
        <?php } ?>
        <?php if($val['column'] == 'nama_barang'){ ?>
          <h3>DETAIL BARANG</h3><hr/>
        <?php } ?>
        <?php if($val['column'] == 'no_kontrak'){ ?>
          <h3>INFORMASI KONTRAK</h3><hr/>
        <?php } ?>
        <?php if($val['column'] == 'nama_mengetahui'){ ?>
          <h3>PIHAK MENGETAHUI</h3><hr/>
        <?php } ?>
      <div class="col_full">
        <label><?php echo $val['caption'];?></label>
        <?php if($val['column'] == 'kelompok_penerima'){ ?>
          <select class="form-control" name="kelompok_penerima">
            <option value="POKTAN" <?php echo ($bastb_persediaan->kelompok_penerima == "POKTAN")? 'selected':'';?>>POKTAN</option>
            <option value="GAPOKTAN" <?php echo ($bastb_persediaan->kelompok_penerima == "GAPOKTAN")? 'selected':'';?>>GAPOKTAN</option>
            <option value="UPJA" <?php echo ($bastb_persediaan->kelompok_penerima == "UPJA")? 'selected':'';?>>UPJA</option>
            <option value="BRIGADE" <?php echo ($bastb_persediaan->kelompok_penerima == "BRIGADE")? 'selected':'';?>>BRIGADE</option>
            <option value="LAINNYA" <?php echo ($bastb_persediaan->kelompok_penerima == "LAINNYA")? 'selected':'';?>>LAINNYA</option>
          </select>
        <?php }elseif($val['column'] == 'tanggal') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal" value="<?php echo ($bastb_persediaan->tanggal > '1900-01-01')? date('d-m-Y', strtotime($bastb_persediaan->tanggal)):''; ?>" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'tahun_anggaran'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $kontrak_pusat->tahun_anggaran;?>" readonly />                   
        <?php }elseif($val['column'] == 'provinsi_penerima'){ ?>
          <input type="hidden" name="id_provinsi_penerima" id="id_provinsi_penerima" value="<?php echo $bastb_persediaan->id_provinsi_penerima;?>" />                   
          <input type="text" class="form-control" value="<?php echo $bastb_persediaan->provinsi_penerima;?>" readonly />                   
        <?php }elseif($val['column'] == 'kabupaten_penerima'){ ?>
          <select id="id_kabupaten_penerima" name="id_kabupaten_penerima" class="form-control js-example-basic-single" <?php echo ($this->session->userdata('logged_in')->role_pengguna == 'ADMIN KABUPATEN')? 'disabled':'';?>>
          </select>
        <?php }elseif($val['column'] == 'nama_kecamatan'){ ?>
          <select name="id_kecamatan_penerima" id="id_kecamatan_penerima" class="form-control js-example-basic-single">
          </select>
        <?php }elseif($val['column'] == 'nama_kelurahan'){ ?>
          <select name="id_kelurahan_penerima" id="id_kelurahan_penerima" class="form-control js-example-basic-single">
          </select>
        <?php }elseif($val['column'] == 'nama_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $bastb_persediaan->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'merk'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $bastb_persediaan->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'nilai_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value=<?php echo number_format($bastb_persediaan->nilai_barang,0);?> readonly />                   
        <?php }elseif($val['column'] == 'harga_satuan'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value=<?php echo number_format($alokasi_pusat->harga_satuan,0);?> readonly />                   
        <?php }elseif($val['column'] == 'no_kontrak'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $bastb_persediaan->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'alamat_penyerah'){ ?>
          <textarea rows=3 name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control"><?php echo $bastb_persediaan->{$val['column']};?></textarea>                   
        <?php }elseif($val['column'] == 'alamat_penerima'){ ?>
          <textarea rows=3 name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control"><?php echo $bastb_persediaan->{$val['column']};?></textarea>                   
        <?php }elseif($val['column'] == 'provinsi_penyerah'){ ?>
          <input type="hidden" name="id_provinsi_penyerah" value="<?php echo $bastb_persediaan->id_provinsi_penyerah;?>"/>
          <select id="id_provinsi_penyerah" class="form-control js-example-basic-single" disabled>
          <?php 
            foreach($provinsi as $prov){
              echo "<option value=".$prov->id." ".($prov->id == $bastb_persediaan->id_provinsi_penyerah ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
            }
          ?>
        </select>
        <?php }elseif($val['column'] == 'kabupaten_penyerah'){ ?>
          <input type="hidden" name="id_kabupaten_penyerah" value="<?php echo $alokasi_pusat->id_kabupaten;?>"/>
          <select id="id_kabupaten_penyerah" class="form-control js-example-basic-single" disabled>
          </select>
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $bastb_persediaan->{$val['column']} ?>" />
        <?php } ?>
      </div>
      <?php } ?>
      <div class="col_full nobottommargin">
        <!-- <a href="<?php echo base_url('Bastb_persediaan/index?id_alokasi_persediaan=').$alokasi_persediaan_pusat->id; ?>"> -->
        <a href="#" onclick="window.history.back();">
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
      LoadKabupaten2();
      $('#jumlah_barang').change();
  });

  $('.input-daterange').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });

  $('#id_provinsi_penyerah').change(function() {
      LoadKabupaten();
  });

  $('#id_kecamatan_penerima').change(function() {
      LoadKelurahan();
  });

  function LoadKabupaten()
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
            if(data[i]["id"] == '<?php echo $bastb_persediaan->id_kabupaten_penyerah; ?>'){
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
  function LoadKabupaten2()
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
            if(data[i]["id"] == '<?php echo $bastb_persediaan->id_kabupaten_penerima; ?>'){
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
          LoadKecamatan();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }

  function LoadKecamatan()
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
            if(data[i]["id"] == '<?php echo $bastb_persediaan->id_kecamatan_penerima; ?>'){
              $('#id_kecamatan_penerima').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kecamatan"]+"</option>"
              );
            }else{
              $('#id_kecamatan_penerima').append(
                "<option value='"+data[i]["id"]+"'>"+data[i]["nama_kecamatan"]+"</option>"
              );
            }
          }
          LoadKelurahan();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }

  function LoadKelurahan()
  {
    $.ajax({
      url: "<?php echo base_url('Kelurahan/GetByKecamatan'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_provinsi : $('#id_provinsi_penerima').val(), id_kabupaten : $('#id_kabupaten_penerima').val(),id_kecamatan : $('#id_kecamatan_penerima').val()},
      success: function(data) {
          $('#id_kelurahan_penerima').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo (empty($bastb_persediaan->id_kelurahan_penerima))? 0:$bastb_persediaan->id_kelurahan_penerima; ?>'){
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
  
  $('#jumlah_barang').on('change keyup paste mouseup', function() {
    $('#nilai_barang').val($('#harga_satuan').val().replace(/,/g,'') * $('#jumlah_barang').val().replace(/,/g,''));
  });
  $('#nilai_barang').number( true, 0 );

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