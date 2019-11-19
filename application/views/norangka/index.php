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

  .dataTables_scrollHeadInner{
    margin: 0 auto;
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
<?php if(isset($bapb)){ ?>
<div id="headerKontrak" style="width:100%; text-align:center; border-style: solid; mergin-bottom: 40px">
  <h4><?php echo $bapb->nama_penyedia_pusat; ?></h4>
  <h2><?php echo $bapb->nomor; ?></h2>
  <h4><?php echo date('d M Y', strtotime($bapb->tanggal)); ?></h4>
  <div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" class="btn btn-default btn-lg" style="width: 250px; height: 50px;white-space: normal;">
      <!-- <i class="glyphicon glyphicon-cog"></i><br> -->
      <b><?php echo $bapb->nama_barang ?></b><br>
    </button>
    <button type="button" class="btn btn-default btn-lg" style="width: 250px; height: 50px;white-space: normal;">
      <!-- <i class="glyphicon glyphicon-usd"></i><br> -->
      <b><?php echo $bapb->merk; ?></b><br>
    </button>
    <button type="button" class="btn btn-default btn-lg" style="width: 200px; height: 50px;">
      <!-- <i class="glyphicon glyphicon-usd"></i><br> -->
      <b><?php echo number_format($bapb->jumlah_unit, 0); ?></b> UNIT<br>
    </button>    
  </div>
</div>
<hr>
<?php } ?>
<div class="col-md-12 npl npr mb20 dt-buttons">
  <div class="col-sm-6" style="height: 120px; border: solid 1px; ">
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <a href="<?php echo base_url('Bapb/index?id_kontrak_pusat='.$bapb->id_kontrak_pusat); ?>">
        <img src="<?php echo base_url('assets/ico/back_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Kembali</span>
    </div>
    <?php if($bolehtambah) { ?>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <a href="<?php echo base_url('Norangka/create?id_bapb='.$bapb->id); ?>">
        <img src="<?php echo base_url('assets/ico/file_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Input No. Rangka & No. Mesin</span>
    </div>
    <?php } ?>    
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <img id="ExportReporttoExcel" style="cursor: pointer;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <br>
      <span style="font-size:10px; font-weight: bold;">Export Excel</span>
    </div>
  </a>
  </div>
  <?php if($grafik == 1) { ?>
  <div style="float: right; margin-right: -30px;">
    <div class="col-sm-6" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo count($norangka_used); ?></span></b><br> Terpakai
    </div>
  </div>
  <div style="float: right; margin-right: -30px;">
    <div class="col-sm-6" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo count($norangka); ?></span></b><br> No Rangka & No Mesin
    </div>
  </div>
  <?php } ?>
</div>

<div style="margin-top: 150px; ">
  <table class="table table-striped table-bordered" id="Table1" width="100%">
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

<!-- modal delete confirmation -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black;">
        <div class="modal-content">
            <div class="modal-header">
                Delete Confirmation
            </div>
            <div class="modal-body">
                Hapus Nomor Rangka?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<!-- end modal confirmation -->

<!-- modal upload -->
<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black;">
        <div class="modal-content">
            <div class="modal-header">
                Upload File Untuk Data Norangka <b><i class="title"></i></b>
            </div>
            <div class="modal-body">
              <div class="col_full">
                <label>UPLOAD FOTO/DOKUMEN Data Norangka</label>
                <input type="hidden" name="id_row" id="id_row" value="">
                <div class="dropzone" id="myDropzone">
                  <img src="" width="50px"/> 
                  <div class="dz-message" data-dz-message><span>Klik atau Drop File di sini untuk Upload</span></div>
                </div>
              </div>
              <!-- tabel utk file2 yang sudah diupload -->
              <table class="table table-bordered" id="TblUploadedFiles">
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="btn-upload-file" class="button button-3d nomargin">Upload</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal upload -->

<!-- modal preview  -->
<div class="modal fade" id="preview-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black; width: 95%;">
        <div class="modal-content" style="height: auto;">

            <div class="modal-header">
                Preview Data Norangka
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
                <div class="col-md-3">
                    <input class="form-check-input" name="show" type="checkbox" value="<?php echo $key+1;?>" id="chkShow<?php echo $val['column'];?>" checked>
                    <label class="form-check-label" for="chkShow<?php echo $val['column'];?>"><?php echo $val['caption'];?></label>
                </div>
            <?php } ?>
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
  //script for dropzone
  Dropzone.options.myDropzone= {
    // The configuration we've talked about above
    url: '<?php echo base_url("Norangka/upload_file"); ?>',
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
      document.getElementById("btn-upload-file").addEventListener("click", function(e) {
          // Make sure that the form isn't actually being sent.
          e.preventDefault();
          e.stopPropagation();
          myDropzone.processQueue();
      });


      //send all the form data along with the files:
      this.on("sendingmultiple", function(data, xhr, formData) {
          formData.append("id_norangka", jQuery("#id_row").val());
      });
    },
    success: function (file, response) {      
      <?php if(isset($bapb)){ ?>
      window.location="<?php echo base_url('Norangka/index?id='.$bapb->id); ?>";
      <?php }else{ ?>
        window.location="<?php echo base_url('Norangka'); ?>";
      <?php } ?>
    }
  }

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
          <?php if(isset($bapb)){ ?>         
          messageTop: 'Penyedia : <?php echo $bapb->nama_penyedia_pusat; ?> | No Kontrak : <?php echo $bapb->nomor; ?> | Tanggal : <?php echo date("d M Y", strtotime($bapb->tanggal));?> | Nama Barang : <?php echo $bapb->nama_barang ?> | Merk : <?php echo $bapb->merk; ?> | Jumlah Barang : <?php echo number_format($bapb->jumlah_barang, 0); ?> Unit',
          <?php } ?>
          exportOptions: {
              columns: 'thead th:not(.noExport)'
          }
      },
    ],
    "ajax": {
        "type": "POST",
        "url": '<?php echo base_url("Norangka/index_json"); ?>',
        <?php if(isset($bapb)){ ?>
        "data": {
           id_bapb : '<?php echo $bapb->id; ?>'
        },
        <?php } ?>
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
        { data: "<?php echo $val['column'];?>" },
      <?php } ?>
        { data: "tools" }
    ],

    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:last-child').attr('nowrap', 'nowrap');
    }
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

  function showUploadedFiles(id_norangka)
  {
    $.ajax({
      url: "<?php echo base_url('Norangka/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id_norangka},
      success: function(data) {
        if(data['nama_file'] != '' && data['nama_file'] != "null" ){

          var obj = JSON.parse(data['nama_file']);

          var content = "";

          for(var i=0;i<obj.length;i++)
          {
            content += "<tr>";
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'PDF' || file_format == 'pdf'){
              
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/pdflogo.png'/></td><td>"+obj[i].substring(10)+"</td>";
              
            }
            else{
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/sop/"+obj[i]+"'/></td><td>"+obj[i].substring(10)+"</td>";
            }
            content += "<td><a class='btn btn-s btn-danger' onclick='removeImage("+id_norangka+", "+i+")'><i class='glyphicon glyphicon-trash'></i></a></td>";
            content += "</tr>";
          }
          // content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";

          // console.log(content);
                        
          $('#TblUploadedFiles').html(content);
        }

      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Data Not Found");
      }
    });
  }

  function removeImage(id_norangka, urutan)
  {

    var txt = '';
    var r = confirm("Apakah anda yakin untuk menghapus gambar ini ?");
    if (r == true) {
      $.ajax({
        url: "<?php echo base_url('Norangka/remove_file'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {id_norangka : id_norangka, urutanfile: urutan},
        success: function(data) { 
          <?php if(isset($bapb)){ ?>
          window.location="<?php echo base_url('Norangka/index?id='.$bapb->id); ?>";
          <?php }else{ ?>
            window.location="<?php echo base_url('Norangka'); ?>";
          <?php } ?>
        },
        error: function(jqXHR, textStatus, errorThrown) {             
          <?php if(isset($bapb)){ ?>
          window.location="<?php echo base_url('Norangka/index?id='.$bapb->id); ?>";
          <?php }else{ ?>
            window.location="<?php echo base_url('Norangka'); ?>";
          <?php } ?>
        }
      });
    }
  }

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

  function LoadData(id_norangka)
  {
    $.ajax({
      url: "<?php echo base_url('Norangka/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id_norangka},
      success: function(data) {
        // console.log(data['nama_file']);
        $('#slideshow_container').html('');

        if(data['nama_file'] != '' && data['nama_file'] != null ){

          var obj = JSON.parse(data['nama_file']);

          var content = "";

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              
              content += "<div class='mySlides'><div class='numbertext'>"+(i+1)+" / "+obj.length+" </div><div id='embedPDF_"+i+"'></div><div class='text'>"+obj[i].substring(10)+"</div></div>";
              
            }
            else{
              content += "<div class='mySlides'><div class='numbertext'> "+(i+1)+" / "+obj.length+" </div><img id='zoom_"+i+"' src='<?php echo base_url();?>/upload/sop/"+obj[i]+"' height='300' /><div class='text'>"+obj[i].substring(10)+"&nbsp;&nbsp;&nbsp;<a class='btn btn-xs btn-primary btn-sm' href='<?php echo base_url();?>/upload/sop/"+obj[i]+"' download><i class='glyphicon glyphicon-save-file'></i></a></div></div>";
            }
            
          }
          content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";
          $("#slideshow_container").html(content);

          // console.log(content);

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              PDFObject.embed("<?php echo base_url();?>/upload/sop/"+obj[i], "#embedPDF_"+i);
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