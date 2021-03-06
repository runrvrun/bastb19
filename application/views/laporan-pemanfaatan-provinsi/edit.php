<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Laporan_pemanfaatan_provinsi/update'); ?>" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $laporan_pemanfaatan_provinsi->id; ?>" />
      <?php if(!empty($laporan_pemanfaatan_provinsi->id_bastb_provinsi)){ ?>
      <input type="hidden" name="id_bastb_provinsi" value="<?php echo $laporan_pemanfaatan_provinsi->id_bastb_provinsi; ?>" />
      <?php } ?>
      <?php foreach($cols as $key=>$val){ ?>
      <?php if(in_array($val['column'], array('periode_selesai'))){
        continue;//skip
      }
      ?>
      <div class="col_full">
        <label><?php echo $val['caption'];?></label>        
        <?php if(in_array($val['column'], array('tanggal_laporan'))){ ?> 
          <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm form-control" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" value="<?php echo date('d-m-Y', strtotime($laporan_pemanfaatan_provinsi->{$val['column']})); ?>" autocomplete="off" required />
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>            
          </div>                     
        <?php }elseif(in_array($val['column'], array('periode_mulai'))){ ?>
          <div class="input-daterange input-group" id="datepicker"> 
            <input type="text" class="input-sm form-control" name="periode_mulai" id="periode_mulai" value="<?php echo date('d-m-Y', strtotime($laporan_pemanfaatan_provinsi->periode_mulai)); ?>" autocomplete="off" required />
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>           
            <span class="input-group-addon">s/d</span>
            <input type="text" class="input-sm form-control" name="periode_selesai" id="periode_selesai" value="<?php echo date('d-m-Y', strtotime($laporan_pemanfaatan_provinsi->periode_selesai)); ?>" autocomplete="off" required />
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>           
          </div>            
       <?php }elseif(in_array($val['column'], array('total_area'))){ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" value="<?php echo $laporan_pemanfaatan_provinsi->{$val['column']};?>" class="form-control" />
        <?php }elseif(in_array($val['column'], array('kondisi'))){ ?>
          <select name="<?php echo $val['column'];?>" class="form-control">
            <option value="Operasional" <?php echo ($laporan_pemanfaatan_provinsi->kondisi == 'Operasional')? 'selected':''; ?>>Operasional</option>
            <option value="Tidak Operasional" <?php echo ($laporan_pemanfaatan_provinsi->kondisi == ' Tidak Operasional')? 'selected':''; ?>>Tidak Operasional</option>
            <option value="Rusak" <?php echo ($laporan_pemanfaatan_provinsi->kondisi == 'Rusak')? 'selected':''; ?>>Rusak</option>
          </select>
        <?php }elseif(in_array($val['column'], array('keterangan','perawatan','pengguna'))){ ?>
          <textarea name="<?php echo $val['column'];?>" class="form-control" rows="4" placeholder="" required><?php echo $laporan_pemanfaatan_provinsi->{$val['column']}; ?></textarea>                
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $laporan_pemanfaatan_provinsi->{$val['column']}; ?>" />
        <?php } ?>
      </div>
      <?php } ?>
      <div class="col_full nobottommargin">
        <?php if(!empty($bastb_provinsi->id)){ ?>
        <a href="<?php echo base_url('Laporan_pemanfaatan_provinsi/index?id_bastb_provinsi=').$bastb_provinsi->id; ?>">
        <?php } ?>
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
    $('.input-daterange').datepicker({
      format: "dd-mm-yyyy",
      autoclose: true
    });

    $('.js-example-basic-single').select2();
  });
</script>