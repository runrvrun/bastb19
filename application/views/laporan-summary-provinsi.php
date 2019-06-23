<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="<?php echo base_url('assets/multi-select/js/bootstrap-multiselect.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/multi-select/css/bootstrap-multiselect.css'); ?>" type="text/css"/>
<style type="text/css">
  .report_box{
    width: 270px;
    height: 120px;
    float:left;
    border: 1px solid;
    padding: 5px;
    text-align: center;
    display: block;
    margin-top: 20px;
    margin-right: 15px;
    box-shadow: 10px 10px 5px grey;
  }

  .report_box_inner{
    width: 258px;
    height: 150px;
    float:left;
    border: 1px solid;
    padding: 5px;
    text-align: center;
    display: block;
    margin-top: 10px;
    margin-right: 15px;
    margin-bottom: 15px;
  }

  .disablingDiv{
    z-index:1;
     
    /* make it cover the whole screen */
    position: fixed; 
    top: 0%; 
    left: 0%; 
    width: 100%; 
    height: 100%; 
    overflow: hidden;
    margin:0;
    /* make it white but fully transparent */
    background-color: white; 
    opacity:0.5;  
  }
  .loader {
    position: absolute;
    left: 50%;
    top: 50%;
    z-index: 1;
    width: 150px;
    height: 150px;
    margin: -75px 0 0 -75px;
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
  }

</style>
<!-- loading div -->
<div id="disablingDiv" class="disablingDiv">
</div>
<div id="loading" class="loader">
</div>
<!-- end loading div -->
<div id="headerKontrak" style="width:100%; text-align:center; mergin-bottom: 50px; width: 100%">
  <div class="btn-group" role="group" aria-label="Basic example">
    <div style="float:left; padding: 10px; width:15%">
      <label>TAHUN ANGGARAN</label>
        <select name="tahun_anggaran" id="tahun_anggaran" class="form-control js-example-basic-single" required>
        <?php 
          foreach($tahun_pengadaan as $tahun){
            if( $tahun->tahun_pengadaan == date('Y', strtotime(NOW)) )
              echo "<option value=".$tahun->tahun_pengadaan." selected>".$tahun->tahun_pengadaan."</option>";
            else
              echo "<option value=".$tahun->tahun_pengadaan.">".$tahun->tahun_pengadaan."</option>";
          }
        ?>
        </select>
    </div>
    <div style="float:left; padding: 10px; width:20%">
      <label>PROVINSI</label>
        <select name="id_provinsi" id="id_provinsi" class="form-control" multiple="multiple" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id.">".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
    </div>
    <div style="float:left; padding: 10px; width:20%">
      <label>KABUPATEN</label>
        <select name="id_kabupaten" id="id_kabupaten" class="form-control" multiple="multiple" required>
        </select>
    </div>
    <div style="float:left; padding: 10px; width:25%">
      <label>PENYEDIA</label>
        <select name="id_penyedia_provinsi" id="id_penyedia_provinsi" class="form-control" multiple="multiple" required>
        <?php 
          // foreach($penyedia as $peny){
          //   echo "<option value=".$peny->id.">".$peny->nama_penyedia_provinsi."</option>";
          // }
        ?>
        </select>
    </div>
    <div style="float:left; padding: 10px; width:20%; margin-top:20px;">
      <div class="col-sm-6" style="padding-top: 10px; text-align: center; ">
        <button type="button" id="btnProses" class="button button-3d nomargin" style="background-color:green;">Proses</button>
      </div>
      <div class="col-sm-6" style="margin-top: -8px; text-align: center; ">
        <img id="ExportReporttoPDF" style="cursor: pointer;" src="<?php echo base_url('assets/ico/pdf_icon.png'); ?>" width="100px" />
      </div>
    </div>
  </div>
</div>
<div>
  <!-- <div class="report_box">
    <h4>KONTRAK</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_kontrak">0</h3>
  </div> -->
  <div class="report_box">
    <h4>JENIS BARANG</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_barang">0</h3>
  </div>
  <div class="report_box">
    <h4>DOKUMEN BAP-STHP</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_bapsthp">0</h3>
  </div>
  <div class="report_box">
    <h4>DOKUMEN BASTB</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_bastb">0</h3>
  </div>
</div>

