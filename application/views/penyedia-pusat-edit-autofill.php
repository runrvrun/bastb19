<div class="col_two_fifth col_centered nobottommargin">
    <div class="well well-lg nobottommargin">
      <form method="post" action="<?php echo base_url('PenyediaPusat/update_autofill'); ?>">
        <input type="hidden" name="id" value="<?php echo $penyedia_pusat->id; ?>">
        <h3>UBAH DATA AUTOFILL PENYEDIA PUSAT: <br/><?php echo $penyedia_pusat->nama_penyedia_pusat ?></h3>
        <hr>
        <div class="col_full">
          <label>Nama Yang Menyerahkan</label>
          <input type="text" name="nama_penyerah" class="form-control" value="<?php echo $penyedia_pusat->nama_penyerah; ?>" />
        </div>
        <div class="col_full">
          <label>Jabatan Yang Menyerahkan</label>
          <input type="text" name="jabatan_penyerah" class="form-control" value="<?php echo $penyedia_pusat->jabatan_penyerah; ?>" />
        </div>
        <div class="col_full">
          <label>Nomor Telepon Yang Menyerahkan</label>
          <input type="text" name="notelp_penyerah" class="form-control" value="<?php echo $penyedia_pusat->notelp_penyerah; ?>" />
        </div>
        <div class="col_full">
          <label>Alamat Yang Menyerahkan</label>
          <textarea rows=3 name="alamat_penyerah" class="form-control"><?php echo $penyedia_pusat->alamat_penyerah; ?></textarea>
        </div>
        <div class="col_full">
          <label>Provinsi Yang Menyerahkan</label>
          <select name="id_provinsi_penyerah" id="id_provinsi_penyerah" class="form-control js-example-basic-single">
          <?php 
            foreach($provinsi as $prov){
              echo "<option value=".$prov->id." ".($prov->id == $penyedia_pusat->id_provinsi_penyerah ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
            }
          ?>
          </select>
        </div>
        <div class="col_full">
          <label>Kabupaten/Kota Yang Menyerahkan</label>
          <select name="id_kabupaten_penyerah" id="id_kabupaten_penyerah" class="form-control js-example-basic-single">
          </select>
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
            if(data[i]["id"] == '<?php echo $penyedia_pusat->id_kabupaten_penyerah; ?>'){
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