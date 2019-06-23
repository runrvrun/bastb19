<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('KontrakPusat/doEdit'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $kontrak_pusat->id; ?>" />
      <h3>UBAH DATA KONTRAK PUSAT</h3>
      <hr>
      <div class="col_full">
        <label>TAHUN ANGGARAN</label>
        <input type="text" name="tahun_anggaran" class="form-control" value="<?php echo $kontrak_pusat->tahun_anggaran; ?>" readonly/>
      </div>
      <div class="col_full">
        <label>NAMA PENYEDIA</label>
        <select name="id_penyedia_pusat" id="id_penyedia_pusat" class="form-control js-example-basic-single" required>
        <?php 
          foreach($penyedia_pusat as $penyedia){
            echo "<option value=".$penyedia->id." ".($penyedia->id == $kontrak_pusat->id_penyedia_pusat ? 'selected' : 
              '')." >".$penyedia->nama_penyedia_pusat."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>NO KONTRAK</label>
        <input type="text" name="no_kontrak" class="form-control" value="<?php echo $kontrak_pusat->no_kontrak; ?>" required />
      </div>
      <div class="col_full">
        <label>PERIODE</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="periode_mulai" required value="<?php echo date('d-m-Y', strtotime($kontrak_pusat->periode_mulai)); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          <span class="input-group-addon">s/d</span>
          <input type="text" class="input-sm form-control" name="periode_selesai" required value="<?php echo date('d-m-Y', strtotime($kontrak_pusat->periode_selesai)); ?>" />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
        
      </div>
      <div class="col_full">
        <label>NAMA BARANG</label>
        <select name="nama_barang" id="nama_barang" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
        <label>MERK</label>
        <select name="merk" id="merk" class="form-control js-example-basic-single" required>
        </select>
      </div>
      <div class="col_full">
        <label>JUMLAH BARANG</label>
        <input type="text" name="jumlah_barang" id="jumlah_barang" required value="<?php echo $kontrak_pusat->jumlah_barang; ?>" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP)</label>
        <input type="text" name="nilai_barang" id="nilai_barang" required value="<?php echo $kontrak_pusat->nilai_barang; ?>" class="form-control numberFilter" />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP)</label>
        <input type="text" name="harga_satuan" id="harga_satuan" required value="<?php echo ($kontrak_pusat->nilai_barang/$kontrak_pusat->jumlah_barang); ?>" class="form-control" readonly />
      </div>
      <div class="col_full">
        <!-- <label>UPLOAD FOTO/DOKUMEN KONTRAK</label> -->
        <!-- <input type='file' name="image_upload" id="image_upload" accept="image/*" /> -->
        <!-- <img id="image_preview" src="<?php echo base_url('upload/kontrak_pusat/').$kontrak_pusat->nama_file; ?>" alt="Preview Image" /> -->
        <?php
          // if($kontrak_pusat->nama_file != '' and $kontrak_pusat->nama_file != '[]' and !is_null($kontrak_pusat->nama_file) and $kontrak_pusat->nama_file != 'null' and $kontrak_pusat->nama_file != null){
          //   $files = json_decode($kontrak_pusat->nama_file);
          //   echo '<table border="2">';
          //   $i=1;
          //   foreach($files as $file){
          //     echo '<tr class="tra'.$i.'">';
          //     if(substr($file, -3) == 'pdf'){
          //       echo '<td nowrap><img src="'.base_url('../upload/pdflogo.png').'" alt="Pdf File" width="60" />
          //         <a href="'.base_url('../upload/kontrak_pusat/').$file.'" target="_blank">'.$file.'</a>
          //       </td>';
          //     }
          //     else{
          //       echo '<td><img src="'.base_url('../upload/kontrak_pusat/').$file.'" alt="Preview Image" /></td>';
          //     }
              
            ?>
              <!-- <td valign="top">
                <a class="btn btn-s btn-danger" onclick="removeImage(<?php echo $i; ?>, '<?php echo $file; ?>')"><i class="glyphicon glyphicon-trash"></i></a>
              </td> -->
            <?php
              // echo '</tr>';
              // $i++;
            // }
            // echo '</table>';
          // }
          // else{
            // echo '<br>File tidak ditemukan';
          // }
          
        ?>
      </div>
      <input type="hidden" name="removedImages" id="removedImages" />
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('KontrakPusat'); ?>">
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
    LoadBarang();

    <?php if($kontrak_pusat->nama_file == ''){ ?>
      $('#image_preview').hide();
    <?php } ?>

  });

  $('.input-daterange').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });

  $('#id_penyedia_pusat').change(function() {
    LoadBarang();
  });

  $('#nama_barang').change(function() {
    LoadMerk();
  });

  var temp = new Array();
  function removeImage(urutan, imagename){
      $('.tra'+urutan).hide();
      temp.push(imagename);
      $('#removedImages').val(temp);
  }

  function LoadBarang(){
    $.ajax({
      url: "<?php echo base_url('JenisBarangPusat/GetByPenyedia'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_penyedia_pusat : $('#id_penyedia_pusat').val()},
      success: function(data) {
          $('#nama_barang').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["nama_barang"] == '<?php echo $kontrak_pusat->nama_barang; ?>'){
              $('#nama_barang').append(
                "<option value='"+data[i]["nama_barang"]+"' selected>"+data[i]["nama_barang"]+"</option>"
              );
            }
            else{
              $('#nama_barang').append(
                "<option value='"+data[i]["nama_barang"]+"'>"+data[i]["nama_barang"]+"</option>"
              );
            }
          }
          LoadMerk();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }

  function LoadMerk(){
    $.ajax({
      url: "<?php echo base_url('JenisBarangPusat/GetMerk'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {nama_barang : $('#nama_barang').val(), id_penyedia_pusat : $('#id_penyedia_pusat').val()},
      success: function(data) {
          // $('#merk').val(data);
          $('#merk').empty();
          for(var i=0;i<data.length;i++)
          {
            if(data[i]["merk"] == '<?php echo $kontrak_pusat->merk; ?>'){
              $('#merk').append(
                "<option value='"+data[i]["merk"]+"' selected>"+data[i]["merk"]+"</option>"
              );
            }
            else{
              $('#merk').append(
                "<option value='"+data[i]["merk"]+"'>"+data[i]["merk"]+"</option>"
              );
            }

          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert("Data Not Found");
      }
    });
  }
  

  $('#jumlah_barang').on('change keyup paste mouseup', function() {
    $('#harga_satuan').val($('#nilai_barang').val() / $('#jumlah_barang').val());
  });

  $('#nilai_barang').on('change keyup paste mouseup', function() {
    $('#harga_satuan').val($('#nilai_barang').val() / $('#jumlah_barang').val());
  });

  $('#jumlah_barang').number( true, 0 );
  $('#nilai_barang').number( true, 0 );
  $('#harga_satuan').number( true, 0 );

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

  function readURL(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      $('#image_preview').show();
      reader.onload = function(e) {
        $('#image_preview').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#image_upload").change(function() {
    readURL(this);
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