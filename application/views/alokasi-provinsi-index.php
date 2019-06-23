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
<!-- loading div -->
<div id="disablingDiv" class="disablingDiv">
</div>
<div id="loading" class="loader">
</div>
<!-- end loading div -->
<div class="col-md-12 npl npr mb20 dt-buttons">
  <div class="col-sm-3" style="height: 120px; border: solid 1px; ">
    <div class="col-sm-5" style="padding-top: 10px; text-align: center; ">
      <a href="#" data-toggle="modal" data-target="#toggle-table-column">
        <img src="<?php echo base_url('assets/ico/grid_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Ubah Tampilan Tabel</span>
    </div>
    <div class="col-sm-5" style="padding-top: 10px; text-align: center; ">
      <img id="ExportReporttoExcel" style="cursor: pointer;" src="<?php echo base_url('assets/ico/excel_icon.png'); ?>" width="50px" />
      <br>
      <span style="font-size:10px; font-weight: bold;">Export Excel</span>
    </div>
      
  </div>
  <?php if($grafik == 1) { ?>
  <div style="float: right; margin-right: -50px;">
    <div class="col-sm-6" style="width: 200px; height: 150px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-cog" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo $total_unit; ?></span></b><br> Unit
    </div>
    <div class="col-sm-6" style="width: 200px; height: 150px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-usd" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 20px; "><span class="Count"><?php echo $total_nilai; ?></span></b><br> Nilai (Rp)
    </div>
    <div class="col-sm-6" style="width: 200px; height: 150px; text-align: center; border-right: solid; border-right-color: #efefef; ">
      <div class="pie_progress" role="progressbar" data-goal="<?php echo($total_unit_kontrak == 0 ? 0 : ($total_unit/$total_unit_kontrak*100)); ?>" data-barcolor="#3daf2c" aria-valuemin="0" aria-valuemax="100">
        <span class="pie_progress__number count">0%</span>
      </div>
      % Unit
    </div>
    <div class="col-sm-6" style="width: 200px; height: 150px; text-align: center; border-right: solid; border-right-color: #efefef; ">
      <div class="pie_progress" role="progressbar" data-goal="<?php echo($total_nilai_kontrak == 0 ? 0 : ($total_nilai/$total_nilai_kontrak*100)); ?>" data-barcolor="#3daf2c" aria-valuemin="0" aria-valuemax="100">
        <span class="pie_progress__number count">0%</span>
      </div>
      % Nilai (Rp)
    </div>
    <!-- <div class="col-sm-6" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; border-right: solid; border-right-color: #efefef; ">
      <i class="glyphicon glyphicon-briefcase" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo $total_kontrak; ?></span></b><br> Kontrak
    </div>
    <div class="col-sm-6" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; ">
      <i class="glyphicon glyphicon-tags" style="font-size: 30px; color: green; "></i><br>
      <b style="font-size: 22px; "><span class="Count"><?php echo $total_merk; ?></span></b><br> Merk
    </div> -->
  </div>
  <?php } ?>
