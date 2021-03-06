<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Penggunaan_ongkir/store'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;">
      <input type="hidden" name="id_baphp" value="<?php echo $id_baphp;?>"/>
      <h3>TAMBAH DATA DATA ONGKIR</h3>
      <hr>
      <?php foreach($cols as $key=>$val){ ?>
      <div class="col_full">
        <label><?php echo $val['caption'];?></label>
        <?php if(in_array($val['column'], array('tanggal_surat_permohonan','tanggal_surat_ke_penyedia','tanggal_surat_ke_dinas'))) { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="<?php echo $val['column'];?>" required />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'id_kontrak_ongkir') { ?>
          <select name="<?php echo $val['column'];?>" class="form-control">
            <?php foreach($kontrak_ongkir as $key=>$val){?>
              <option value='<?php echo $val->id;?>'><?php echo $val->nomor;?></option>
            <?php } ?>
          </select>
        <?php }elseif($val['column'] == 'ongkir') { ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control numberFilter" />
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" />
        <?php } ?>
      </div>
      <?php } ?>    
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Penggunaan_ongkir/index?id_baphp='.$id_baphp); ?>">
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