<?php 
  $bolehtambah = 0;
  $bolehedit = 0;
  $bolehhapus = 0;
  $grafik = 0;

  if($this->session->userdata('logged_in')->crud_items){
    foreach($this->session->userdata('logged_in')->crud_items as $crud){
      if(rtrim($crud->crud_action) == 'TAMBAH DATA')
        $bolehtambah = 1;
      if(rtrim($crud->crud_action) == 'EDIT DATA')
        $bolehedit = 1;
      if(rtrim($crud->crud_action) == 'HAPUS DATA')
        $bolehhapus = 1;
      if(rtrim($crud->crud_action) == 'GRAFIK PENCAPAIAN')
        $grafik = 1;
    }
  }
  
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/lightslider.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/dropzone.css'); ?>">

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

  .search-input-text{
    border-radius: 5px;
    padding-left: 4px;
  }

  table.dataTable thead:first-child > tr:first-child > th, table.dataTable tr td:first-child {
    text-align: left; 
  }
</style>
<link rel="stylesheet" href="<?php echo base_url('assets/asPieProgress/asPieProgress.css'); ?>">
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
<script type="text/javascript" src="<?php echo base_url('assets/asPieProgress/jquery-asPieProgress.js'); ?>"></script>
<div class="col-md-12 npl npr mb20 dt-buttons">

  <div class="col-sm-3" style="height: 120px; border: solid 1px; ">
    <div class="col-sm-6" style="padding-top: 10px; text-align: center; ">
      <a href="#" data-toggle="modal" data-target="#toggle-table-column">
        <img src="<?php echo base_url('assets/ico/grid_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Ubah Tampilan Tabel</span>
    </div>
    <div class="col-sm-6" style="padding-top: 10px; text-align: center; ">
      <img id="ExportReporttoExcel" style="cursor: pointer;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <br>
      <span style="font-size:10px; font-weight: bold;">Export Excel</span>
    </div>
      
  </div>
  <?php if($grafik == 1) { ?>
  <div style="float: right; margin-right: -30px;">
    <div class="col-sm-3" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo $alokasi; ?></span></b><br> Alokasi
    </div>
    <div class="col-sm-3" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef;">
      <i class="glyphicon glyphicon-usd" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 20px; "><span class="Count"><?php echo $pagu; ?></span></b><br> Pagu
    </div>
    <div class="col-sm-3" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef;">
      <i class="glyphicon glyphicon-usd" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 20px; "><span class="Count"><?php echo $pagu - $alokasi; ?></span></b><br> Sisa
    </div>
  </div>
  <?php } ?>
</div>
<div style="margin-top: 150px; ">
  <table class="table table-striped table-bordered" id="Table1">
  <thead>
    <tr>
    <?php foreach($cols as $key=>$val){ ?>
      <?php if($val['column']=='periode_selesai') continue; ?>
      <td><input type="text" data-column="<?php echo $key;?>"  class="search-input-text"></td>
    <?php } ?>
    <td><input type="text" data-column="sisa"  class="search-input-text"></td>
    </tr>
    <tr>
    <?php foreach($cols as $key=>$val){ ?>
      <?php if($val['column']=='periode_selesai') continue; ?>
      <th><?php echo $val['caption'];?></th>
    <?php } ?>
      <th>Sisa</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
  </table>
</div>

<!-- modal toggle table column -->
<div class="modal fade" id="toggle-table-column" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="color:black; width: 1000px;">
      <div class="modal-content">
          <div class="modal-header">
              Show / Hide Column
          </div>
          <div class="modal-body" style="width: 1000px; ">
            <div class="row">
            <?php foreach($cols as $key=>$val){ ?>
            <?php if($val['column']=='periode_selesai') continue; ?>
                <div class="col-md-3">
                    <input class="form-check-input" name="show" type="checkbox" value="<?php echo $key+1;?>" id="chkShow<?php echo $val['column'];?>" checked>
                    <label class="form-check-label" for="chkShow<?php echo $val['column'];?>"><?php echo $val['caption'];?></label>
                </div>
            <?php } ?>
              <div class="col-md-3">
                    <input class="form-check-input" name="show" type="checkbox" value="<?php echo $key+1;?>" id="chkShowsisa" checked>
                    <label class="form-check-label" for="chkShowsisa">Sisa</label>
                </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-success" id="show-column-save-btn">Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>
<!-- end modal toggle table column -->

<script src="<?php echo base_url('assets/js/dropzone.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/pdfobject.min.js'); ?>"></script>

<script>
  var isExport = 0;
  var currlen = 10;
  
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

    var column = $('<td>'); 
    $(column).attr("align","left");
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
    "autoWidth": false,
    "columnDefs": [
            { width: 10, targets: 1 }
        ],
    "buttons": [
      {
          extend: 'excel',
          exportOptions: {
              columns: 'thead th:not(.noExport)'
          }
      },
    ],
    "ajax": {
        "type": "POST",
        "url": '<?php echo base_url("Alokasi_persediaan_pusat/rekap_json"); ?>',
        "dataType": "json",
    },
    "drawCallback": function( settings ) {
        if(isExport == 1){
          table.button( '.buttons-excel' ).trigger();
          isExport = 0;
          table.page.len( currlen ).draw();
        }
        
    },
    columns: [
      <?php foreach($cols as $val){ ?>
      <?php if($val['column']=='periode_selesai') continue; ?>
        { data: "<?php echo $val['column'];?>" },
      <?php } ?>
      { data: "sisa" },
    ],

    createdRow: function( row, data, dataIndex ) {
    },
  });

  $('.search-input-text').on( 'keyup click', function () {   // for text boxes
      var i =$(this).attr('data-column');  // getting column index
      var v =$(this).val();  // getting search input value
      table.columns(i).search(v).draw();
  } );

  $('.search-input-select').on( 'change', function () {   // for text boxes
      var i =$(this).attr('data-column');  // getting column index
      var v =$(this).val();  // getting search input value
      table.columns(i).search(v).draw();
  } );

  $("#ExportReporttoExcel").on("click", function() {
    isExport = 1;
    currlen = table.page.len();
    table.page.len( -1 ).draw();
    
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

</script>