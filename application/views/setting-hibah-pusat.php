<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('HibahPusat/UpdateSettingUmum'); ?>" style="font-color:black;">
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
      <label>INSTANSI</label>
        <input type="text" name="instansi_penerima" id="instansi_penerima" class="form-control nocaps" required value="<?php echo $setting->instansi_penerima; ?>" />
      </div>
      <div class="col_full">
        <label>NAMA</label>
        <input type="text" name="nama_penerima" id="nama_penerima"  class="form-control nocaps" required value="<?php echo $setting->nama_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>NIP</label>
        <input type="text" name="nip_penerima" id="nip_penerima"  class="form-control nocaps" required value="<?php echo $setting->nip_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>PANGKAT/GOLONGAN</label>
        <input type="text" name="pangkat_penerima" id="pangkat_penerima"  class="form-control nocaps" required value="<?php echo $setting->pangkat_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>JABATAN</label>
        <input type="text" name="jabatan_penerima" id="jabatan_penerima"  class="form-control nocaps" required value="<?php echo $setting->jabatan_penerima; ?>" / />
      </div>
      <div class="col_full">
        <label>ALAMAT DINAS</label>
        <textarea name="alamat_dinas_penerima" class="form-control" required ><?php echo $setting->alamat_dinas_penerima; ?></textarea>
      </div
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('HibahPusat/Add'); ?>">
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
    $('.js-example-basic-single').select2();
  });
</script>