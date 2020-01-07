<link rel="stylesheet" href="<?php echo base_url('assets/css/lightslider.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/asPieProgress/asPieProgress.css'); ?>">

<script src="<?php echo base_url('assets/js/jquery.elevatezoom.js'); ?>"></script>

<style>
  .img-zoom-container {
    position: relative;
    z-index: 9999;
  }
  .img-zoom-lens {
    position: absolute;
    border: 1px solid #d4d4d4;
    /*set the size of the lens:*/
    width: 40px;
    height: 40px;
    z-index: 9999;
  }
  .img-zoom-result {
    border: 1px solid #d4d4d4;
    /*set the size of the result div:*/
    width: 300px;
    height: 300px;
    z-index: 9999;
  }

  .zoomContainer{ z-index: 9999;}
  .zoomWindow{ z-index: 9999;}


  * {box-sizing: border-box}

  .mySlides {display: none; text-align: center;}
  img {vertical-align: middle;}

  /* Slideshow container */
  .slideshow-container {
    max-width: 1000px;
    position: relative;
    margin: auto;
  }

  /* Next & previous buttons */
  .prevpointer, .nextpointer {
    cursor: pointer;
    position: absolute;
    /*top: 50%;*/
    width: auto;
    padding: 19px;
    /*margin-top: -22px;*/
    color: white;
    background-color: rgba(0,0,0,0.8);
    font-weight: bold;
    font-size: 18px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
  }

  /* Position the "next button" to the right */
  .nextpointer {
    right: 0;
    border-radius: 3px 0 0 3px;
  }

  /* On hover, add a black background color with a little bit see-through */
  .prevpointer:hover, .nextpointer:hover {
    background-color: rgba(0,0,0,0.8);
  }

  /* Caption text */
  .text {
    /*color: #f2f2f2;*/
    color: black;
    /*text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;*/
    font-size: 13px;
    padding: 8px 12px;
    position: absolute;
    /*bottom: 8px;*/
    width: 100%;
    text-align: center;
  }

  /* Number text (1/3 etc) */
  .numbertext {
    color: #f2f2f2;
    text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
    font-size: 12px;
    padding: 8px 12px;
    position: absolute;
    top: 0;
  }

  /* The dots/bullets/indicators */
  .dot {
    cursor: pointer;
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    transition: background-color 0.6s ease;
  }

  .activeThumbs, .dot:hover {
    /*background-color: #717171;*/
  }

  /* On smaller screens, decrease text size */
  @media only screen and (max-width: 300px) {
    .prevpointer, .nextpointer,.text {font-size: 11px}
  }

  .pie_progress {
    width: 100px;
    margin: 10px auto;
  }
  @media all and (max-width: 768px) {
    .pie_progress {
      width: 80%;
      max-width: 300px;
    }
  }

  .sortable tr {
    cursor: pointer;
  }
</style>
<script>
  // script for image zooming
  function imageZoom(imgID, resultID) {
    var img, lens, result, cx, cy;
    img = document.getElementById(imgID);
    result = document.getElementById(resultID);
    /*create lens:*/
    lens = document.createElement("DIV");
    lens.setAttribute("class", "img-zoom-lens");
    /*insert lens:*/
    img.parentElement.insertBefore(lens, img);
    /*calculate the ratio between result DIV and lens:*/
    cx = result.offsetWidth / lens.offsetWidth;
    cy = result.offsetHeight / lens.offsetHeight;
    /*set background properties for the result DIV:*/
    result.style.backgroundImage = "url('" + img.src + "')";
    result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
    /*execute a function when someone moves the cursor over the image, or the lens:*/
    lens.addEventListener("mousemove", moveLens);
    img.addEventListener("mousemove", moveLens);
    /*and also for touch screens:*/
    lens.addEventListener("touchmove", moveLens);
    img.addEventListener("touchmove", moveLens);

    function moveLens(e) {
      var pos, x, y;
      /*prevent any other actions that may occur when moving over the image:*/
      e.preventDefault();
      /*get the cursor's x and y positions:*/
      pos = getCursorPos(e);
      /*calculate the position of the lens:*/
      x = pos.x - (lens.offsetWidth / 2);
      y = pos.y - (lens.offsetHeight / 2);
      /*prevent the lens from being positioned outside the image:*/
      if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
      if (x < 0) {x = 0;}
      if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
      if (y < 0) {y = 0;}
      /*set the position of the lens:*/
      lens.style.left = x + "px";
      lens.style.top = y + "px";
      /*display what the lens "sees":*/
      result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
    }

    function getCursorPos(e) {
      var a, x = 0, y = 0;
      e = e || window.event;
      /*get the x and y positions of the image:*/
      a = img.getBoundingClientRect();
      /*calculate the cursor's x and y coordinates, relative to the image:*/
      x = e.pageX - a.left;
      y = e.pageY - a.top;
      /*consider any page scrolling:*/
      x = x - window.pageXOffset;
      y = y - window.pageYOffset;
      return {x : x, y : y};
    }
  }
