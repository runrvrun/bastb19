<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Ongkir_persediaan/update'); ?>" style="font-color:black;">
      <input type="hidden" name="id_baphp_persediaan" value="<?php echo $ongkir_persediaan->id_baphp_persediaan;?>"/>
      <input type="hidden" name="id" value="<?php echo $ongkir_persediaan->id; ?>" />
      <h3>UBAH DATA DATA ONGKIR</h3>
      <hr>
      <?php foreach($cols as $key=>$val){ ?>
      <div class="col_full">
      <label><?php echo $val['caption'];?></label>
        <?php if(in_array($val['column'], array('tanggal_surat_permohonan','tanggal_surat_ke_penyedia','tanggal_surat_ke_dinas'))) { ?>
          <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="<?php echo $val['column'];?>" value="<?php echo date('d-m-Y', strtotime($ongkir_persediaan->{$val['column']})); ?>" required />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
        <?php }elseif($val['column'] == 'id_kontrak_ongkir') { ?>
          <select name="<?php echo $val['column'];?>" class="form-control">
            <?php foreach($kontrak_ongkir as $key=>$valu){?>
              <option value='<?php echo $valu->id;?>' <?php echo ($valu->id == $ongkir_persediaan->{$val['column']})? 'selected':'';?>><?php echo $valu->nomor;?> - Rp <?php echo number_format($valu->ongkir,0);?></option>
            <?php } ?>
          </select>
        <?php }elseif($val['column'] == 'ongkir') { ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" value="<?php echo $ongkir_persediaan->{$val['column']};?>" class="form-control numberFilter" />
        <?php }elseif($val['column'] == 'keterangan') { ?>
          <textarea name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control"><?php echo $ongkir_persediaan->{$val['column']};?></textarea>
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $ongkir_persediaan->{$val['column']} ?>" />
        <?php } ?>
      </div>
      <?php } ?>    
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Ongkir_persediaan/index?id_baphp_persediaan='.$ongkir_persediaan->id_baphp_persediaan); ?>">
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