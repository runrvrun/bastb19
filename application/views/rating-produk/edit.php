<link rel="stylesheet" href="<?php echo base_url('assets/js/rateit/rateit.css'); ?>">
<script src="<?php echo base_url('assets/js/rateit/jquery.rateit.min.js'); ?>"></script>
<style>
.rateit{
  display: block;
}
</style>  

<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Rating_produk/update'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;">
      <input type="hidden" name="id" value="<?php if(isset($rating_produk->id)) echo $rating_produk->id; ?>" />
      <input type="hidden" name="id_jenis_barang_pusat" value="<?php if(isset($rating_produk->id_jenis_barang_pusat)) echo $rating_produk->id_jenis_barang_pusat; ?>" />
      <h3>UBAH DATA RATING PRODUK</h3>
      <hr>
      <?php foreach($cols as $key=>$val){ ?>
      <div class="col_full">
        <label><?php echo $val['caption'];?></label>
        <?php if(in_array($val['column'], array('mutu', 'daya_tahan', 'kesesuaian', 
				'ketersediaan_suku_cadang', 'perawatan', 'cara_pengoperasian'))) { ?>
          <div class="rateit countoverall" id="rateit_<?php echo $val['column'];?>" data-rateit-value="<?php echo $rating_produk->{$val['column']};?>" data-rateit-ispreset="true" data-rateit-mode="font" style="font-size:40px" data-rateit-resetable="false" data-rateit-step="1"></div>
          <input type="hidden" id="<?php echo $val['column'];?>" name="<?php echo $val['column'];?>" value="" />
        <?php }elseif($val['column']=='overall'){ ?>
          <div class="rateit" data-rateit-readonly="true" id="rateit_<?php echo $val['column'];?>" data-rateit-value="<?php echo $rating_produk->{$val['column']};?>" data-rateit-ispreset="true" data-rateit-mode="font" style="font-size:40px" data-rateit-resetable="false"></div>
          <input type="hidden" id="<?php echo $val['column'];?>" name="<?php echo $val['column'];?>" value="" />
        <?php }elseif($val['column']=='nama_jenis_barang_pusat'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" disabled id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $rating_produk->{$val['column']} ?>" />
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $rating_produk->{$val['column']} ?>" />
        <?php } ?>
      </div>
      <?php } ?>    
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Rating_produk'); ?>">
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

  $(".countoverall").click(function (e){
    var mutu = $('#rateit_mutu').rateit('value');
    var daya_tahan = $('#rateit_daya_tahan').rateit('value');
    var kesesuaian = $('#rateit_kesesuaian').rateit('value');
    var ketersediaan_suku_cadang = $('#rateit_ketersediaan_suku_cadang').rateit('value');
    var perawatan = $('#rateit_perawatan').rateit('value');
    var cara_pengoperasian = $('#rateit_cara_pengoperasian').rateit('value');

    $("#mutu").val(mutu);
    $("#daya_tahan").val(daya_tahan);
    $("#kesesuaian").val(kesesuaian);
    $("#ketersediaan_suku_cadang").val(ketersediaan_suku_cadang);
    $("#perawatan").val(perawatan);
    $("#cara_pengoperasian").val(cara_pengoperasian);

    var overall = (mutu*0.2) + (daya_tahan*0.2) + (kesesuaian*0.2) + (ketersediaan_suku_cadang*0.2) + (perawatan*0.1) + (cara_pengoperasian*0.1);
    overall = Math.round(overall); 
    $("#overall").val(overall);
    $('#rateit_overall').rateit('value', overall);
  });

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