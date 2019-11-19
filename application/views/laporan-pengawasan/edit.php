<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Informasi_terkait/update'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $laporan_pengawasan->id; ?>" />
      <h3>UBAH DATA LAPORAN PENGAWASAN</h3>
      <hr>
      <div class="col_full">
        <label>TANGGAL</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal" id="tanggal" autocomplete="off" value="<?php echo date('d-m-Y', strtotime($laporan_pengawasan->tanggal)); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>        
      </div>
      <div class="col_full">
        <label>PROVINSI</label>
        <select name="id_provinsi" id="id_provinsi" class="form-control js-example-basic-single">
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id." ".($prov->id == $laporan_pengawasan->id_provinsi ? 'selected' : '')." >".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>KABUPATEN/KOTA</label>
        <select name="id_kabupaten" id="id_kabupaten" class="form-control js-example-basic-single">
        </select>
      </div>
      <div class="col_full">
        <label>JUDUL</label>
        <input type="text" name="judul" id="judul" class="form-control" value="<?php echo $laporan_pengawasan->judul; ?>" />
      </div>
      <div class="col_full">
        <label>DESKRIPSI</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows=5><?php echo $laporan_pengawasan->deskripsi; ?></textarea>
      </div>      
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Laporan_pengawasan'); ?>">
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
      
      $('.input-daterange').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
      });
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
            if(data[i]["id"] == '<?php echo $laporan_pengawasan->id_kabupaten; ?>'){
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

  function readURL(input, no) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      $('#image_preview_'+no).show();
        reader.onload = function(e) {
        $('#image_preview_'+no).attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
</script>