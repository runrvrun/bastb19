<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Sp2d/store'); ?>" style="font-color:black;">
      <input type="hidden" name="id_kontrak_pusat" value="<?php echo $id_kontrak_pusat;?>"/>
      <h3>TAMBAH DATA SP2D</h3>
      <hr>
      <?php foreach($cols as $key=>$val){ ?>
      <div class="col_full">
        <label><?php echo $val['caption'];?></label>
        <?php if($val['column'] == 'tanggal') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'tanggal_spm') { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="tanggal_spm" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'nilai_sebelum_pajak') { ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control numberFilter" />
        <?php }elseif($val['column'] == 'nilai_setelah_pajak') { ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control numberFilter" />
        <?php }elseif($val['column'] == 'keterangan') { ?>
          <select name="keterangan" class="form-control">
            <?php if(!in_array('UANG MUKA'.$i,$termin_exist)){ ?>
              <option value="UANG MUKA">UANG MUKA</option>
            <?php } ?>
            <?php for($i=1;$i<=$kontrak_pusat->jumlah_termin;$i++){ 
                    if(in_array('TERMIN '.$i,$termin_exist)){
                      continue;
                    }  
            ?>
            <option value="TERMIN <?php echo $i;?>">TERMIN <?php echo $i;?></option>
            <?php } ?>
          </select>
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" />
        <?php } ?>
      </div>
      <?php } ?>    
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Sp2d/index?id_kontrak_pusat='.$id_kontrak_pusat); ?>">
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

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
</script>