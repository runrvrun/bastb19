<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Baphp_provinsi_norangka/store'); ?>" style="font-color:black;">
      <input type="hidden" name="id_baphp_provinsi" value="<?php echo $id_baphp_provinsi;?>"/>
      <input type="hidden" name="id_kontrak_provinsi" value="<?php echo $id_kontrak_provinsi;?>"/>
      <h3>TAMBAH DATA NO. RANGKA & NO. MESIN</h3>
      <p>Pisahkan nomor rangka dan nomor mesin dengan tanda koma (,) atau titik koma (;).</p>
      <p>Pisahkan kendaraan dengan baris baru.</p>
      <hr>
      <div class="col_full">
        <label><No. Rangka, No. Mesin</label>        
          <textarea name="norangkanomesin" id="norangkanomesin" class="form-control" rows=20 style="width:375px;"></textarea>
      </div>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Baphp_provinsi_norangka/index?id_baphp_provinsi='.$id_baphp_provinsi); ?>">
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

  $(".numberFilter").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
      // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
      ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
      // Allow: home, end, left, right, down, up
      (e.keyCode >= 35 && e.keyCode <= 40)) {
      // let it happen, don't do anything
      return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
  });
  function addCommas(nStr) {
      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
</script>