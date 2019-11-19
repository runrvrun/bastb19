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

  .table-cell-percent{
    /*background-color: lightgoldenrodyellow;*/
    background-color: #eaeaeb;
  }
</style>
<!-- loading div -->
<div id="disablingDiv" class="disablingDiv">
</div>
<div id="loading" class="loader">
</div>
<!-- end loading div -->
<div id="headerKontrak">
  <div class="btn-group" role="group" aria-label="Basic example">    
    <div class="form-group">
      <label>PROVINSI</label>
        <select name="id_provinsi" id="id_provinsi" class="form-control" multiple="multiple" required>
        <?php 
          foreach($provinsi as $prov){
            echo "<option value=".$prov->id.">".$prov->nama_provinsi."</option>";
          }
        ?>
        </select>
        <button type="button" id="btnProses" class="button button-3d nomargin" style="background-color:green;">Proses</button>
    </div>
  </div>
</div>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item active">
    <a class="nav-link active" id="unit-tab" data-toggle="tab" href="#unit" role="tab" aria-controls="unit" aria-selected="true">Unit</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="nilai-tab" data-toggle="tab" href="#nilai" role="tab" aria-controls="nilai" aria-selected="false">Nilai</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade in active" id="unit" role="tabpanel" aria-labelledby="unit-tab">
      <div style="height: 725px; padding: 20px; border: 1px solid; margin-top: 20px; border-radius: 20px; box-shadow: 10px 10px 5px grey;">
      <h3>Unit</h3>
      <img id="ExportReporttoExcelUnit" style="cursor: pointer; float: right; margin-top: -50px;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <hr>
      <table class="table table-bordered" id="tableUnit" style="width:100%">
        <thead>
          <tr>
            <th>Provinsi</th>
            <th>Kabupaten/Kota</th>
            <th>Alokasi Reguler</th>
            <th>BAPHP Reguler</th>
            <th>(%)</th>
            <th>BASTB Reguler</th>
            <th>(%)</th>
            <th>Alokasi Persediaan</th>
            <th>BAPHP Persediaan</th>
            <th>(%)</th>
            <th>BASTB Persediaan</th>
            <th>(%)</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  <div class="tab-pane fade in" id="nilai" role="tabpanel" aria-labelledby="nilai-tab">
    <div style="height: 725px; padding: 20px; border: 1px solid; margin-top: 20px; border-radius: 20px; box-shadow: 10px 10px 5px grey;">
      <h3>Nilai (RP)</h3>
      <img id="ExportReporttoExcelNilai" style="cursor: pointer; float: right; margin-top: -50px;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <hr>
      <table class="table table-bordered" id="tableNilai" style="width:100%">
        <thead>
          <tr>
            <th>Provinsi</th>
            <th>Kabupaten/Kota</th>
            <th>Alokasi Reguler</th>
            <th>BAPHP Reguler</th>
            <th>(%)</th>
            <th>BASTB Reguler</th>
            <th>(%)</th>
            <th>Alokasi Persediaan</th>
            <th>BAPHP Persediaan</th>
            <th>(%)</th>
            <th>BASTB Persediaan</th>
            <th>(%)</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ajaxStart(function() {
    $("#disablingDiv").show();
    $("#loading").show();
  });

  $(document).ajaxStop(function() {
    $("#loading").hide();
    $("#disablingDiv").hide();
  });

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
  var isExportUnit = 0;
  var tableUnit = $('#tableUnit').DataTable({
    // "pageLength": 10,
    // "lengthChange": true,
    "processing" : true,
    "serverSide" : true,
    "searching": true,
    "scrollY" : 450,
    "fixedHeader" : true,
    "scrollX" : true,
    "buttons": [
      {
          extend: 'excel',
          exportOptions: {
              columns: ':visible'
          }
      },
    ],
    "ajax": {
        "type": "POST",
        "url": '<?php echo base_url("LaporanKabupatenPusat/AjaxGetDataUnit"); ?>',
        "dataType": "json",
        "data":function(data) {
              data.barang = $('#list_nama_barang').val();
              data.provinsi = $('#id_provinsi').val();
        },
    },
    "drawCallback": function( settings ) {
        if(isExportUnit == 1){
          tableUnit.button( '.buttons-excel' ).trigger();
          isExportUnit = 0;
          tableUnit.page.len( currlenUnit ).draw();
        }
        
    },
    columns: [
        { data: "provinsi" },
        { data: "kabupaten" },
        { data: "alokasi" },
        { data: "baphp" },
        { data: "persen_baphp", "className": "table-cell-percent" },
        { data: "bastb" },
        { data: "persen_bastb", "className": "table-cell-percent" },
        { data: "alokasicad" },
        { data: "baphpcad" },
        { data: "persen_baphpcad", "className": "table-cell-percent" },
        { data: "bastbcad" },
        { data: "persen_bastbcad", "className": "table-cell-percent" },
    ],
    columnDefs: [ 
      {"targets": 2, "orderable": false}, 
      {"targets": 3, "orderable": false}, 
      {"targets": 4, "orderable": false}, 
      {"targets": 5, "orderable": false}, 
      {"targets": 6, "orderable": false}, 
      {"targets": 7, "orderable": false}, 
      {"targets": 8, "orderable": false}, 
      {"targets": 9, "orderable": false}, 
      {"targets": 10, "orderable": false}, 
      {"targets": 11, "orderable": false}, 
    ],
  });

  var currlenUnit = 10;

  $("#ExportReporttoExcelUnit").on("click", function() {
    isExportUnit = 1;
    currlenUnit = tableUnit.page.len();
    tableUnit.page.len( -1 ).draw();
    
  });

  var isExportNilai = 0;
  var tableNilai = $('#tableNilai').DataTable({
    // "pageLength": 10,
    // "lengthChange": true,
    "processing" : true,
    "serverSide" : true,
    "searching": true,
    "scrollY" : 450,
    "fixedHeader" : true,
    "scrollX" : true,
    "buttons": [
      {
          extend: 'excel',
          exportOptions: {
              columns: ':visible'
          }
      },
    ],
    "ajax": {
        "type": "POST",
        "url": '<?php echo base_url("LaporanKabupatenPusat/AjaxGetDataNilai"); ?>',
        "dataType": "json",
        "data":function(data) {
              data.barang = $('#list_nama_barang').val();
              data.provinsi = $('#id_provinsi').val();
        },
    },
    "drawCallback": function( settings ) {
        if(isExportNilai == 1){
          tableNilai.button( '.buttons-excel' ).trigger();
          isExportNilai = 0;
          tableNilai.page.len( currlenNilai ).draw();
        }
        
    },
    columns: [
        { data: "provinsi_nilai" },
        { data: "kabupaten_nilai" },
        { data: "alokasi_nilai" },
        { data: "baphp_nilai" },
        { data: "persen_baphp_nilai", "className": "table-cell-percent" },
        { data: "bastb_nilai" },
        { data: "persen_bastb_nilai", "className": "table-cell-percent" },
        { data: "alokasicad_nilai" },
        { data: "baphpcad_nilai" },
        { data: "persen_baphpcad_nilai", "className": "table-cell-percent" },
        { data: "bastbcad_nilai" },
        { data: "persen_bastbcad_nilai", "className": "table-cell-percent" },
    ],
    columnDefs: [ 
      {"targets": 2, "orderable": false}, 
      {"targets": 3, "orderable": false}, 
      {"targets": 4, "orderable": false}, 
      {"targets": 5, "orderable": false}, 
      {"targets": 6, "orderable": false}, 
      {"targets": 7, "orderable": false}, 
      {"targets": 8, "orderable": false}, 
      {"targets": 9, "orderable": false}, 
      {"targets": 10, "orderable": false}, 
      {"targets": 11, "orderable": false}, 
    ],
  });

  var currlenNilai = 10;

  $("#ExportReporttoExcelNilai").on("click", function() {
    isExportNilai = 1;
    currlenNilai = tableNilai.page.len();
    tableNilai.page.len( -1 ).draw();
    
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href") // activated tab
    if(target == "#nilai"){
      tableNilai.draw();
    }
    if(target == "#unit"){
      tableUnit.draw();
    }
  });

  $(document).ready(function() {
    // $("#loading").hide();
    // $("#disablingDiv").hide();
    // tableUnit.clear().draw();
    $('.buttons-excel').hide();
    $('.js-example-basic-single').select2();

    // $('#list_nama_barang').multiselect({
    //   maxHeight: 300,
    //   includeSelectAllOption: true,
    //   enableFiltering: true, 
    //   buttonWidth: '250px',
    // });

    $('#id_provinsi').multiselect({
      maxHeight: 300,
      includeSelectAllOption: true,
      enableFiltering: true,
      buttonWidth: '200px',
      onChange: function(element, checked, $option) {
        LoadBarang();
      },
      onSelectAll: function(checked) {
        LoadBarang();
      },
      onDeselectAll: function(checked) {
        $('#list_nama_barang').multiselect('destroy');
        $('#list_nama_barang').empty();
      }
    });

    $('.multiselect-search').keyup(function() {
      $(this).val($(this).val().toUpperCase());
    });

  });

  function LoadBarang()
  {
    if($("#id_provinsi").val()){

      $.ajax({
        url: "<?php echo base_url('JenisBarangProvinsi/GetByListProvinsi'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: { list_id_provinsi : $("#id_provinsi").val() },
        success: function(data) {
          $('#list_nama_barang').multiselect('destroy');
          $('#list_nama_barang').empty();
          for(var i=0;i<data.length;i++)
          {
            $('#list_nama_barang').append($('<option></option>').attr('value', data[i]["nama_barang"]).text(data[i]["nama_barang"]));
          }

          $('#list_nama_barang').multiselect({
            maxHeight: 300,
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonWidth: '200px',
          });

          $('.multiselect-search').keyup(function() { $(this).val($(this).val().toUpperCase()); });

          $('#list_nama_barang').show();
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
      $('#list_nama_barang').multiselect('destroy');
      $('#list_nama_barang').empty();
    }
  }

  $('#btnProses').on('click', function() {
    // alert( $("#list_nama_barang").val() );
      tableUnit.draw();
      tableNilai.draw()
  });
</script>