<div style="float:left; margin-top:25px; border: 1px solid; padding:15px; box-shadow: 10px 10px 5px grey;">
  <h3> UNIT</h3>
  <hr style="border: 1px solid;">
  <!-- <div class="report_box_inner">
    <h4>KONTRAK</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_unit_kontrak">0</h3>
  </div> -->
  <div class="report_box_inner">
    <h4>ALOKASI</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_unit_alokasi">0</h3>
    <!-- <h4>( <text id="span_persen_unit_alokasi">0</text> % )</h4> -->
  </div>
  <div class="report_box_inner">
    <h4>BAP-STHP</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_unit_bapsthp">0</h3>
    <h4>( <text id="span_persen_unit_bapsthp">0</text> % )</h4>
  </div>
  <div class="report_box_inner">
    <h4>BASTB</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_unit_bastb">0</h3>
    <h4>( <text id="span_persen_unit_bastb">0</text> % )</h4>
  </div>
</div>

<div style="float:left; margin-top:25px; border: 1px solid; padding:15px; box-shadow: 10px 10px 5px grey;">
  <h3> NILAI (RP)</h3>
  <hr style="border: 1px solid;">
  <!-- <div class="report_box_inner">
    <h4>KONTRAK</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_nilai_kontrak">0</h3>
  </div> -->
  <div class="report_box_inner">
    <h4>ALOKASI</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_nilai_alokasi">0</h3>
    <!-- <h4>( <text id="span_persen_nilai_alokasi">0</text> % )</h4> -->
  </div>
  <div class="report_box_inner">
    <h4>BAP-STHP</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_nilai_bapsthp">0</h3>
    <h4>( <text id="span_persen_nilai_bapsthp">0</text> % )</h4>
  </div>
  <div class="report_box_inner">
    <h4>BASTB</h4>
    <hr style="border: 1px solid;">
    <h3 id="span_nilai_bastb">0</h3>
    <h4>( <text id="span_persen_nilai_bastb">0</text> % )</h4>
  </div>
</div>

<!-- download pdf link -->
<a id="anchorID" href="../upload/report/LaporanSummaryProvinsi.pdf" target="_blank"></a>
<!--  -->

