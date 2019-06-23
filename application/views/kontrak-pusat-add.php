<link rel="stylesheet" href="<?php echo base_url('assets/css/dropzone.css'); ?> ">

<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" action="<?php echo base_url('KontrakPusat/doAdd'); ?>" onkeypress="return event.keyCode != 13;" style="font-color:black;" enctype="multipart/form-data">
      <h3>TAMBAH DATA KONTRAK PUSAT</h3>
      <hr>
      <div class="col_full">
        <label>TAHUN ANGGARAN</label>
        <input type="text" name="tahun_anggaran" id="tahun_anggaran" class="form-control" value="<?php echo $this->session->userdata('logged_in')->tahun_pengadaan; ?>" readonly/>
      </div>
      <div class="col_full">
        <label>NAMA PENYEDIA</label>
        <select name="id_penyedia_pusat" id="id_penyedia_pusat" class="form-control js-example-basic-single">
        <?php 
          foreach($penyedia_pusat as $penyedia){
            echo "<option value=".$penyedia->id.">".$penyedia->nama_penyedia_pusat."</option>";
          }
        ?>
        </select>
      </div>
      <div class="col_full">
        <label>NO KONTRAK</label>
        <input type="text" name="no_kontrak" id="no_kontrak" class="form-control" required />
      </div>
      <div class="col_full">
        <label>PERIODE</label>
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="periode_mulai" id="periode_mulai" required />
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          <span class="input-group-addon">s/d</span>
          <input type="text" class="input-sm form-control" name="periode_selesai" id="periode_selesai" required />
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
        <input type="text" name="jumlah_barang" id="jumlah_barang" class="form-control numberFilter" required />
      </div>
      <div class="col_full">
        <label>NILAI BARANG (RP)</label>
        <input type="text" name="nilai_barang" id="nilai_barang" class="form-control numberFilter" required />
      </div>
      <div class="col_full">
        <label>HARGA SATUAN (RP)</label>
        <input type="text" name="harga_satuan" id="harga_satuan" class="form-control" readonly />
      </div>
      <!-- <div class="col_full">
        <label>UPLOAD FOTO/DOKUMEN KONTRAK</label>

        <div class="dropzone" id="myDropzone">
          <img src="" width="50px"/> 
          <div class="dz-message" data-dz-message><span>Klik atau Drop File di sini untuk Upload</span></div>
        </div>
        
      </div> -->
      <div class="col_full nobottommargin">
        <a href="<?php echo base_url('KontrakPusat'); ?>">
          <button type="button" class="button button-3d button-white button-light nomargin mr10">
              Cancel
          </button>
        </a>
        <button type="submit" id="submit-all" class="button button-3d nomargin">Save</button>
      </div>
    </form>
  </div>
</div>

<script>  
  
  $(document).ready(function() {
    LoadBarang();

    $('#image_preview').hide();

    /*
    Dropzone.options.myDropzone= {
      // The configuration we've talked about above
      url: '<?php echo base_url("KontrakPusat/Test"); ?>',
      acceptedFiles: 'image/*,.pdf',
      addRemoveLinks: true,
      autoProcessQueue: false,
      uploadMultiple: true,
      parallelUploads: 10,
      maxFiles: 10,
      // The setting up of the dropzone
      init: function() {
        var myDropzone = this;

        // First change the button to actually tell Dropzone to process the queue.
        document.getElementById("submit-all").addEventListener("click", function(e) {
            // Make sure that the form isn't actually being sent.
            e.preventDefault();
            e.stopPropagation();
            myDropzone.processQueue();
        });


        //send all the form data along with the files:
        this.on("sendingmultiple", function(data, xhr, formData) {
            formData.append("tahun_anggaran", jQuery("#tahun_anggaran").val());
            formData.append("id_penyedia_pusat", jQuery("#id_penyedia_pusat").val());
            formData.append("no_kontrak", jQuery("#no_kontrak").val());
            formData.append("periode_mulai", jQuery("#periode_mulai").val());
            formData.append("periode_selesai", jQuery("#periode_selesai").val());
            formData.append("nama_barang", jQuery("#nama_barang").val());
            formData.append("merk", jQuery("#merk").val());
            formData.append("jumlah_barang", jQuery("#jumlah_barang").val());
            formData.append("nilai_barang", jQuery("#nilai_barang").val());
        });
      },
      success: function (file, response) {
            // var imgName = response;
            // file.previewElement.classList.add("dz-success");
            // console.log("Successfully uploaded :" + imgName);
            if(response == 'success'){
              window.location="<?php echo base_url('KontrakPusat'); ?>";
            }
            else{
              window.location="<?php echo base_url('KontrakPusat/Add'); ?>";
            }
      },
    }
    */
    

  });

  $('.input-daterange').datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });

  $('#id_penyedia_pusat').change(function() {
    LoadBarang()
  });

  $('#nama_barang').change(function() {
    LoadMerk();
  });

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
            $('#nama_barang').append($('<option></option>').attr('value', data[i]["nama_barang"]).text(data[i]["nama_barang"]));
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
            $('#merk').append($('<option></option>').attr('value', data[i]["merk"]).text(data[i]["merk"]));
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