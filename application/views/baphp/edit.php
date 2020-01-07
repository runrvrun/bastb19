<style>
.select2{
  width:100% !important;
}
</style>
<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Baphp/update'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $baphp->id; ?>" />
      <input type="hidden" name="regcad" value="<?php echo $alokasi_pusat->regcad; ?>" />
      <h3>INFORMASI DOKUMEN</h3><hr/>
      <?php foreach($cols as $key=>$val){ ?>
        <?php 
          //skip ba titip if ID/Dinas not PUSAT
          if($alokasi_pusat->dinas != 'PUSAT'){
            if(in_array($val['column'],array('no_batitip','tanggal_batitip'))){
              continue;
            }
          }
        ?>
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
        <?php if($val['column'] == 'titik_serah') { ?>
          <select name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control">
          <option value="PROVINSI" <?php echo ($baphp->titik_serah == 'PROVINSI')? 'selected':'';?>>PROVINSI</option>
          <option value="KABUPATEN/KOTA" <?php echo ($baphp->titik_serah == 'KABUPATEN/KOTA')? 'selected':'';?>>KABUPATEN/KOTA</option>
          <option value="KODIM" <?php echo ($baphp->titik_serah == 'KODIM')? 'selected':'';?>>KODIM/KOREM</option>
          <option value="PUSAT" <?php echo ($baphp->titik_serah == 'PUSAT')? 'selected':'';?>>PUSAT</option>
        </select>
        <?php }elseif($val['column'] == 'nama_wilayah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="txt_<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_pusat->nama_provinsi;?>" readonly />                   
          <div id="ddl_<?php echo $val['column'];?>">
          <select name="<?php echo $val['column'];?>" id="ddlsel_<?php echo $val['column'];?>" class="form-control js-example-basic-single">
          <?php 
            foreach($kodim as $kod){
              echo "<option value=".$kod->nama_kodim_korem.">".$kod->nama_kodim_korem."</option>";
            }
          ?>
          </select>
          </div>
        <?php }elseif($val['column'] == 'provinsi_penerima'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_pusat->nama_provinsi;?>" readonly />                   
        <?php }elseif($val['column'] == 'kabupaten_penerima'){ ?>
          <input type="hidden" name="id_kabupaten_penerima" value="<?php echo $alokasi_pusat->id_kabupaten;?>" />
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_pusat->nama_kabupaten;?>" readonly />                   
        <?php }elseif($val['column'] == 'nama_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_pusat->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'merk'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo htmlentities($alokasi_pusat->{$val['column']});?>" readonly />                   
        <?php }elseif($val['column'] == 'jumlah_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value=<?php echo number_format($alokasi_pusat->jumlah_barang_rev,0);?> readonly />                   
        <?php }elseif($val['column'] == 'nilai_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value=<?php echo number_format($alokasi_pusat->nilai_barang_rev,0);?> readonly />                   
        <?php }elseif($val['column'] == 'harga_satuan'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value=<?php echo number_format($alokasi_pusat->harga_satuan_rev,0);?> readonly />                   
        <?php }elseif($val['column'] == 'no_kontrak'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_pusat->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'tahun_anggaran'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value=<?php echo $kontrak_pusat->tahun_anggaran;?> readonly />                   
        <?php }elseif($val['column'] == 'pihak_penyerah'){ ?>
          <input type="text" name="nama_penyedia_pusat" id="<?php echo $val['column'];?>" readonly class="form-control" value="<?php echo $baphp->nama_penyedia_pusat;?>" />                   
        <?php }elseif($val['column'] == 'provinsi_penyerah'){ ?>
          <select name="id_provinsi_penyerah" id="id_provinsi_penyerah" class="form-control js-example-basic-single">
          <?php 
            foreach($provinsi as $prov){
              echo "<option value=".$prov->id." ".($prov->id == $baphp->id_provinsi_penyerah ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
            }
          ?>
        </select>
        <?php }elseif($val['column'] == 'kabupaten_penyerah'){ ?>
          <select name="id_kabupaten_penyerah" id="id_kabupaten_penyerah" class="form-control js-example-basic-single">
          <?php 
            foreach($kabupaten as $kab){
              echo "<option value=".$kab->id.">".$kab->nama_kabupaten."</option>";
            }
          ?>
        </select>
        <?php }elseif($val['column'] == 'nama_penyerah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $baphp->{$val['column']};?>" />                   
        <?php }elseif($val['column'] == 'jabatan_penyerah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $baphp->{$val['column']};?>" />                   
         <?php }elseif($val['column'] == 'notelp_penyerah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $baphp->{$val['column']};?>" />                   
        <?php }elseif($val['column'] == 'alamat_penyerah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $baphp->{$val['column']};?>" />                   
        <?php }elseif($val['column'] == 'alamat_penerima'){ ?>
          <textarea rows=3 name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control"><?php echo $baphp->{$val['column']};?></textarea>
        <?php }elseif($val['column'] == 'tanggal') { ?>
          <div class="input-daterange input-group" id="datepicker2">
          <input type="text" class="input-sm form-control" name="tanggal" autocomplete="off" value="<?php echo ($baphp->tanggal > '1900-01-01')? date('d-m-Y', strtotime($baphp->tanggal)):''; ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'tanggal_bart') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="<?php echo $val['column'];?>" autocomplete="off" value="<?php echo ($baphp->{$val['column']} > '1900-01-01')? date('d-m-Y', strtotime($baphp->{$val['column']})):''; ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'tanggal_batitip') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_batitip" autocomplete="off" value="<?php echo ($baphp->tanggal_batitip > '1900-01-01')? date('d-m-Y', strtotime($baphp->tanggal_batitip)):''; ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $baphp->{$val['column']} ?>" />
        <?php } ?>
      </div>
      <?php } ?>
      <div class="col_full nobottommargin">
        <!-- <a href="<?php echo base_url('Baphp/index?id_alokasi=').$alokasi_pusat->id; ?>"> -->
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

      $('#image_preview_1').hide();
      $('#image_preview_2').hide();

      $('#titik_serah').change();
  });

  $('.input-daterange').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });

  $('#titik_serah').change(function() {
    if(this.value == 'PROVINSI'){
      $('#txt_nama_wilayah').val('<?php echo $alokasi_pusat->nama_provinsi;?>');
      $('#txt_nama_wilayah').show();
      $('#ddl_nama_wilayah').hide();
      $('#ddlsel_nama_wilayah').prop( "disabled", true );
    }
    if(this.value == 'KABUPATEN/KOTA'){
      $('#txt_nama_wilayah').val('<?php echo $alokasi_pusat->nama_kabupaten;?>');
      $('#txt_nama_wilayah').show();
      $('#ddl_nama_wilayah').hide();
      $('#ddlsel_nama_wilayah').prop( "disabled", true );
    }
    if(this.value == 'PUSAT'){
      $('#txt_nama_wilayah').val('DIREKTORAT ALAT DAN MESIN PERTANIAN');
      $('#txt_nama_wilayah').show();
      $('#ddl_nama_wilayah').hide();
      $('#ddlsel_nama_wilayah').prop( "disabled", true );
    }
    if(this.value == 'KODIM'){
      $('#txt_nama_wilayah').hide();
      $('#ddlsel_nama_wilayah').prop( "disabled", false );
      $('#ddl_nama_wilayah').show();
    }
  });

  $('#id_provinsi_penyerah').change(function() {
      LoadKabupaten();
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
            if(data[i]["id"] == '<?php echo $baphp->id_kabupaten_penyerah; ?>'){
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