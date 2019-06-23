<div id="headerKontrak" style="width:100%; text-align:center; border-style: solid; mergin-bottom: 50px">
  <h2><?php echo $selected_provinsi.' - KABUPATEN '.$selected_kabupaten; ?></h2>
  <div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" class="btn btn-default btn-lg" style="width: 250px; height: 50px;">
      <!-- <i class="glyphicon glyphicon-cog"></i><br> -->
      <?php echo "<b>".number_format($unit_alokasi, 0)."</b> UNIT"; ?><br>
    </button>
    <button type="button" class="btn btn-default btn-lg" style="width: 250px; height: 50px;">
      <!-- <i class="glyphicon glyphicon-usd"></i><br> -->
      <?php echo "Rp <b>".number_format($nilai_alokasi, 0)."</b>"; ?><br>
    </button>
  </div>
</div>
<br/><br/>
<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('HibahPusat/doAdd'); ?>" style="font-color:black;">
      <input type="hidden" name="listIdAlokasi" value="<?php echo $listIdAlokasi; ?>" />
      <input type="hidden" name="id" value="<?php echo $setting->id; ?>" />
      <h3>Pejabat yang Menyerahkan</h3>
      <hr>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penyerah" class="form-control nocaps" required value="<?php echo $setting->nama_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>NIP</label>
        <input type="text" name="nip_penyerah" class="form-control nocaps" required value="<?php echo $setting->nip_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>PANGKAT/GOLONGAN</label>
        <input type="text" name="pangkat_penyerah" class="form-control nocaps" required value="<?php echo $setting->pangkat_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penyerah" class="form-control nocaps" required value="<?php echo $setting->jabatan_penyerah; ?>" />
      </div>
      <div class="col_full">
        <label>ALAMAT DINAS</label>
        <textarea name="alamat_dinas_penyerah" class="form-control" required ><?php echo $setting->alamat_dinas_penyerah; ?></textarea>
      </div>
      <h3>Pejabat yang Menerima</h3>
      <hr>
      <div class="col_full">
      <label>TITIK SERAH</label>
        <select name="titik_serah" id="titik_serah" class="form-control js-example-basic-single" onchange="changeNamaWilayah(this.value);" required>
          <option value="PROVINSI">PROVINSI</option>
          <option value="KABUPATEN">KABUPATEN</option>
          <option value="KOTA">KOTA</option>
        </select>
      </div>
      <div class="col_full">
      <label>NAMA WILAYAH</label>
        <select name="nama_wilayah" id="nama_wilayah" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
      <label>NAMA INSTANSI</label>
        <input type="text" name="instansi_penerima" class="form-control nocaps" required value="<?php echo $setting->instansi_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penerima" class="form-control nocaps" required value="<?php echo $setting->nama_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>NIP</label>
        <input type="text" name="nip_penerima" class="form-control nocaps" required value="<?php echo $setting->nip_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>PANGKAT/GOLONGAN</label>
        <input type="text" name="pangkat_penerima" class="form-control nocaps" required value="<?php echo $setting->pangkat_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penerima" class="form-control nocaps" required value="<?php echo $setting->jabatan_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>ALAMAT DINAS</label>
        <textarea name="alamat_dinas_penerima" class="form-control" required ><?php echo $setting->alamat_dinas_penerima; ?></textarea>
      </div>
      <h3>Informasi Dokumen</h3>
      <hr>
      <div class="col_full">
        <label>TANGGAL NASKAH HIBAH</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_naskah_hibah" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <div class="col_full">
        <label>NO. NASKAH HIBAH</label>
        <input type="text" name="no_naskah_hibah" id="no_naskah_hibah" class="form-control" />
      </div>
      <div class="col_full">
        <label>TANGGAL BAST BMN</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_bast_bmn" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <div class="col_full">
        <label>NO. BAST BMN</label>
        <input type="text" name="no_bast_bmn" id="no_bast_bmn" class="form-control" />
      </div>
      <div class="col_full">
        <label>TANGGAL SURAT PERNYATAAN</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_surat_pernyataan" autocomplete="off" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>
      <div class="col_full">
        <label>NO. SURAT PERNYATAAN</label>
        <input type="text" name="no_surat_pernyataan" id="no_surat_pernyataan" class="form-control" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('HibahPusat/Add'); ?>">
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
    changeNamaWilayah('PROVINSI');
  });

  var selected_prov = '<?php echo $selected_provinsi; ?>';
  var selected_kab = '<?php echo $selected_kabupaten; ?>';

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
              if(data[i].nama_provinsi == selected_prov)
                $('#nama_wilayah').append($('<option></option>').attr('value', data[i]["nama_provinsi"]).text(data[i]["nama_provinsi"]));
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
              if(data[i].nama_kabupaten == selected_kab)
                $('#nama_wilayah').append($('<option></option>').attr('value', data[i]["nama_kabupaten"]).text(data[i]["nama_kabupaten"]));
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