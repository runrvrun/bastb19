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

  .pie_progress {
    width: 100px;
    margin: 10px auto;
  }

  .search-input-text{
    border-radius: 5px;
    padding-left: 4px;
  }

  table.dataTable thead:first-child > tr:first-child > th, table.dataTable tr td:first-child {
    text-align: left; 
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
<?php if(!isset($alokasi_pusat)){ ?>
<!-- loading div -->
<div id="disablingDiv" class="disablingDiv">
</div>
<div id="loading" class="loader">
</div>
<!-- end loading div -->
<?php } ?>
<script type="text/javascript" src="<?php echo base_url('assets/asPieProgress/jquery-asPieProgress.js'); ?>"></script>
<?php if(isset($kontrak_pusat)){ ?>
<div id="headerKontrak" style="width:100%; text-align:center; border-style: solid; mergin-bottom: 40px">
  <h4><?php echo $kontrak_pusat->nama_penyedia_pusat; ?></h4>
  <h2><?php echo $kontrak_pusat->no_kontrak; ?></h2>
  <h4><?php echo date('d M Y', strtotime($kontrak_pusat->periode_mulai))." - ".date('d M Y', strtotime($kontrak_pusat->periode_selesai)); ?> 
  (tersisa <?php echo max(round((strtotime($kontrak_pusat->periode_selesai) - time()) / (60 * 60 * 24)),0) ?> hari)</h4>
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
      <b><?php echo number_format($alokasi_pusat->jumlah_barang_rev, 0); ?></b> UNIT<br>
    </button>
    <button type="button" class="btn btn-default btn-lg" style="width: 300px; height: 50px;">
      <!-- <i class="glyphicon glyphicon-usd"></i><br> -->
      Rp <b><?php echo number_format($alokasi_pusat->nilai_barang_rev, 0); ?></b><br>
    </button>
  </div>
</div>
<hr>
<?php } ?>
<div class="col-sm-12 npl npr mb20 dt-buttons">
  <?php if(isset($alokasi_pusat)){ ?>
  <div class="col-sm-6" style="height: 120px; border: solid 1px; ">
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
        <a href="<?php echo base_url('Alokasi_pusat/index_confirmed'); ?>">
        <img src="<?php echo base_url('assets/ico/back_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Kembali Ke Alokasi</span>
    </div>
    <?php if($bolehtambah) { ?>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <a href="<?php echo base_url('Bastb_pusat/create?id_alokasi=').$alokasi_pusat->id; ?>">
        <img src="<?php echo base_url('assets/ico/file_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Input BASTB</span>
    </div>
    <?php } ?>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <a href="#" data-toggle="modal" data-target="#toggle-table-column">
        <img src="<?php echo base_url('assets/ico/grid_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Ubah Tampilan Tabel</span>
    </div>
    <div class="col-sm-3" style="padding-top: 10px; text-align: center; ">
      <img id="ExportReporttoExcel" style="cursor: pointer;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <br>
      <span style="font-size:10px; font-weight: bold;">Export Excel</span>
    </div>
  </div>
  <?php if($grafik == 1) { ?>
  <div class="col-sm-6">
    <div class="col-sm-6" style="min-width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo $total_unit; ?></span></b><br> Unit
    </div>
    <div class="col-sm-6" style="min-width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef;">
      <i class="glyphicon glyphicon-usd" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 20px; "><span class="Count"><?php echo $total_nilai; ?></span></b><br> Nilai (Rp)
    </div>    
  </div>
  <?php } ?>
  <?php }else{ ?>
  <div class="col-sm-4" style="height: 120px; border: solid 1px; ">
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
  <div class="col-sm-8">
    <div class="col-sm-3" style="min-width: 120px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo $total_unit; ?></span></b><br> Unit
    </div>
    <div class="col-sm-3" style="height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef;">
      <i class="glyphicon glyphicon-usd" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 20px; "><span class="Count"><?php echo $total_nilai; ?></span></b><br> Nilai (Rp)
    </div>
    <div class="col-sm-3" style="min-width: 110px; height: 110px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef;">
        <div class="pie_progress" role="progressbar" data-goal="<?php echo($total_all_unit == 0 ? 0 : ($total_unit/$total_all_unit*100)); ?>" data-barcolor="#3daf2c" aria-valuemin="0" aria-valuemax="100">
        <span class="pie_progress__number count">0%</span>
      </div>
      % Unit
    </div>
    <div class="col-sm-3" style="min-width: 110px; height: 110px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef;">
      <div class="pie_progress" role="progressbar" data-goal="<?php echo($total_all_nilai == 0 ? 0 : ($total_nilai/$total_all_nilai*100)); ?>" data-barcolor="#3daf2c" aria-valuemin="0" aria-valuemax="100">
        <span class="pie_progress__number count">0%</span>
      </div>
      % Nilai
    </div>
  </div>
  <?php } ?>
  <?php } ?>