</script>
<div class="col-md-12 npl npr mb20 dt-buttons">
  <div class="col-sm-6" style="height: 120px; border: solid 1px; ">
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <a href="<?php echo base_url('HibahPusat'); ?>">
        <img src="<?php echo base_url('assets/ico/back_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Kembali Ke Hibah</span>
    </div>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <a href="<?php echo base_url('HibahPusat/SettingUmum'); ?>">
        <img src="<?php echo base_url('assets/ico/setting.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Setting Umum</span>
    </div>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; cursor: pointer;" onclick="checkAlokasi();">
      <img src="<?php echo base_url('assets/ico/play_green.png'); ?>" width="50px" /><br>
      <span style="font-size:10px; font-weight: bold;">Buat Dokumen</span>
    </div>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <img id="ExportReporttoExcel" style="cursor: pointer;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <br>
      <span style="font-size:10px; font-weight: bold;">Export Excel</span>
    </div>
      
  </div>

  <div style="float: right; margin-right: -50px;">
    <div class="col-sm-6" style="width: 200px; height: 150px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count" id="spanTotalUnit"></span></b><br> Unit
    </div>
    <div class="col-sm-6" style="width: 200px; height: 150px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-usd" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 20px; "><span class="Count" id="spanTotalNilai"></span></b><br> Nilai (Rp)
    </div>
  </div>