</div>
<div style="margin-top: 150px; ">
  <input id="dt_search_input" type="hidden" />
  <input id="dt_columns" type="hidden" />
  <table class="table table-striped table-bordered" id="Table1">
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
      <th width="40px"></th>
    </tr>
  </thead>
  <tbody>
    <?php 
      for($i=0; $i<count($alokasi_provinsi); $i++){
        echo "<tr>
                <td>".$alokasi_provinsi[$i]->tahun_anggaran."</td>
                <td>".$alokasi_provinsi[$i]->no_kontrak."</td>
                <td>".$alokasi_provinsi[$i]->periode_mulai." s/d ".$alokasi_provinsi[$i]->periode_selesai."</td>
                <td>".$alokasi_provinsi[$i]->jenis_barang."</td>
                <td>".$alokasi_provinsi[$i]->merk."</td>
                <td>".$alokasi_provinsi[$i]->nama_provinsi."</td>
                <td>".$alokasi_provinsi[$i]->nama_kabupaten."</td>";

                if($alokasi_provinsi[$i]->status_alokasi == 'DATA KONTRAK AWAL'){
                  echo "<td>".number_format($alokasi_provinsi[$i]->jumlah_barang_detail, 0)."</td>
                        <td>".number_format($alokasi_provinsi[$i]->nilai_barang_detail, 0)."</td>
                        <td>".number_format(($alokasi_provinsi[$i]->nilai_barang_detail/$alokasi_provinsi[$i]->jumlah_barang_detail), 0)."</td>";
                }
                else if($alokasi_provinsi[$i]->status_alokasi == 'DATA ADENDUM 1'){
                  echo "<td>".number_format($alokasi_provinsi[$i]->jumlah_barang_rev_1, 0)."</td>
                        <td>".number_format($alokasi_provinsi[$i]->nilai_barang_rev_1, 0)."</td>
                        <td>".number_format(($alokasi_provinsi[$i]->nilai_barang_rev_1/$alokasi_provinsi[$i]->jumlah_barang_rev_1), 0)."</td>";
                }
                else if($alokasi_provinsi[$i]->status_alokasi == 'DATA ADENDUM 2'){
                  echo "<td>".number_format($alokasi_provinsi[$i]->jumlah_barang_rev_2, 0)."</td>
                        <td>".number_format($alokasi_provinsi[$i]->nilai_barang_rev_2, 0)."</td>
                        <td>".number_format(($alokasi_provinsi[$i]->nilai_barang_rev_2/$alokasi_provinsi[$i]->jumlah_barang_rev_2), 0)."</td>";
                }

        echo    "<td>".$alokasi_provinsi[$i]->dinas."</td>
                <td>".$alokasi_provinsi[$i]->regcad."</td>";

                if($alokasi_provinsi[$i]->status_alokasi == 'DATA KONTRAK AWAL'){
                  echo "<td></td>";
                }
                else if($alokasi_provinsi[$i]->status_alokasi == 'DATA ADENDUM 1'){
                  echo "<td>".$alokasi_provinsi[$i]->no_adendum_1."</td>";
                }
                else if($alokasi_provinsi[$i]->status_alokasi == 'DATA ADENDUM 2'){
                  echo "<td>".$alokasi_provinsi[$i]->no_adendum_2."</td>";
                }

        echo    "<td>".$alokasi_provinsi[$i]->nama_penyedia_provinsi."</td>
                
                <td nowrap>";
    ?>
              <a class="btn btn-xs btn-success btn-sm"><i class="glyphicon glyphicon-zoom-in" onclick="LoadData(<?php echo $alokasi_provinsi[$i]->id; ?>);"></i></a>
    <?php
        echo "</td>
              </tr>";
      }
    ?>
  </tbody>
  </table>
</div>


<!-- modal toggle table column -->
<div class="modal fade" id="toggle-table-column" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black; width: 850px; ">
        <div class="modal-content">
            <div class="modal-header">
                Show / Hide Column
            </div>
            <div class="modal-body" style="width: 850px; ">
              <div class="row">
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="1" id="chkShowTahunAnggaran" checked>
                      <label class="form-check-label" for="chkShowTahunAnggaran">Tahun Anggaran</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="2" id="chkNoKontrak" checked>
                      <label class="form-check-label" for="chkNoKontrak">No. Kontrak</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="3" id="chkPeriode" checked>
                      <label class="form-check-label" for="chkPeriode">Periode</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="4" id="chkJenisBarang" checked>
                      <label class="form-check-label" for="chkJenisBarang">Jenis Barang</label>
                  </div>
              </div>
              <div class="row">
                  

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="5" id="chkMerk" checked>
                      <label class="form-check-label" for="chkMerk">Merk</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="6" id="chkProvinsi" checked>
                      <label class="form-check-label" for="chkProvinsi">Provinsi</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="7" id="chkKabupaten" checked>
                      <label class="form-check-label" for="chkKabupaten">Kabupaten</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="8" id="chkAlokasi" checked>
                      <label class="form-check-label" for="chkAlokasi">Alokasi</label>
                  </div>

              </div>
              <div class="row">

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="9" id="chkNilai" checked>
                      <label class="form-check-label" for="chkNilai">Nilai (Rp)</label>
                  </div>


                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="10" id="chkHargaSatuan" checked>
                      <label class="form-check-label" for="chkHargaSatuan">Harga Satuan (Rp)</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="11" id="chkID" checked>
                      <label class="form-check-label" for="chkID">ID</label>
                  </div>
                  
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="12" id="chkRegCad" checked>
                      <label class="form-check-label" for="chkRegCad">REG/CAD</label>
                  </div>
                  
              </div>
              <div class="row">

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="13" id="chkNoAdendum" checked>
                      <label class="form-check-label" for="chkNoAdendum">No Adendum</label>
                  </div>
                  
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="14" id="chkPenyedia" checked>
                      <label class="form-check-label" for="chkPenyedia">Penyedia</label>
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

