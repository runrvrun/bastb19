<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Kelurahan/doAdd'); ?>" style="font-color:black;">
      <h3>TAMBAH DATA KELURAHAN</h3>
      <hr>
      <div class="col_full">
        <label>KODE</label>
        <input type="text" name="kode_kelurahan" class="form-control" required />
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
        <input type="text" name="nama_kelurahan" class="form-control" required />
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
            $('#id_kabupaten').append($('<option></option>').attr('value', data[i]["id"]).text(data[i]["nama_kabupaten"]));
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
            $('#id_kecamatan').append($('<option></option>').attr('value', data[i]["id"]).text(data[i]["nama_kecamatan"]));
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }
</script>