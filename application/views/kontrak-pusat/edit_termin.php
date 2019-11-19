<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Kontrak_pusat/update_termin'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;">
      <input type="hidden" name="id" value="<?php echo $kontrak_pusat->id; ?>" />
      <h3>SETTING TERMIN PEMBAYARAN</h3>
      <hr>
      <div class="col_full">
        <label>Jumlah Termin Pembayaran</label>
        <select name='jumlah_termin' id='jumlah_termin' class="form-control">
          <option value=1 <?php echo ($kontrak_pusat->jumlah_termin == 1)? 'selected':'' ?>>1</option>
          <option value=2 <?php echo ($kontrak_pusat->jumlah_termin == 2)? 'selected':'' ?>>2</option>
          <option value=3 <?php echo ($kontrak_pusat->jumlah_termin == 3)? 'selected':'' ?>>3</option>
          <option value=4 <?php echo ($kontrak_pusat->jumlah_termin == 4)? 'selected':'' ?>>4</option>
          <option value=5 <?php echo ($kontrak_pusat->jumlah_termin == 5)? 'selected':'' ?>>5</option>
        </select>
      </div>
      <?php foreach($cols as $key=>$val){ ?>
      <div class="col_full" id='input_<?php echo $val['column'];?>'>
        <label><?php echo $val['caption'];?></label>
        <input type="text" name="<?php echo $val['column'];?>" class="form-control numberFilter" value="<?php echo $kontrak_pusat->{$val['column']}; ?>" />
      </div>
      <?php } ?>
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Kontrak_pusat'); ?>">
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

  jQuery(window).on("load", function(){
    $('#jumlah_termin').change();
  });

  $(document).ready(function() {
    $('.js-example-basic-single').select2();

    $("#jumlah_termin").on('change', function() {
      //reset
      for(i=1;i<=5;i++){
        $("#input_termin_" + i).show();
        $("input[name=termin_" + i + "]").prop('disabled', false);
      }
      //hide
      var x = parseInt(this.value) + 1; 
      for(i=x;i<=5;i++){
        $("#input_termin_" + i).hide();
        $("input[name=termin_" + i + "]").prop('disabled', true);
      }
    });
  });
</script>