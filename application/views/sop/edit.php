<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Sop/update'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $sop->id; ?>" />
      <h3>UBAH DATA SOP</h3>
      <hr>
      <div class="col_full">
        <label>TANGGAL</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal" id="tanggal" autocomplete="off" value="<?php echo date('d-m-Y', strtotime($sop->tanggal)); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>        
      </div>
      <div class="col_full">
        <label>JUDUL</label>
        <input type="text" name="judul" id="judul" class="form-control" value="<?php echo $sop->judul; ?>" />
      </div>
      <div class="col_full">
        <label>DESKRIPSI</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows=5><?php echo $sop->deskripsi; ?></textarea>
      </div>      
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Sop'); ?>">
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
      $('.input-daterange').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
      });
  });

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