</div>

<div style="margin-top: 150px; ">
  <table class="table table-striped table-bordered" id="Table1">
  <thead>
    <tr>
    <?php foreach($cols as $key=>$val){ ?>
      <td><input type="text" data-column="<?php echo $key;?>"  class="search-input-text"></td>
    <?php } ?>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
    <?php foreach($cols as $key=>$val){ ?>
      <th><?php echo $val['caption'];?></th>
    <?php } ?>
      <th>Ket. Dokumen</th>
      <th>Ket. Foto</th>
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
                Hapus BASTB?
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
                Upload File
            </div>
            <div class="modal-body">
              <div class="col_full">
                <label>UPLOAD DOKUMEN</label>
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
<div class="modal fade" id="upload-modal-cad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black;">
        <div class="modal-content">
            <div class="modal-header">
                Upload File
            </div>
            <div class="modal-body">
              <div class="col_full">
                <label>UPLOAD DOKUMEN</label>
                <input type="hidden" name="id_rowcad" id="id_rowcad" value="">
                <div class="dropzone" id="myDropzonecad">
                  <img src="" width="50px"/> 
                  <div class="dz-message" data-dz-message><span>Klik atau Drop File di sini untuk Upload</span></div>
                </div>
              </div>
              <!-- tabel utk file2 yang sudah diupload -->
              <table class="table table-bordered" id="TblUploadedFilescad">
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="btn-upload-filecad" class="button button-3d nomargin">Upload</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal upload -->
<!-- modal upload foto -->
<div class="modal fade" id="upload-modalfoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black;">
        <div class="modal-content">
            <div class="modal-header">
                Upload Foto
            </div>
            <div class="modal-body">
              <div class="col_full">
                <label>UPLOAD FOTO</label>
                <input type="hidden" name="id_rowfoto" id="id_rowfoto" value="">
                <div class="dropzone" id="myDropzonefoto">
                  <img src="" width="50px"/> 
                  <div class="dz-message" data-dz-message><span>Klik atau Drop File di sini untuk Upload</span></div>
                </div>
              </div>
              <!-- tabel utk file2 yang sudah diupload -->
              <table class="table table-bordered" id="TblUploadedFilesfoto">
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="btn-upload-filefoto" class="button button-3d nomargin">Upload</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload-modalfoto-cad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black;">
        <div class="modal-content">
            <div class="modal-header">
                Upload Foto
            </div>
            <div class="modal-body">
              <div class="col_full">
                <label>UPLOAD FOTO</label>
                <input type="hidden" name="id_rowfotocad" id="id_rowfotocad" value="">
                <div class="dropzone" id="myDropzonefotocad">
                  <img src="" width="50px"/> 
                  <div class="dz-message" data-dz-message><span>Klik atau Drop File di sini untuk Upload</span></div>
                </div>
              </div>
              <!-- tabel utk file2 yang sudah diupload -->
              <table class="table table-bordered" id="TblUploadedFilesfotocad">
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="btn-upload-filefotocad" class="button button-3d nomargin">Upload</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal upload foto-->