<!-- modal preview  -->
<div class="modal fade" id="preview-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black; width: 95%;">
        <div class="modal-content">

            <div class="modal-header">
                Preview Data Alokasi
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
                    <tr>
                      <td>Tahun Anggaran</td>
                      <td>:</td>
                      <td id="prev_tahun_anggaran"></td>
                      <!-- <td rowspan="17">
                      </td> -->
                    </tr>
                    <tr>
                      <td>No Kontrak</td>
                      <td>:</td>
                      <td id="prev_no_kontrak"></td>
                    </tr>
                    <tr>
                      <td>Periode</td>
                      <td>:</td>
                      <td id="prev_periode"></td>
                    </tr>
                    <tr>
                      <td>Jenis Barang</td>
                      <td>:</td>
                      <td id="prev_jenis_barang"></td>
                    </tr>
                    <tr>
                      <td>Merk</td>
                      <td>:</td>
                      <td id="prev_merk"></td>
                    </tr>
                    <tr>
                      <td>Provinsi</td>
                      <td>:</td>
                      <td id="prev_nama_provinsi"></td>
                    </tr>
                    <tr>
                      <td>Kabupaten</td>
                      <td>:</td>
                      <td id="prev_nama_kabupaten"></td>
                    </tr>
                    <tr>
                      <td>Alokasi</td>
                      <td>:</td>
                      <td id="prev_jumlah_barang"></td>
                    </tr>
                    <tr>
                      <td>Nilai Barang (Rp)</td>
                      <td>:</td>
                      <td id="prev_nilai_barang"></td>
                    </tr>
                    <tr>
                      <td>Harga Satuan (Rp)</td>
                      <td>:</td>
                      <td id="prev_harga_satuan"></td>
                    </tr>
                    <tr>
                      <td>ID</td>
                      <td>:</td>
                      <td id="prev_dinas"></td>
                    </tr>
                    <tr>
                      <td>REG/CAD</td>
                      <td>:</td>
                      <td id="prev_regcad"></td>
                    </tr>
                    <tr>
                      <td>No Adendum</td>
                      <td>:</td>
                      <td id="prev_no_adendum"></td>
                    </tr>
                    <tr>
                      <td>Penyedia</td>
                      <td>:</td>
                      <td id="prev_penyedia"></td>
                    </tr>
                    <!-- <tr>
                      <td>Gambar Adendum 1</td>
                      <td>:</td>
                      <td><img id="prev_image_adendum_1" src="#" width="250px" /></td>
                    </tr>
                    <tr>
                      <td>Gambar Adendum 2</td>
                      <td>:</td>
                      <td><img id="prev_image_adendum_2" src="#" width="250px" /></td>
                    </tr> -->
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