</div>
<div style="margin-top: 150px; ">
  <table class="table table-bordered sortable" id="Table1">
  <thead>
    <tr>
      <td><input type="text" data-column="0"  class="search-input-text"></td>
      <td><input type="text" data-column="1"  class="search-input-text"></td>
      <td><input type="text" data-column="2"  class="search-input-text"></td>
      <td><input type="text" data-column="3"  class="search-input-text"></td>
      <td><input type="text" data-column="4"  class="search-input-text"></td>
      <td><input type="text" data-column="5"  class="search-input-text"></td>
      <td><input type="text" data-column="6"  class="search-input-text"></td>
      <td><input type="text" data-column="7"  class="search-input-text"></td>
      <td><input type="text" data-column="8"  class="search-input-text"></td>
      <td><!-- <input type="text" data-column="9"  class="search-input-text"> --></td>
      <td><input type="text" data-column="10"  class="search-input-text"></td>
      <td><input type="text" data-column="11"  class="search-input-text"></td>
      <td><input type="text" data-column="12"  class="search-input-text"></td>
      <td><input type="text" data-column="13"  class="search-input-text"></td>
      <td><input type="text" data-column="14"  class="search-input-text"></td>
    </tr>
    <tr>
      <th>Tahun Anggaran</th>
      <th>No Kontrak</th>
      <th>Periode</th>
      <th>Nama Barang</th>
      <th>Merk</th>
      <th>Provinsi</th>
      <th>Kabupaten</th>
      <th>Alokasi</th>
      <th>Nilai (Rp)</th>
      <th style="white-space: nowrap">Harga Satuan (Rp)</th>
      <th>ID</th>
      <th>REG/CAD</th>
      <th>No Adendum</th>
      <th>Penyedia</th>
      <th>Status Rilis</th>
      <th width="40px"></th>
      <th width="40px"></th>
    </tr>
  </thead>
  <tbody>
    <?php 
      for($i=0; $i<count($alokasi_pusat); $i++){
        echo "<tr>
                <td>".$alokasi_pusat[$i]->tahun_anggaran."</td>
                <td>".$alokasi_pusat[$i]->no_kontrak."</td>
                <td>".$alokasi_pusat[$i]->periode_mulai." s/d ".$alokasi_pusat[$i]->periode_selesai."</td>
                <td>".$alokasi_pusat[$i]->nama_barang."</td>
                <td>".$alokasi_pusat[$i]->merk."</td>
                <td>".$alokasi_pusat[$i]->nama_provinsi."</td>
                <td>".$alokasi_pusat[$i]->nama_kabupaten."</td>";
          echo "<td>".number_format($alokasi_pusat[$i]->jumlah_barang_rev, 0)."</td>
                <td>".number_format($alokasi_pusat[$i]->nilai_barang_rev, 0)."</td>
                <td>".number_format(($alokasi_pusat[$i]->harga_satuan_rev), 0)."</td>";
        echo    "<td>".$alokasi_pusat[$i]->dinas."</td>
                <td>".$alokasi_pusat[$i]->regcad."</td>";

                if($alokasi_pusat[$i]->status_alokasi == 'DATA KONTRAK AWAL'){
                  echo "<td></td>";
                }
                else if($alokasi_pusat[$i]->status_alokasi == 'DATA ADENDUM 1'){
                  echo "<td>".$alokasi_pusat[$i]->no_adendum_1."</td>";
                }
                else if($alokasi_pusat[$i]->status_alokasi == 'DATA ADENDUM 2'){
                  echo "<td>".$alokasi_pusat[$i]->no_adendum_2."</td>";
                }

        echo    "<td>".$alokasi_pusat[$i]->nama_penyedia_pusat."</td>
                
                <td nowrap>";
    ?>
              <a class="btn btn-xs btn-success btn-sm"><i class="glyphicon glyphicon-zoom-in" onclick="LoadData(<?php echo $alokasi_pusat[$i]->id; ?>);"></i></a>
    <?php
        echo "</td>
              </tr>";
      }
    ?>
  </tbody>
  </table>
</div>

<form id="formBuatDokumen" method="post" action="<?php echo base_url('HibahPusat/BuatDokumen');?>">
  <input type="hidden" id="listIdAlokasi" name="listIdAlokasi" />
  <input type="hidden" id="hdnTotalUnit" name="hdnTotalUnit" />
  <input type="hidden" id="hdnTotalNilai" name="hdnTotalNilai" />
</form>

<script src="<?php echo base_url('assets/js/pdfobject.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/asPieProgress/jquery-asPieProgress.js'); ?>"></script>