<script>
  $(".numberFilter").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
         // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
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

  $(document).ajaxStart(function() {
    $("#disablingDiv").show();
    $("#loading").show();
  });

  $(document).ajaxStop(function() {
    $("#loading").hide();
    $("#disablingDiv").hide();
  });

  $(document).ready(function() {
    $("#loading").hide();
    $("#disablingDiv").hide();

    $('.js-example-basic-single').select2();

    $('#id_provinsi').multiselect({
      maxHeight: 300,
      includeSelectAllOption: true,
      enableFiltering: true,
      buttonWidth: '200px',
      onChange: function(element, checked, $option) {
        // alert($("#id_provinsi").val());
        LoadKabupaten();
        LoadPenyedia();
      },
      onSelectAll: function(checked) {
        LoadKabupaten();
        LoadPenyedia();
      },
      onDeselectAll: function(checked) {
        $('#id_kabupaten').multiselect('destroy');
        $('#id_kabupaten').empty();

        $('#id_penyedia_provinsi').multiselect('destroy');
        $('#id_penyedia_provinsi').empty();
      }
    });

    // $('#id_kabupaten').multiselect({
    //   maxHeight: 200,
    //   includeSelectAllOption: true
    // });
    $('#id_kabupaten').hide();
    $('#id_penyedia_provinsi').hide();
    // $('#id_penyedia_provinsi').multiselect({
    //   maxHeight: 300,
    //   includeSelectAllOption: true,
    //   enableFiltering: true,
    //   buttonWidth: '250px',
    // });

    $('.multiselect-search').keyup(function() {
    // alert('tes');
      $(this).val($(this).val().toUpperCase());
    });

  });

  function LoadKabupaten()
  {
    if($("#id_provinsi").val()){

      $.ajax({
        url: "<?php echo base_url('Kabupaten/GetByListProvinsi'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: { list_id_provinsi : $("#id_provinsi").val() },
        success: function(data) {
          $('#id_kabupaten').multiselect('destroy');
          $('#id_kabupaten').empty();
          for(var i=0;i<data.length;i++)
          {
            $('#id_kabupaten').append($('<option></option>').attr('value', data[i]["id"]).text(data[i]["nama_kabupaten"]));
          }

          $('#id_kabupaten').multiselect({
            maxHeight: 300,
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonWidth: '200px',
          });

          $('.multiselect-search').keyup(function() { $(this).val($(this).val().toUpperCase()); });

          $('#id_kabupaten').show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
      });

      var query = $('.multiselect-search').val();
      if (query) {
          $('.multiselect-search').val('').trigger('keydown');
      }

    }
    else{
      $('#id_kabupaten').multiselect('destroy');
      $('#id_kabupaten').empty();
    }
  }

  function LoadPenyedia()
  {
    if($("#id_provinsi").val()){

      $.ajax({
        url: "<?php echo base_url('PenyediaProvinsi/GetByListProvinsi'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: { list_id_provinsi : $("#id_provinsi").val() },
        success: function(data) {
          $('#id_penyedia_provinsi').multiselect('destroy');
          $('#id_penyedia_provinsi').empty();
          for(var i=0;i<data.length;i++)
          {
            $('#id_penyedia_provinsi').append($('<option></option>').attr('value', data[i]["id"]).text(data[i]["nama_penyedia_provinsi"]));
          }

          $('#id_penyedia_provinsi').multiselect({
            maxHeight: 300,
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonWidth: '200px',
          });

          $('.multiselect-search').keyup(function() { $(this).val($(this).val().toUpperCase()); });

          $('#id_penyedia_provinsi').show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
      });

      var query = $('.multiselect-search').val();
      if (query) {
          $('.multiselect-search').val('').trigger('keydown');
      }

    }
    else{
      $('#id_penyedia_provinsi').multiselect('destroy');
      $('#id_penyedia_provinsi').empty();
    }
  }

  $('#btnProses').on('click', function() {
    $.ajax({
      url: "<?php echo base_url('LaporanSummaryProvinsi/GetReportData'); ?>",
      dataType: 'JSON',
      type: 'POST',
      data: { tahun_anggaran : $("#tahun_anggaran").val() , list_id_provinsi : $("#id_provinsi").val(), list_id_kabupaten : $("#id_kabupaten").val(), list_id_penyedia : $("#id_penyedia_provinsi").val() },
      success: function(data) {
        // alert(data["count_no_kontrak"]);
        $("#span_kontrak").html(data["count_kontrak"]);
        $("#span_barang").html(data["count_barang"]);
        $("#span_bapsthp").html(data["count_bapsthp"]);
        $("#span_bastb").html(data["count_bastb"]);

        $("#span_unit_kontrak").html(data["sum_unit_kontrak"]);
        $("#span_unit_alokasi").html(data["sum_unit_alokasi"]);
        $("#span_unit_bapsthp").html(data["sum_unit_bapsthp"]);
        $("#span_unit_bastb").html(data["sum_unit_bastb"]);

        $("#span_nilai_kontrak").html(data["sum_nilai_kontrak"]);
        $("#span_nilai_alokasi").html(data["sum_nilai_alokasi"]);
        $("#span_nilai_bapsthp").html(data["sum_nilai_bapsthp"]);
        $("#span_nilai_bastb").html(data["sum_nilai_bastb"]);

        $("#span_persen_unit_alokasi").html(data["persen_unit_alokasi"]);
        $("#span_persen_unit_bapsthp").html(data["persen_unit_bapsthp"]);
        $("#span_persen_unit_bastb").html(data["persen_unit_bastb"]);

        $("#span_persen_nilai_alokasi").html(data["persen_nilai_alokasi"]);
        $("#span_persen_nilai_bapsthp").html(data["persen_nilai_bapsthp"]);
        $("#span_persen_nilai_bastb").html(data["persen_nilai_bastb"]);
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  });

  $('#ExportReporttoPDF').on('click', function() {
    $.ajax({
      url: "<?php echo base_url('LaporanSummaryProvinsi/ExportPDF'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: { tahun_anggaran : $("#tahun_anggaran").val() , list_id_provinsi : $("#id_provinsi").val(), list_id_kabupaten : $("#id_kabupaten").val(), list_id_penyedia : $("#id_penyedia_provinsi").val() },
      success: function(data) {
        if(data == 'success'){
          document.getElementById("anchorID").click();
        }
        
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  });

</script>