<script src="<?php echo base_url('assets/js/pdfobject.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/asPieProgress/jquery-asPieProgress.js'); ?>"></script>

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

  $("#ExportReporttoExcel").on("click", function() {
    // isExport = 1;
    // currlen = table.page.len();
    // table.page.len( -1 ).draw();
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("AlokasiProvinsi/doExport"); ?>',
      data: Object.assign(JSON.parse($("#dt_search_input").val()), JSON.parse($("#dt_columns").val())),
      success: function(data) {
        if(data.filename) window.open(data.filename,'_blank');
      }
    }); 
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
    "order": [[ 14, "desc" ]],
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
        "url": '<?php echo base_url("AlokasiProvinsi/AjaxGetAllData"); ?>',
        "data": function(d) {
          $("#dt_search_input").val(JSON.stringify(d));
          return d;
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
        { data: "tahun_anggaran" },
        { data: "no_kontrak" },
        { data: "periode" },
        { data: "nama_barang" },
        { data: "merk" },
        { data: "nama_provinsi" },
        { data: "nama_kabupaten" },
        { data: "alokasi" },
        { data: "nilai_barang" },
        { data: "harga_satuan" },
        { data: "dinas" },
        { data: "regcad" },
        { data: "no_adendum" },
        { data: "nama_penyedia" },
        { data: "tools" },
    ],
    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:eq(13)').attr('nowrap', 'nowrap');
    },
    "columnDefs": [ {
      "targets": 9,
      "orderable": false,
    } ],
    "initComplete": function(s) {
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
    }
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
  $('.search-input-text').on( 'keyup click', function () {   // for text boxes
      var i =$(this).attr('data-column');  // getting column index
      var v =$(this).val();  // getting search input value
      table.columns(i).search(v).draw();
  } );

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

  function LoadData(id_alokasi)
  {
    $.ajax({
      url: "<?php echo base_url('AlokasiProvinsi/GetData'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_alokasi : id_alokasi},
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
              content += "<div class='mySlides'><div class='numbertext'> "+(i+1)+" / "+obj.length+" </div><img id='zoom_"+i+"' src='../upload/kontrak_provinsi_detail/"+obj[i]+"' height='300' /><div class='text'>"+obj[i].substring(10)+"&nbsp;&nbsp;&nbsp;<a class='btn btn-xs btn-primary btn-sm' href='../upload/kontrak_provinsi_detail/"+obj[i]+"' download><i class='glyphicon glyphicon-save-file'></i></a></div></div>";
            }
            
          }
          content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";
          $("#slideshow_container").html(content);

          // console.log(content);

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              PDFObject.embed("../upload/kontrak_provinsi_detail/"+obj[i], "#embedPDF_"+i);
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

        $('#prev_tahun_anggaran').html(data['tahun_anggaran']);
        $('#prev_no_kontrak').html(data['no_kontrak']);
        $('#prev_periode').html(data['periode_mulai']+' s/d '+data['periode_selesai']);
        $('#prev_jenis_barang').html(data['jenis_barang']);
        $('#prev_merk').html(data['merk']);
        $('#prev_nama_provinsi').html(data['nama_provinsi']);
        $('#prev_nama_kabupaten').html(data['nama_kabupaten']);

        if(data['status_alokasi'] == 'DATA KONTRAK AWAL'){
          var jumlah_barang = data['jumlah_barang_detail'];
          var nilai_barang = data['nilai_barang_detail'];
          var harga_satuan = nilai_barang / jumlah_barang;
          $('#prev_no_adendum').html('');
        }
        else if(data['status_alokasi'] == 'DATA ADENDUM 1'){
          var jumlah_barang = data['jumlah_barang_rev_1'];
          var nilai_barang = data['nilai_barang_rev_1'];
          var harga_satuan = nilai_barang / jumlah_barang;
          $('#prev_no_adendum').html(data['no_adendum_1']);
        }
        else if(data['status_alokasi'] == 'DATA ADENDUM 2'){
          var jumlah_barang = data['jumlah_barang_rev_2'];
          var nilai_barang = data['nilai_barang_rev_2'];
          var harga_satuan = nilai_barang / jumlah_barang;
          $('#prev_no_adendum').html(data['no_adendum_2']);
        }

        $('#prev_jumlah_barang').html(jumlah_barang);
        $('#prev_nilai_barang').html(nilai_barang);
        $('#prev_harga_satuan').html(harga_satuan);

        $('#prev_regcad').html(data['regcad']);
        $('#prev_dinas').html(data['dinas']);
        $('#prev_penyedia').html(data['nama_penyedia_provinsi']);


        $('#prev_jumlah_barang').number( true, 0 );
        $('#prev_nilai_barang').number( true, 0 );
        $('#prev_harga_satuan').number( true, 0 );

        if(data['nama_file'] != '' && data['nama_file'] != "null" ){
          showSlides(1);
        }
        $('#preview-data').modal('show');
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