<script>
  var totalunit = 0;
  var totalnilai = 0;

  $(document).ready(function() {
    $('.buttons-excel').hide();

    $('.Count').each(function () {
      $(this).prop('Counter',0).animate({
          Counter: $(this).text()
      }, {
          duration: 2000,
          easing: 'swing',
          step: function (now) {
              $(this).text(Math.ceil(now));
              $(this).number( true, 0 );
          }
      });
    });

    $('.pie_progress').asPieProgress('start');

  });

  $('.pie_progress').asPieProgress({
      namespace: 'pie_progress'
  });

  var slideIndex = 1;
  var table = $('#Table1').DataTable({
    // "pageLength": 10,
    // "lengthChange": true,
    "processing" : true,
    "serverSide" : true,
    "searching": true,
    "scrollX" : true,
    "scrollY" : 450,
    "fixedHeader" : true,
    // "dom" : 'Bfrtip',
    "order": [[ 15, "desc" ]],
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
        "url": '<?php echo base_url("Alokasi_pusat/index_hibah_json"); ?>',
        "dataType": "json",
    },
    "fnDrawCallback": function( oSettings ) {
      // alert( 'DataTables has redrawn the table' );
      markData();
    },
    rowId: "id",
    columns: [
        { data: "tahun_anggaran" },
        { data: "no_kontrak" },
        { data: "periode" },
        { data: "nama_barang" },
        { data: "merk" },
        { data: "nama_provinsi" },
        { data: "nama_kabupaten" },
        { data: "jumlah_barang" },
        { data: "nilai_barang" },
        { data: "harga_satuan" },
        { data: "dinas" },
        { data: "regcad" },
        { data: "no_adendum" },
        { data: "nama_penyedia" },
        { data: "status_rilis" },
        { data: "tools" },
        { data: "id" },

    ],
    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:eq(13)').attr('nowrap', 'nowrap');
    },
    "columnDefs": [ {
      "targets": 9,
      "orderable": false,
    } ],
    "columnDefs": [ {
      "targets": 13,
      "orderable": false,
    } ]
  });
  table.column(16).visible(false);

  var arrayId = [];

  $('#Table1 tbody').on('click', 'tr', function () {
      
      if(table.row(this).data()["status_rilis"].indexOf('BELUM')){
        var id = table.row(this).id();
        var unit = table.row(this).data()["jumlah_barang"].replace(/\,/g,"");
        var nilai = table.row(this).data()["nilai_barang"].replace(/\,/g,"");
        // alert( 'Clicked row id '+id );
        if($("#chk_"+id).is(":checked")){
          $("#chk_"+id).prop('checked', false);
          $("#"+id).css("background-color", "white");
          totalunit -= parseFloat(unit);
          totalnilai -= parseFloat(nilai);
          removeA(arrayId, id);
        }
        else{
          $("#chk_"+id).prop('checked', true);
          $("#"+id).css("background-color", "#00ff00");
          totalunit += parseFloat(unit);
          totalnilai += parseFloat(nilai);
          arrayId.push(id);
        }

        if(totalnilai > 10000000000){
          alert("Total Nilai Melebihi 10 M");
        }

        $("#listIdAlokasi").val(arrayId);
        $("#spanTotalUnit").html(numberWithCommas(totalunit));
        $("#spanTotalNilai").html(numberWithCommas(totalnilai));

        $("#hdnTotalUnit").val(totalunit);
        $("#hdnTotalNilai").val(totalnilai);
      }

     
  } );

  function markData(){
    for (i = 0; i < arrayId.length; i++) {
      // alert(arrayId[i]);
      $("#chk_"+arrayId[i]).prop('checked', true);
      $("#"+arrayId[i]).css("background-color", "#00ff00");
    }
  }

  function removeA(arr) {
      var what, a = arguments, L = a.length, ax;
      while (L > 1 && arr.length) {
          what = a[--L];
          while ((ax= arr.indexOf(what)) !== -1) {
              arr.splice(ax, 1);
          }
      }
      return arr;
  }

  
  $('.search-input-text').on( 'keyup click', function () {   // for text boxes
      var i =$(this).attr('data-column');  // getting column index
      var v =$(this).val();  // getting search input value
      table.columns(i).search(v).draw();
  } );


  $("#ExportReporttoExcel").on("click", function() {
    table.button( '.buttons-excel' ).trigger();
  });

  $("#show-column-save-btn").click(function () {
      var checked = [];

      if($("input[name='show']:checked").length == 0) {
          alert("Check column you want to show!")
      } else {
          $("#toggle-table-column").modal('hide');

          $("input[name='show']:checked").each(function (key, val) {
              var column = table.column(val.value-1);
              column.visible(true);
          });

          $("input[name='show']:not(:checked)").each(function (key, val) {
              var column = table.column(val.value-1);
              column.visible(false);
          });

      }
  });

  function checkAlokasi(){
    if( $("#spanTotalUnit").html() == "0"){
      alert("Harap pilih minimal satu alokasi !");
    }
    else{
      document.getElementById("formBuatDokumen").submit();
    }
  }

  const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
</script>