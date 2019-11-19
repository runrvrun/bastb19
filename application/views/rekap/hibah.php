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
  .sisamasa{
    color: #dd380f !important;
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
<div id="headerKontrak" style="width:100%; text-align:center; border-style: solid; mergin-bottom: 40px">
  <h4><?php echo $kontrak_pusat->nama_penyedia_pusat; ?></h4>
  <h2><?php echo $kontrak_pusat->no_kontrak; ?></h2>
  <h4><?php echo date('d M Y', strtotime($kontrak_pusat->periode_mulai))." - ".date('d M Y', strtotime($kontrak_pusat->periode_selesai)); ?></h4>
  <div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" class="btn btn-default btn-lg" style="width: 250px; height: 50px;white-space: normal;">
      <!-- <i class="glyphicon glyphicon-cog"></i><br> -->
      <b><?php echo $kontrak_pusat->nama_barang ?></b><br>
    </button>
    <button type="button" class="btn btn-default btn-lg" style="width: 250px; height: 50px;white-space: normal;">
      <!-- <i class="glyphicon glyphicon-usd"></i><br> -->
      <b><?php echo $kontrak_pusat->merk; ?></b><br>
    </button>
    <button type="button" class="btn btn-default btn-lg" style="width: 200px; height: 50px;">
      <!-- <i class="glyphicon glyphicon-usd"></i><br> -->
      <b><?php echo number_format($kontrak_pusat->jumlah_barang, 0); ?></b> UNIT<br>
    </button>
    <button type="button" class="btn btn-default btn-lg" style="width: 300px; height: 50px;">
      <!-- <i class="glyphicon glyphicon-usd"></i><br> -->
      Rp <b><?php echo number_format($kontrak_pusat->nilai_barang, 0); ?></b><br>
    </button>
  </div>
</div>
<hr>
<div class="col-md-12 npl npr mb20 dt-buttons">
  <div class="col-sm-6" style="height: 120px; border: solid 1px; ">
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <a href="<?php echo base_url('Kontrak_pusat'); ?>">
        <img src="<?php echo base_url('assets/ico/back_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Kembali Ke Kontrak</span>
    </div>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <img id="ExportReporttoExcel" style="cursor: pointer;" src="<?php echo base_url('assets/ico/downloaddoc.png'); ?>" width="50px" />
      <br>
      <span style="font-size:10px; font-weight: bold;">Download Dokumen</span>
    </div>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <img id="ExportReporttoExcel" style="cursor: pointer;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <br>
      <span style="font-size:10px; font-weight: bold;">Export Excel</span>
    </div>
  </div>
  <?php if($grafik == 1) { ?>
  <div style="float: right; margin-right: -30px;">
    <div class="col-sm-6" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo $total_unit; ?></span></b><br> Unit
    </div>
    <div class="col-sm-6" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef;">
      <i class="glyphicon glyphicon-usd" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 20px; "><span class="Count"><?php echo $total_nilai; ?></span></b><br> Nilai (Rp)
    </div>
  </div>
  <?php } ?>
</div>

<ul class="nav nav-tabs">
  <li><a href="<?php echo base_url('Rekap/alokasi?id_kontrak_pusat='.$kontrak_pusat->id);?>">Alokasi & SP2D</a></li>
  <li><a href="<?php echo base_url('Rekap/baphp_reguler?id_kontrak_pusat='.$kontrak_pusat->id);?>">BAPHP Reguler</a></li>
  <li><a href="<?php echo base_url('Rekap/baphp_persediaan?id_kontrak_pusat='.$kontrak_pusat->id);?>">BAPHP Persediaan</a></li>
  <li><a href="<?php echo base_url('Rekap/bastb?id_kontrak_pusat='.$kontrak_pusat->id);?>">BASTB</a></li>
  <li class="active"><a href="<?php echo base_url('Rekap/hibah?id_kontrak_pusat='.$kontrak_pusat->id);?>">Hibah</a></li>
  <li><a href="<?php echo base_url('Rekap/data_ongkir?id_kontrak_pusat='.$kontrak_pusat->id);?>">Data Ongkir</a></li>
  <li><a href="<?php echo base_url('Rekap/pemanfaatan?id_kontrak_pusat='.$kontrak_pusat->id);?>">Pemanfaatan</a></li>
</ul>

<div style="margin-top: 20px; ">
  <table class="table table-striped table-bordered" id="Table1">
  <thead>
    <tr>
      <?php foreach($cols as $key=>$val){ ?>
        <td><input type="text" data-column="<?php echo $key;?>"  class="search-input-text"></td>
      <?php } ?>
        <td></td>
    </tr>
    <tr>
      <?php foreach($cols as $key=>$val){ ?>
        <th><?php echo $val['caption'];?></th>
      <?php } ?>
      <th class="noExport"></th>
    </tr>
  </thead>
  <tbody>
  </tbody>
  </table>
</div>

<!-- modal preview  -->
<div class="modal fade" id="preview-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black; width: 95%;">
        <div class="modal-content" style="height: auto;">

            <div class="modal-header">
                Preview Data
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-4">

                  <div class="slideshow-container" id="slideshow_container">


                  </div>
                  <br>

                  <div style="text-align:center" id="slideshow_thumb">
                  </div>        
                  
                </div>
                <div class="col-sm-4">
                <table class="table table-striped">
                  <?php foreach($cols as $key=>$val){ ?>
                    <tr>
                      <td><?php echo $val['caption'];?></td>
                      <td>:</td>
                      <td id="prev_<?php echo $val['column'];?>"></td>
                    </tr>
                  <?php } ?>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal preview -->

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
    "buttons": [
      {
          extend: 'excel',
          <?php if(isset($kontrak_pusat)){ ?>         
          messageTop: 'Penyedia : <?php echo $kontrak_pusat->nama_penyedia_pusat; ?> | No Kontrak : <?php echo $kontrak_pusat->no_kontrak; ?> | Periode : <?php echo date("d M Y", strtotime($kontrak_pusat->periode_mulai))." - ".date("d M Y", strtotime($kontrak_pusat->periode_selesai)); ?> | Nama Barang : <?php echo $kontrak_pusat->nama_barang ?> | Merk : <?php echo $kontrak_pusat->merk; ?> | Jumlah Barang : <?php echo number_format($kontrak_pusat->jumlah_barang, 0); ?> Unit | Nilai Barang : Rp  <?php echo number_format($kontrak_pusat->nilai_barang, 0); ?>',
          <?php } ?>
          exportOptions: {
              columns: 'thead th:not(.noExport)'
          }
      },
    ],
    "ajax": {
        "type": "POST",
        "url": '<?php echo base_url("Rekap/hibah_json"); ?>',
        "data": {
           id_kontrak_pusat : '<?php echo $kontrak_pusat->id; ?>',
        },
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
    <?php foreach($cols as $key=>$val){ ?>
        { data: "<?php echo $val['column']; ?>" },
    <?php } ?>
      { data: "tools" },
    ],

    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:last-child').attr('nowrap', 'nowrap');
    },
    "columnDefs": [ {
      "targets": 4,
      "orderable": false,
    } ],
    "columnDefs": [ {
      "targets": 10,
      "orderable": false,
    } ],
    "columnDefs": [ {
      "targets": 14,
      "orderable": false,
    } ],

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

  $('#confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

      $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
  });

  $('#confirm-delete').on('show.bs.modal', function(e) {
      var data = $(e.relatedTarget).data();
      $('.title', this).text(data.recordTitle);
  });

  $('#upload-modal').on('show.bs.modal', function(e) {
      var data = $(e.relatedTarget).data();
      $('.title', this).text(data.recordTitle);
      $('#id_row', this).val(data.recordId);
      showUploadedFiles(data.recordId);
  });

  function LoadData(id)
  {
    $.ajax({
      url: "<?php echo base_url('HibahPusat/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id},
      success: function(data) {

        $('#slideshow_container').html('');

        if(data['nama_file'] != '' && data['nama_file'] != "null" ){

          var obj = JSON.parse(data['nama_file']);

          var content = "";

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              
              content += "<div class='mySlides'><div class='numbertext'>"+(i+1)+" / "+obj.length+" </div><div id='embedPDF_"+i+"'></div><div class='text'>"+obj[i].substring(10)+"</div></div>";
              
            }
            else{
              content += "<div class='mySlides'><div class='numbertext'> "+(i+1)+" / "+obj.length+" </div><img id='zoom_"+i+"' src='<?php echo base_url();?>/upload/alokasi_pusat/"+obj[i]+"' height='300' /><div class='text'>"+obj[i].substring(10)+"&nbsp;&nbsp;&nbsp;<a class='btn btn-xs btn-primary btn-sm' href='<?php echo base_url();?>/upload/alokasi_pusat/"+obj[i]+"' download><i class='glyphicon glyphicon-save-file'></i></a></div></div>";
            }
            
          }
          content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";
          $("#slideshow_container").html(content);

          // console.log(content);

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              PDFObject.embed("<?php echo base_url();?>/upload/alokasi_pusat/"+obj[i], "#embedPDF_"+i);
              $('#embedPDF_'+i).height(350);
            }
            else{
              var img = document.getElementById('zoom_'+i);
              var width = img.naturalWidth;
              var height = img.naturalHeight;

              // alert(width+" "+height);
              var offset = 550;
              if(width == height){
                offset = 600;
              }
              else if(height > width){
                offset = 600 + (width / height * 150);
              }
              else if(width > height){
                offset = 600 - (width  / height * 90);
                // offset = 550;
              }


              $("#zoom_"+i).elevateZoom({
                // zoomWindowPosition: "zoom-container", 
                // zoomWindowHeight: 200, 
                // zoomWindowWidth:200, 
                borderSize: 0, 
                easing:true,
                zoomWindowPosition: 2, 
                zoomWindowOffetx: offset,
                // scrollZoom : true,
              });
            }
          }
                        
          
        }

        <?php foreach($cols as $key=>$val){ ?>
        $('#prev_<?php echo $val['column'];?>').html(data['<?php echo $val['column'];?>']);
        <?php } ?>

        $('#preview-data').modal('show');

        if(data['nama_file'] != '' && data['nama_file'] != "null" ){
          showSlides(1);
        }
        $(window).resize();
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert("Data Not Found");
      }
    });
  }

  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}    
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" activeThumbs", "");
    }
    slides[slideIndex-1].style.display = "block";  
    // dots[slideIndex-1].className += " activeThumbs";
  }
  // imageZoom("myimage", "myresult"); 

</script>