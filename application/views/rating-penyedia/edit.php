<link rel="stylesheet" href="<?php echo base_url('assets/js/rateit/rateit.css'); ?>">
<script src="<?php echo base_url('assets/js/rateit/jquery.rateit.min.js'); ?>"></script>
<style>
.rateit{
  display: block;
}
</style>  

<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('Rating_penyedia/update'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;">
      <input type="hidden" name="id" value="<?php if(isset($rating_penyedia->id)) echo $rating_penyedia->id; ?>" />
      <input type="hidden" name="id_penyedia_pusat" value="<?php if(isset($rating_penyedia->id_penyedia_pusat)) echo $rating_penyedia->id_penyedia_pusat; ?>" />
      <h3>UBAH DATA RATING PENYEDIA</h3>
      <hr>
      <?php foreach($cols as $key=>$val){ ?>
      <div class="col_full">
        <label><?php echo $val['caption'];?></label>
        <?php if(in_array($val['column'], array('kecepatan_pelayanan', 'kualitas_pelayanan', 'penjelasan_produk', 
				'bantuan_informasi_teknis', 'penanganan_komplain'))) { ?>
          <div class="rateit countoverall" id="rateit_<?php echo $val['column'];?>" data-rateit-value="<?php echo $rating_penyedia->{$val['column']};?>" data-rateit-ispreset="true" data-rateit-mode="font" style="font-size:40px" data-rateit-resetable="false" data-rateit-step="1"></div>
          <input type="hidden" id="<?php echo $val['column'];?>" name="<?php echo $val['column'];?>" value="" />
        <?php }elseif($val['column']=='overall'){ ?>
          <div class="rateit" data-rateit-readonly="true" id="rateit_<?php echo $val['column'];?>" data-rateit-value="<?php echo $rating_penyedia->{$val['column']};?>" data-rateit-ispreset="true" data-rateit-mode="font" style="font-size:40px" data-rateit-resetable="false"></div>
          <input type="hidden" id="<?php echo $val['column'];?>" name="<?php echo $val['column'];?>" value="" />
        <?php }elseif($val['column']=='nama_penyedia_pusat'){ ?>
          <input type="text" name="<?php echo $val['column'];?>" disabled id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $rating_penyedia->{$val['column']} ?>" />
        <?php }else{ ?>
          <input type="text" name="<?php echo $val['column'];?>" id="<?php echo $val['column'];?>" class="form-control" value="<?php echo $rating_penyedia->{$val['column']} ?>" />
        <?php } ?>
      </div>
      <?php } ?>    
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('Rating_penyedia'); ?>">
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
    var kecepatan_pelayanan = $('#rateit_kecepatan_pelayanan').rateit('value');
    var kualitas_pelayanan = $('#rateit_kualitas_pelayanan').rateit('value');
    var penjelasan_produk = $('#rateit_penjelasan_produk').rateit('value');
    var bantuan_informasi_teknis = $('#rateit_bantuan_informasi_teknis').rateit('value');
    var penanganan_komplain = $('#rateit_penanganan_komplain').rateit('value');

    $("#kecepatan_pelayanan").val(kecepatan_pelayanan);
    $("#kualitas_pelayanan").val(kualitas_pelayanan);
    $("#penjelasan_produk").val(penjelasan_produk);
    $("#bantuan_informasi_teknis").val(bantuan_informasi_teknis);
    $("#penanganan_komplain").val(penanganan_komplain);

    var overall = (kecepatan_pelayanan*0.2) + (kualitas_pelayanan*0.2) + (penjelasan_produk*0.2) + (bantuan_informasi_teknis*0.2) + (penanganan_komplain*0.2);
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