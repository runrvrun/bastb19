<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Kecamatan/doEdit'); ?>" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $kecamatan->id; ?>" />
      <h3>UBAH DATA KECAMATAN</h3>
      <hr>
      <div class="col_full">
        <label>KODE</label>
        <input type="text" name="kode_kecamatan" class="form-control" required value="<?php echo $kecamatan->kode_kecamatan; ?>" />
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" id="id_provinsi" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($kecamatan->id_provinsi == $prov->id ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KABUPATEN/KOTA</label>
        <select name="id_kabupaten" id="id_kabupaten" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
        <label>KECAMATAN</label>
        <input type="text" name="nama_kecamatan" class="form-control" required value="<?php echo $kecamatan->nama_kecamatan; ?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Kecamatan'); ?>">
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
    LoadKabupaten();
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
            if(data[i]["id"] == '<?php echo $kecamatan->id_kabupaten; ?>'){
              $('#id_kabupaten').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kabupaten"]+"</option>"
              );
            }
            else{
              $('#id_kabupaten').append(
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
</script>