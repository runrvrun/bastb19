<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Kelurahan/doEdit'); ?>" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $kelurahan->id; ?>" />
      <h3>UBAH DATA KELURAHAN/DESA</h3>
      <hr>
      <div class="col_full">
        <label>KODE</label>
        <input type="text" name="kode_kelurahan" class="form-control" required value="<?php echo $kelurahan->kode_kelurahan; ?>" />
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" id="id_provinsi" class="form-control js-example-basic-single" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($kelurahan->id_provinsi == $prov->id ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
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
        <select name="id_kecamatan" id="id_kecamatan" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
        <label>KELURAHAN/DESA</label>
        <input type="text" name="nama_kelurahan" class="form-control" required value="<?php echo $kelurahan->nama_kelurahan; ?>" />
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Kelurahan'); ?>">
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

  $('#id_kabupaten').change(function() {
      LoadKecamatan();
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
            if(data[i]["id"] == '<?php echo $kelurahan->id_kabupaten; ?>'){
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
      data: {id_provinsi : $('#id_provinsi').val(), id_kabupaten : $('#id_kabupaten').val()},
      success: function(data) {
          $('#id_kecamatan').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo $kelurahan->id_kecamatan; ?>'){
              $('#id_kecamatan').append(
                "<option value='"+data[i]["id"]+"' selected>"+data[i]["nama_kecamatan"]+"</option>"
              );
            }
            else{
              $('#id_kecamatan').append(
                "<option value='"+data[i]["id"]+"'>"+data[i]["nama_kecamatan"]+"</option>"
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