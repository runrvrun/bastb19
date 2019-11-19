<div class="col_two_fifth col_centered nobottommargin">
    <div class="well well-lg nobottommargin">
      <form method="post" action="<?php echo base_url('Kabupaten/update_autofill'); ?>">
        <input type="hidden" name="id" value="<?php echo $kabupaten->id; ?>">
        <h3>UBAH DATA AUTOFILL ADMIN KABUPATEN: <br/><?php echo $kabupaten->nama_kabupaten ?></h3>
        <hr>
        <div class="col_full">
          <label>Nama Dinas</label>
          <input type="text" name="nama_dinas" class="form-control" value="<?php echo $kabupaten->nama_dinas; ?>" />
        </div>
        <div class="col_full">
          <label>Nama Yang Menyerahkan</label>
          <input type="text" name="nama_penyerah" class="form-control" value="<?php echo $kabupaten->nama_penyerah; ?>" />
        </div>
        <div class="col_full">
          <label>Jabatan Yang Menyerahkan</label>
          <input type="text" name="jabatan_penyerah" class="form-control" value="<?php echo $kabupaten->jabatan_penyerah; ?>" />
        </div>
        <div class="col_full">
          <label>Nomor Telepon Yang Menyerahkan</label>
          <input type="text" name="notelp_penyerah" class="form-control" value="<?php echo $kabupaten->notelp_penyerah; ?>" />
        </div>
        <div class="col_full">
          <label>Alamat Yang Menyerahkan</label>
          <textarea rows=3 name="alamat_penyerah" class="form-control"><?php echo $kabupaten->alamat_penyerah; ?></textarea>
        </div>
        <!-- <div class="col_full">
          <input type="hidden" name="id_provinsi_penyerah" value="<?php echo $kabupaten->id_provinsi;?>" />
          <label>Kabupaten/Kota Yang Menyerahkan</label>
          <select name="id_kabupaten_penyerah" id="id_kabupaten_penyerah" class="form-control js-example-basic-single">
          </select>
        </div> -->
        <div class="col_full">
          <label>Nama Mengetahui</label>
          <input type="text" name="nama_mengetahui" class="form-control" value="<?php echo $kabupaten->nama_mengetahui; ?>" />
        </div>
        <div class="col_full">
          <label>Jabatan Mengetahui</label>
          <input type="text" name="jabatan_mengetahui" class="form-control" value="<?php echo $kabupaten->jabatan_mengetahui; ?>" />
        </div>
        <div class="col_full nobottommargin">
          <a href="<?php echo base_url('Home'); ?>">
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
  });

  // $('#id_kabupaten_penyerah').change(function() {
  //     LoadKabupaten();
  // });

  function LoadKabupaten()
  {
    $.ajax({
      url: "<?php echo base_url('Kabupaten/GetByProvinsi'); ?>",
      dataType: 'JSON', 
      type: 'GET',
      data: {id_provinsi : <?php echo $kabupaten->id_provinsi;?>},
      success: function(data) {
          $('#id_kabupaten_penyerah').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["id"] == '<?php echo $kabupaten->id_kabupaten_penyerah; ?>'){
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

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
  </script>