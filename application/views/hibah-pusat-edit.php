<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('HibahPusat/doEdit'); ?>" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $hibah_pusat->id; ?>" />
      <h3>Pejabat yang Menyerahkan</h3>
      <hr>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penyerah" class="form-control nocaps" required value="<?php echo $hibah_pusat->nama_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>NIP</label>
        <input type="text" name="nip_penyerah" class="form-control nocaps" required value="<?php echo $hibah_pusat->nip_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>PANGKAT/GOLONGAN</label>
        <input type="text" name="pangkat_penyerah" class="form-control nocaps" required value="<?php echo $hibah_pusat->pangkat_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penyerah" class="form-control nocaps" required value="<?php echo $hibah_pusat->jabatan_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>ALAMAT DINAS</label>
        <textarea name="alamat_dinas_penyerah" class="form-control" required ><?php echo $hibah_pusat->alamat_dinas_penyerah; ?></textarea>
      </div>
      <h3>Pejabat yang Menerima</h3>
      <hr>
      <div class="col_full">
      <label>TITIK SERAH</label>
        <select name="titik_serah" id="titik_serah" class="form-control js-example-basic-single" onchange="changeNamaWilayah(this.value);" required>
          <option value="PROVINSI" <?php echo ($hibah_pusat->titik_serah == 'PROVINSI' ? 'selected' : ''); ?> >PROVINSI</option>
          <option value="KABUPATEN" <?php echo ($hibah_pusat->titik_serah == 'KABUPATEN' ? 'selected' : ''); ?> >KABUPATEN</option>
          <option value="KOTA" <?php echo ($hibah_pusat->titik_serah == 'KOTA' ? 'selected' : ''); ?> >KOTA</option>
        </select>
      </div>
      <div class="col_full">
      <label>NAMA WILAYAH</label>
        <select name="nama_wilayah" id="nama_wilayah" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
      <label>NAMA INSTANSI</label>
        <input type="text" name="instansi_penerima" class="form-control nocaps" required value="<?php echo $hibah_pusat->instansi_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penerima" class="form-control nocaps" required value="<?php echo $hibah_pusat->nama_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>NIP</label>
        <input type="text" name="nip_penerima" class="form-control nocaps" required value="<?php echo $hibah_pusat->nip_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>PANGKAT/GOLONGAN</label>
        <input type="text" name="pangkat_penerima" class="form-control nocaps" required value="<?php echo $hibah_pusat->pangkat_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penerima" class="form-control nocaps" required value="<?php echo $hibah_pusat->jabatan_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>ALAMAT DINAS</label>
        <textarea name="alamat_dinas_penerima" class="form-control" required ><?php echo $hibah_pusat->alamat_dinas_penerima; ?></textarea>
      </div>
      <h3>Informasi Dokumen</h3>
      <hr>
      <div class="col_full">
        <label>TANGGAL NASKAH HIBAH</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_naskah_hibah" autocomplete="off" value="<?php echo (($hibah_pusat->tanggal_naskah_hibah == '' or $hibah_pusat->tanggal_naskah_hibah == '0000-00-00' ) ? '' : date('d-m-Y', strtotime($hibah_pusat->tanggal_naskah_hibah))); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <div class="col_full">
        <label>NO. NASKAH HIBAH</label>
        <input type="text" name="no_naskah_hibah" id="no_naskah_hibah" class="form-control" value="<?php echo $hibah_pusat->no_naskah_hibah; ?>" />
      </div>
      <div class="col_full">
        <label>TANGGAL BAST BMN</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_bast_bmn" autocomplete="off" value="<?php echo (($hibah_pusat->tanggal_bast_bmn == '' or $hibah_pusat->tanggal_bast_bmn == '0000-00-00') ? '' : date('d-m-Y', strtotime($hibah_pusat->tanggal_bast_bmn))); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <div class="col_full">
        <label>NO. BAST BMN</label>
        <input type="text" name="no_bast_bmn" id="no_bast_bmn" class="form-control" value="<?php echo $hibah_pusat->no_bast_bmn; ?>" />
      </div>
      <div class="col_full">
        <label>TANGGAL SURAT PERNYATAAN</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_surat_pernyataan" autocomplete="off" value="<?php echo ($hibah_pusat->tanggal_surat_pernyataan == '' ? '' : date('d-m-Y', strtotime($hibah_pusat->tanggal_surat_pernyataan))); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <div class="col_full">
        <label>NO. SURAT PERNYATAAN</label>
        <input type="text" name="no_surat_pernyataan" id="no_surat_pernyataan" class="form-control" value="<?php echo $hibah_pusat->no_surat_pernyataan; ?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('HibahPusat'); ?>">
          <button type="button" class="button button-3d button-white button-light nomargin mr10">
              Kembali
          </button>
        </a>
        <button type="submit" class="button button-3d nomargin">Berikutnya</button>
      </div>
    </form>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
    changeNamaWilayah('<?php echo $hibah_pusat->titik_serah; ?>');
  });
  
  var prov = '<?php echo $provinsi_alokasi; ?>';
  var kab = '<?php echo $kabupaten_alokasi; ?>';

  function changeNamaWilayah(val)
  {
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
              if(data[i]["nama_provinsi"] == '<?php echo $hibah_pusat->nama_wilayah; ?>'){
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_provinsi"]+"' selected>"+data[i]["nama_provinsi"]+"</option>"
                );
              }
              else{

                if(data[i]["nama_provinsi"] == prov){
                  $('#nama_wilayah').append(
                    "<option value='"+data[i]["nama_provinsi"]+"'>"+data[i]["nama_provinsi"]+"</option>"
                  );
                }
                
              }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
      });
    }
    else{
      $.ajax({
        url: "<?php echo base_url('Kabupaten/AjaxGetAll'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {},
        success: function(data) {
            $('#nama_wilayah').empty();
            for(var i=0;i<data.length;i++)
            {
              if(data[i]["nama_kabupaten"] == '<?php echo $hibah_pusat->nama_wilayah; ?>'){
                $('#nama_wilayah').append(
                  "<option value='"+data[i]["nama_kabupaten"]+"' selected>"+data[i]["nama_kabupaten"]+"</option>"
                );
              }
              else{

                if(data[i]["nama_kabupaten"] == kab){
                  $('#nama_wilayah').append(
                    "<option value='"+data[i]["nama_kabupaten"]+"'>"+data[i]["nama_kabupaten"]+"</option>"
                  );
                }
                
              }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
      });
    }
  }

  $('.input-daterange').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });
</script>