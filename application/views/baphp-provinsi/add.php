<style>
.select2{
  width:100% !important;
}
</style>
<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Baphp_provinsi/store'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id_alokasi_provinsi" value="<?php echo $id_alokasi_provinsi; ?>" />
      <input type="hidden" name="tahun_anggaran" value="<?php echo $kontrak_provinsi->tahun_anggaran; ?>" />
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
        <?php if($val['column'] == 'no_bart'){ ?>
          <h3>INFORMASI TAMBAHAN</h3><hr/>
        <?php } ?>
      <div class="col_full">
        <label><?php echo $val['caption'];?></label>
        <?php if($val['column'] == 'titik_serah') { ?>
          <select name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control">
          <option value="PROVINSI">PROVINSI</option>
          <option value="KABUPATEN/KOTA">KABUPATEN/KOTA</option>
          <option value="KODIM">KODIM/KOREM</option>
        </select>
        <?php }elseif($val['column'] == 'nama_wilayah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="txt_<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_provinsi->nama_provinsi;?>" readonly />                   
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
          <input type="hidden" name="id_provinsi_penerima" id="<?php echo $val['column'];?>" value="<?php echo $alokasi_provinsi->id_provinsi;?>" />                   
          <input type="text" name="provinsi_penerima" class="form-control" value="<?php echo $alokasi_provinsi->nama_provinsi;?>" readonly />                   
        <?php }elseif($val['column'] == 'kabupaten_penerima'){ ?>
          <input type="hidden" name="id_kabupaten_penerima" id="<?php echo $val['column'];?>" value="<?php echo $alokasi_provinsi->id_kabupaten;?>" />                   
          <input type="text" name="kabupaten_penerima" class="form-control" value="<?php echo $alokasi_provinsi->nama_kabupaten;?>" readonly />                   
        <?php }elseif($val['column'] == 'nama_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_provinsi->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'merk'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_provinsi->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'jumlah_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo number_format($alokasi_provinsi->jumlah_barang,0);?>" readonly />                   
        <?php }elseif($val['column'] == 'nilai_barang'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo number_format($alokasi_provinsi->nilai_barang,0);?>" readonly />                   
        <?php }elseif($val['column'] == 'harga_satuan'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo number_format($alokasi_provinsi->{$val['column']},0);?>" readonly />                   
        <?php }elseif($val['column'] == 'no_kontrak'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $alokasi_provinsi->{$val['column']};?>" readonly />                   
        <?php }elseif($val['column'] == 'tahun_anggaran'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $kontrak_provinsi->tahun_anggaran;?>" readonly />                   
        <?php }elseif($val['column'] == 'provinsi_penyerah'){ ?>
          <select name="id_provinsi_penyerah" id="id_provinsi_penyerah" class="form-control js-example-basic-single">
          <?php 
            foreach($provinsi as $prov){
              echo "<option value=".$prov->id." ";
              echo ($prov->id == $penyedia_provinsi->id_provinsi_penyerah) ? 'selected' : '';
              echo ">".$prov->nama_provinsi."</option>";
            }
          ?>
        </select>
        <?php }elseif($val['column'] == 'kabupaten_penyerah'){ ?>
          <select name="id_kabupaten_penyerah" id="id_kabupaten_penyerah" class="form-control js-example-basic-single">
          <?php 
            foreach($kabupaten as $kab){
              echo "<option value=".$kab->id." ";
              echo ($kab->id == $penyedia_provinsi->id_kabupaten_penyerah) ? 'selected' : '';
              echo ">".$kab->nama_kabupaten."</option>";
            }
          ?>
        </select>
        <?php }elseif($val['column'] == 'pihak_penyerah'){ ?>
          <input type="hidden" name="id_penyerah" value="<?php echo $penyedia_provinsi->id;?>"/>
          <input type="text" name="nama_penyedia_provinsi" id="<?php echo $val['column'];?>" readonly class="form-control" value="<?php echo $penyedia_provinsi->nama_penyedia_provinsi;?>" />                   
        <?php }elseif($val['column'] == 'nama_penyerah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $penyedia_provinsi->{$val['column']};?>" />                   
        <?php }elseif($val['column'] == 'jabatan_penyerah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $penyedia_provinsi->{$val['column']};?>" />                   
         <?php }elseif($val['column'] == 'notelp_penyerah'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $penyedia_provinsi->{$val['column']};?>" />                   
        <?php }elseif($val['column'] == 'alamat_penyerah'){ ?>
          <textarea rows=3 name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control"><?php echo $penyedia_provinsi->{$val['column']};?></textarea>
        <?php }elseif($val['column'] == 'alamat_penerima'){ ?>
          <textarea rows=3 name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control"></textarea>
        <?php }elseif($val['column'] == 'tanggal') { ?>
          <div class="input-daterange input-group" id="datepicker">
        <input type="text" class="input-sm form-control" value="<?php echo date('d-m-Y');?>" name="tanggal" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'tanggal_bart') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="<?php echo $val['column'];?>" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'tanggal_sp2d') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="<?php echo $val['column'];?>" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'tanggal_spm') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="<?php echo $val['column'];?>" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="" />
        <?php } ?>
      </div>
      <?php } ?>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Baphp_provinsi/index?id_alokasi_provinsi=').$alokasi_provinsi->id; ?>">
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
  
      $('#titik_serah').change();
  });

  $('.input-daterange').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });

  $('#titik_serah').change(function() {
    if(this.value == 'PROVINSI'){
      $('#txt_nama_wilayah').val('<?php echo $alokasi_provinsi->nama_provinsi;?>');
      $('#txt_nama_wilayah').show();
      $('#ddl_nama_wilayah').hide();
      $('#ddlsel_nama_wilayah').prop( "disabled", true );
    }
    if(this.value == 'KABUPATEN/KOTA'){
      $('#txt_nama_wilayah').val('<?php echo $alokasi_provinsi->nama_kabupaten;?>');
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
            if(data[i]["id"] == '<?php echo $penyedia_provinsi->id_kabupaten_penyerah; ?>'){
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