<!-- modal preview  -->
<div class="modal fade" id="preview-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black; width: 95%;">
        <div class="modal-content" style="height: auto;">

            <div class="modal-header">
                Preview Data BASTB
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
          <?php if(!isset($alokasi_pusat)){ ?>
              <div class="row">
                  <div class="col-md-3">
                      <input class="form-check-input" name="showControl" type="checkbox" value="std" id="chkStandar">
                      <label class="form-check-label" for="chkStandar">STANDARD</label>
                  </div>
                  <div class="col-md-3">
                      <input class="form-check-input" name="showControl" type="checkbox" value="all" id="chkLengkap">
                      <label class="form-check-label" for="chkLengkap">LENGKAP</label>
                  </div>
                  <div class="col-md-3">
                      <input class="form-check-input" name="showControl" type="checkbox" value="cus" id="chkKustom">
                      <label class="form-check-label" for="chkKustom">CUSTOM</label>
                  </div>
              </div>
            <?php } ?>
            <div class="row">
            <?php foreach($cols as $key=>$val){ ?>
                <div class="col-md-3 tog-col-div">
                    <input class="form-check-input tog-col" name="show" type="checkbox" value="<?php echo $key+1;?>" id="chkShow<?php echo $val['column'];?>" checked>
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
  
  $(document).ajaxStart(function() {
    $("#disablingDiv").show();
    $("#loading").show();
  });
  $(document).ajaxStop(function() {
    $("#loading").hide();
    $("#disablingDiv").hide();
  });

  //script for dropzone
  Dropzone.options.myDropzone= {
    // The configuration we've talked about above
    url: '<?php echo base_url("Bastb_pusat/upload_file"); ?>',
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
          formData.append("id", jQuery("#id_row").val());
      });
    },
    success: function (file, response) {      
      <?php if(isset($kontrak_pusat)){ ?>
      window.location="<?php echo base_url('Bastb_pusat/index?id_alokasi='.$alokasi_pusat->id); ?>";
      <?php }else{ ?>
        window.location="<?php echo base_url('Bastb_pusat'); ?>";
      <?php } ?>
    }
  }
  Dropzone.options.myDropzonecad= {
    // The configuration we've talked about above
    url: '<?php echo base_url("Bastb_persediaan/upload_file"); ?>',
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
      document.getElementById("btn-upload-filecad").addEventListener("click", function(e) {
          // Make sure that the form isn't actually being sent.
          e.preventDefault();
          e.stopPropagation();
          myDropzone.processQueue();
      });


      //send all the form data along with the files:
      this.on("sendingmultiple", function(data, xhr, formData) {
          formData.append("id", jQuery("#id_rowcad").val());
      });
    },
    success: function (file, response) {      
        window.location.reload(true); 
    }
  }
  //script for dropzone foto
  Dropzone.options.myDropzonefoto= {
    // The configuration we've talked about above
    url: '<?php echo base_url("Bastb_pusat/upload_filefoto"); ?>',
    acceptedFiles: 'image/*,.pdf',
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 10,
    maxFiles: 10,
    // The setting up of the dropzone
    init: function() {
      var myDropzonefoto = this;

      // First change the button to actually tell Dropzone to process the queue.
      document.getElementById("btn-upload-filefoto").addEventListener("click", function(e) {
          // Make sure that the form isn't actually being sent.
          e.preventDefault();
          e.stopPropagation();
          myDropzonefoto.processQueue();
      });


      //send all the form data along with the files:
      this.on("sendingmultiple", function(data, xhr, formData) {
          formData.append("id", jQuery("#id_rowfoto").val());
      });
    },
    success: function (file, response) {      
      <?php if(isset($kontrak_pusat)){ ?>
      window.location="<?php echo base_url('Bastb_pusat/index?id_alokasi='.$alokasi_pusat->id); ?>";
      <?php }else{ ?>
        window.location="<?php echo base_url('Bastb_pusat'); ?>";
      <?php } ?>
    }
  }
  Dropzone.options.myDropzonefotocad= {
    // The configuration we've talked about above
    url: '<?php echo base_url("Bastb_persediaan/upload_filefoto"); ?>',
    acceptedFiles: 'image/*,.pdf',
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 10,
    maxFiles: 10,
    // The setting up of the dropzone
    init: function() {
      var myDropzonefoto = this;

      // First change the button to actually tell Dropzone to process the queue.
      document.getElementById("btn-upload-filefotocad").addEventListener("click", function(e) {
          // Make sure that the form isn't actually being sent.
          e.preventDefault();
          e.stopPropagation();
          myDropzonefoto.processQueue();
      });


      //send all the form data along with the files:
      this.on("sendingmultiple", function(data, xhr, formData) {
          formData.append("id", jQuery("#id_rowfotocad").val());
      });
    },
    success: function (file, response) {      
      window.location.reload(true); 
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
    "order": [],
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
        "url": '<?php echo base_url("Bastb_pusat/index_json"); ?>',
        <?php if(isset($alokasi_pusat)){ ?>
        "data": {
           id_alokasi : '<?php echo $alokasi_pusat->id; ?>',
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
        { data: "ketdokumen" },
        { data: "ketfoto" },
        { data: "tools" }
    ],

    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:last-child').attr('nowrap', 'nowrap');
    },
    "columnDefs": [ {
      "targets": 4,
      "orderable": false,
    } ],
  });
  table.on('column-visibility.dt', function (a, s) {
    const visibleColumns = { "visible_columns": [] };
    s.aoColumns.forEach(function(item) {
      if(item.bVisible && item.data !== 'tools') {
        visibleColumns.visible_columns.push({
          id: item.data,
          title: item.sTitle
        });
      }
    });
    $("#dt_columns").val(JSON.stringify(visibleColumns));
  });

  $('.search-input-text').on( 'input', function () {   // for text boxes
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
    $.ajax({
      type: "POST",
      url: '<?php echo base_url("Bastb_pusat/export"); ?>',
      success: function(data) {
        if(data.filename) window.open(data.filename,'_blank');
      }
    });
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
  $('#upload-modal-cad').on('show.bs.modal', function(e) {
      var data = $(e.relatedTarget).data();
      $('.title', this).text(data.recordTitle);
      $('#id_rowcad', this).val(data.recordId);
      showUploadedFilescad(data.recordId);
  });
  $('#upload-modalfoto').on('show.bs.modal', function(e) {
      var data = $(e.relatedTarget).data();
      $('.title', this).text(data.recordTitle);
      $('#id_rowfoto', this).val(data.recordId);
      showUploadedFilesfoto(data.recordId);
  });
  $('#upload-modalfoto-cad').on('show.bs.modal', function(e) {
      var data = $(e.relatedTarget).data();
      $('.title', this).text(data.recordTitle);
      $('#id_rowfotocad', this).val(data.recordId);
      showUploadedFilesfotocad(data.recordId);
  });

  function showUploadedFiles(id)
  {
    $.ajax({
      url: "<?php echo base_url('Bastb_pusat/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id},
      success: function(data) {
        if(data['nama_file'] != '' && data['nama_file'] != null ){

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
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/bastb_pusat/"+obj[i]+"'/></td><td>"+obj[i].substring(10)+"</td>";
            }
            content += "<td><a class='btn btn-s btn-danger' onclick='removeImage("+id+", "+i+")'><i class='glyphicon glyphicon-trash'></i></a></td>";
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
  function showUploadedFilescad(id)
  {
    $.ajax({
      url: "<?php echo base_url('Bastb_persediaan/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id},
      success: function(data) {
        if(data['nama_file'] != '' && data['nama_file'] != null ){

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
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/bastb_persediaan/"+obj[i]+"'/></td><td>"+obj[i].substring(10)+"</td>";
            }
            content += "<td><a class='btn btn-s btn-danger' onclick='removeImagecad("+id+", "+i+")'><i class='glyphicon glyphicon-trash'></i></a></td>";
            content += "</tr>";
          }
          // content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";

          // console.log(content);
                        
          $('#TblUploadedFilescad').html(content);
        }

      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert("Data Not Found");
      }
    });
  }

  function showUploadedFilesfoto(id)
  {
    $.ajax({
      url: "<?php echo base_url('Bastb_pusat/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id},
      success: function(data) {
        if(data['nama_filefoto'] != '' && data['nama_filefoto'] != null ){

          var obj = JSON.parse(data['nama_filefoto']);
          var content = "";

          for(var i=0;i<obj.length;i++)
          {
            content += "<tr>";
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'PDF' || file_format == 'pdf'){
              
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/pdflogo.png'/></td><td>"+obj[i].substring(10)+"</td>";
              
            }
            else{
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/bastb_pusat/"+obj[i]+"'/></td><td>"+obj[i].substring(10)+"</td>";
            }
            content += "<td><a class='btn btn-s btn-danger' onclick='removeImagefoto("+id+", "+i+")'><i class='glyphicon glyphicon-trash'></i></a></td>";
            content += "</tr>";
          }
          // content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";

          // console.log(content);
                        
          $('#TblUploadedFilesfoto').html(content);
        }

      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert("Data Not Found");
      }
    });
  }

  function showUploadedFilesfotocad(id)
  {
    $.ajax({
      url: "<?php echo base_url('Bastb_persediaan/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id},
      success: function(data) {
        if(data['nama_filefoto'] != '' && data['nama_filefoto'] != null ){

          var obj = JSON.parse(data['nama_filefoto']);
          var content = "";

          for(var i=0;i<obj.length;i++)
          {
            content += "<tr>";
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'PDF' || file_format == 'pdf'){
              
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/pdflogo.png'/></td><td>"+obj[i].substring(10)+"</td>";
              
            }
            else{
              content += "<td>"+(i+1)+"</td><td><img src='<?php echo base_url();?>/upload/bastb_persediaan/"+obj[i]+"'/></td><td>"+obj[i].substring(10)+"</td>";
            }
            content += "<td><a class='btn btn-s btn-danger' onclick='removeImagefotocad("+id+", "+i+")'><i class='glyphicon glyphicon-trash'></i></a></td>";
            content += "</tr>";
          }
          // content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";

          // console.log(content);
                        
          $('#TblUploadedFilesfotocad').html(content);
        }

      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert("Data Not Found");
      }
    });
  }

  function removeImage(id, urutan)
  {

    var txt = '';
    var r = confirm("Apakah anda yakin untuk menghapus gambar ini ?");
    if (r == true) {
      $.ajax({
        url: "<?php echo base_url('Bastb_pusat/remove_file'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {id : id, urutanfile: urutan},
        success: function(data) { 
          <?php if(isset($alokasi_pusat)){ ?>
          window.location="<?php echo base_url('Bastb_pusat/index?id_alokasi='.$alokasi_pusat->id); ?>";
          <?php }else{ ?>
            window.location="<?php echo base_url('Bastb_pusat'); ?>";
          <?php } ?>
        },
        error: function(jqXHR, textStatus, errorThrown) {             
          <?php if(isset($alokasi_pusat)){ ?>
          window.location="<?php echo base_url('Bastb_pusat/index?id_alokasi='.$alokasi_pusat->id); ?>";
          <?php }else{ ?>
            window.location="<?php echo base_url('Bastb_pusat'); ?>";
          <?php } ?>
        }
      });
    }
  }
  function removeImagefoto(id, urutan)
  {

    var txt = '';
    var r = confirm("Apakah anda yakin untuk menghapus gambar ini ?");
    if (r == true) {
      $.ajax({
        url: "<?php echo base_url('Bastb_pusat/remove_filefoto'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {id : id, urutanfile: urutan},
        success: function(data) { 
          <?php if(isset($alokasi_pusat)){ ?>
          window.location="<?php echo base_url('Bastb_pusat/index?id_alokasi='.$alokasi_pusat->id); ?>";
          <?php }else{ ?>
            window.location="<?php echo base_url('Bastb_pusat'); ?>";
          <?php } ?>
        },
        error: function(jqXHR, textStatus, errorThrown) {             
          <?php if(isset($alokasi_pusat)){ ?>
          window.location="<?php echo base_url('Bastb_pusat/index?id_alokasi='.$alokasi_pusat->id); ?>";
          <?php }else{ ?>
            window.location="<?php echo base_url('Bastb_pusat'); ?>";
          <?php } ?>
        }
      });
    }
  }
  function removeImagecad(id, urutan)
  {

    var txt = '';
    var r = confirm("Apakah anda yakin untuk menghapus gambar ini ?");
    if (r == true) {
      $.ajax({
        url: "<?php echo base_url('Bastb_persediaan/remove_file'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {id : id, urutanfile: urutan},
        success: function(data) { 
          window.location.reload(true); 
        },
        error: function(jqXHR, textStatus, errorThrown) {             
          window.location.reload(true); 
        }
      });
    }
  }
  function removeImagefotocad(id, urutan)
  {

    var txt = '';
    var r = confirm("Apakah anda yakin untuk menghapus gambar ini ?");
    if (r == true) {
      $.ajax({
        url: "<?php echo base_url('Bastb_persediaan/remove_filefoto'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {id : id, urutanfile: urutan},
        success: function(data) { 
          window.location.reload(true); 
       },
        error: function(jqXHR, textStatus, errorThrown) {             
          window.location.reload(true); 
        }
      });
    }
  }

  // toggle standard, custom, lengkap
  $('#chkStandar').change(function(){
    if($(this).prop("checked") == true){
      $('#chkLengkap').prop("checked", false);
      $('#chkKustom').prop("checked", false);
      $('.tog-col').prop("checked", false);
      $('#chkShowtahun_anggaran').prop("checked", true);
      $('#chkShowno_bastb').prop("checked", true);
      $('#chkShowtanggal').prop("checked", true);
      $('#chkShowkabupaten_penyerah').prop("checked", true);
      $('#chkShowpihak_penerima').prop("checked", true);
      $('#chkShowkabupaten_penerima').prop("checked", true);
      $('#chkShownama_barang').prop("checked", true);
      $('#chkShowmerk').prop("checked", true);
      $('#chkShowjumlah_barang').prop("checked", true);
      $('#chkShownilai_barang').prop("checked", true);
      $('.tog-col-div').hide();
    }
  });
  $('#chkLengkap').change(function(){
    if($(this).prop("checked") == true){
      $('#chkStandar').prop("checked", false);
      $('#chkKustom').prop("checked", false);
      $('.tog-col').prop("checked", true);
      $('.tog-col-div').hide();
    }
  });
  $('#chkKustom').change(function(){
    if($(this).prop("checked") == true){
      $('#chkLengkap').prop("checked", false);
      $('#chkStandar').prop("checked", false);
      $('.tog-col-div').show();
    }
  });
  <?php if(!isset($alokasi_pusat)){ ?>
  $(document).ready(function() {
    $('#chkStandar').prop("checked", true).trigger('change');
    $("#show-column-save-btn").click();
  });
  <?php } ?>

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

  function LoadData(id)
  {
    $.ajax({
      url: "<?php echo base_url('Bastb_pusat/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id},
      success: function(data) {

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
              content += "<div class='mySlides'><div class='numbertext'> "+(i+1)+" / "+obj.length+" </div><img id='zoom_"+i+"' src='<?php echo base_url();?>/upload/bastb_pusat/"+obj[i]+"' height='300' /><div class='text'>"+obj[i].substring(10)+"&nbsp;&nbsp;&nbsp;<a class='btn btn-xs btn-primary btn-sm' href='<?php echo base_url();?>/upload/bastb_pusat/"+obj[i]+"' download><i class='glyphicon glyphicon-save-file'></i></a></div></div>";
            }
            
          }
          content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";
          $("#slideshow_container").html(content);

          // console.log(content);

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              PDFObject.embed("<?php echo base_url();?>/upload/bastb_pusat/"+obj[i], "#embedPDF_"+i);
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

  function LoadDataCad(id)
  {
    $.ajax({
      url: "<?php echo base_url('Bastb_persediaan/get_json'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id : id},
      success: function(data) {

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
              content += "<div class='mySlides'><div class='numbertext'> "+(i+1)+" / "+obj.length+" </div><img id='zoom_"+i+"' src='<?php echo base_url();?>/upload/bastb_persediaan/"+obj[i]+"' height='300' /><div class='text'>"+obj[i].substring(10)+"&nbsp;&nbsp;&nbsp;<a class='btn btn-xs btn-primary btn-sm' href='<?php echo base_url();?>/upload/bastb_persediaan/"+obj[i]+"' download><i class='glyphicon glyphicon-save-file'></i></a></div></div>";
            }
            
          }
          content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";
          $("#slideshow_container").html(content);

          // console.log(content);

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              PDFObject.embed("<?php echo base_url();?>/upload/bastb_persediaan/"+obj[i], "#embedPDF_"+i);
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