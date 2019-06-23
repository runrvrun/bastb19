<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('JenisBarangProvinsi/doAdd'); ?>" style="font-color:black;">
      <h3>TAMBAH DATA JENIS BARANG TP PROVINSI</h3>
      <hr>
      <div class="col_full">
        <label>JENIS BARANG</label>
        <input type="text" name="jenis_barang" class="form-control" required />
      </div>
      <div class="col_full">
        <label>NAMA BARANG</label>
        <input type="text" name="nama_barang" class="form-control" required />
      </div>
      <div class="col_full">
        <label>MERK</label>
        <input type="text" name="merk" class="form-control" required />
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" id="id_provinsi" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id.">".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>PENYEDIA</label>
        <select name="id_penyedia_provinsi" id="id_penyedia_provinsi" class="form-control js-example-basic-single" required>
        <?php 
          // foreach($penyedia_provinsi as $penyedia){
          //   echo "<option value=".$penyedia->id.">".$penyedia->nama_penyedia_provinsi."</option>";
          // }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KODE BARANG</label>
        <input type="text" name="kode_barang" class="form-control" required />
      </div>
      <div class="col_full">
        <label>AKUN</label>
        <input type="text" name="akun" class="form-control" required />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('JenisBarangProvinsi'); ?>">
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
    LoadPenyedia();
  });

  $('#id_provinsi').change(function() {
    LoadPenyedia()
  });

  function LoadPenyedia(){
    $.ajax({
      url: "<?php echo base_url('PenyediaProvinsi/GetByProvinsi'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_provinsi : $('#id_provinsi').val()},
      success: function(data) {
          $('#id_penyedia_provinsi').empty();
          for(var i=0;i<data.length;i++)
          {
            $('#id_penyedia_provinsi').append($('<option></option>').attr('value', data[i]["id"]).text(data[i]["nama_penyedia_provinsi"]));
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }
</script>