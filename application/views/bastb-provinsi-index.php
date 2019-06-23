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
  .search-input-text{
    border-radius: 5px;
    padding-left: 4px;
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
<div class="col-md-12 npl npr mb20 dt-buttons">
  <div class="col-sm-4" style="height: 120px; border: solid 1px; ">
    <?php if($bolehtambah) { ?>
    <div class="col-sm-4" style="padding-top: 10px; text-align: center; ">
      <a href="<?php echo base_url('BASTBProvinsi/Add'); ?>">
        <img src="<?php echo base_url('assets/ico/file_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Input Data BASTB</span>
    </div>
    <?php } ?>
    <div class="col-sm-4" style="padding-top: 10px; text-align: center; ">
      <a href="#" data-toggle="modal" data-target="#toggle-table-column">
        <img src="<?php echo base_url('assets/ico/grid_icon.png'); ?>" width="50px" />
      </a><br>
      <span style="font-size:10px; font-weight: bold;">Ubah Tampilan Tabel</span>
    </div>
    <div class="col-sm-4" style="padding-top: 10px; text-align: center; ">
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
      <div class="pie_progress" role="progressbar" data-goal="<?php echo($total_unit_alokasi == 0 ? 0 : ($total_unit/$total_unit_alokasi*100)); ?>" data-barcolor="#3daf2c" aria-valuemin="0" aria-valuemax="100">
        <span class="pie_progress__number count">0%</span>
      </div>
      % Unit
    </div>
    <div class="col-sm-6" style="width: 200px; height: 150px; text-align: center; border-right: solid; border-right-color: #efefef; ">
      <div class="pie_progress" role="progressbar" data-goal="<?php echo($total_unit_alokasi == 0 ? 0 : ($total_nilai/$total_nilai_alokasi*100)); ?>" data-barcolor="#3daf2c" aria-valuemin="0" aria-valuemax="100">
        <span class="pie_progress__number count">0%</span>
      </div>
      % Nilai (Rp)
    </div>
    <!-- <div class="col-sm-6" style="width: 200px; height: 100px; text-align: center; padding-top: 15px; ">
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
      <td><input type="text" data-column="9"  class="search-input-text"></td>
      <td><input type="text" data-column="10"  class="search-input-text"></td>
      <td><input type="text" data-column="11"  class="search-input-text"></td>
      <td><input type="text" data-column="12"  class="search-input-text"></td>
      <td><input type="text" data-column="13"  class="search-input-text"></td>
      <td><input type="text" data-column="14"  class="search-input-text"></td>
      <td><input type="text" data-column="15"  class="search-input-text"></td>
      <td><input type="text" data-column="16"  class="search-input-text"></td>
      <td><input type="text" data-column="17"  class="search-input-text"></td>
      <td><input type="text" data-column="18"  class="search-input-text"></td>
      <td><input type="text" data-column="19"  class="search-input-text"></td>
      <td><input type="text" data-column="20"  class="search-input-text"></td>
      <td><input type="text" data-column="21"  class="search-input-text"></td>
      <td><input type="text" data-column="22"  class="search-input-text"></td>
      <td><input type="text" data-column="23"  class="search-input-text"></td>
      <td><input type="text" data-column="24"  class="search-input-text"></td>
      <td><input type="text" data-column="25"  class="search-input-text"></td>
      <td><input type="text" data-column="26"  class="search-input-text"></td>
      <td>
        <select class="search-input-select" data-column="27">
          <option value=""></option>
          <option value="TERSEDIA">TERSEDIA</option>
          <option value="TDKTERSEDIA">TDK TERSEDIA</option>
        </select>
      </td>
      <!-- <td><input type="text" data-column="26"  class="search-input-text"></td> -->
    </tr>
    <tr>
      <th>Tahun Anggaran</th>
      <th>Kelompok Penerima</th>
      <th>No. BASTB</th>
      <th>Tanggal</th>
      <th>Pihak yang Menyerahkan</th>
      <th>Nama yang Menyerahkan</th>
      <th>Jabatan yang Menyerahkan</th>
      <th>Telp yang Menyerahkan</th>
      <th>Alamat yang Menyerahkan</th>
      <th>Provinsi yang Menyerahkan</th>
      <th>Kabupaten/Kota yang Menyerahkan</th>
      <th>Pihak yang Menerima</th>
      <th>Nama yang Menerima</th>
      <th>Jabatan yang Menerima</th>
      <th>Telp yang Menerima</th>
      <th>Alamat yang Menerima</th>
      <th>Provinsi yang Menerima</th>
      <th>Kabupaten/Kota yang Menerima</th>
      <th>Kecamatan yang Menerima</th>
      <th>Kelurahan/Desa yang Menerima</th>
      <th>No Kontrak</th>
      <th>Nama Barang</th>
      <th>Merk</th>
      <th>Jumlah</th>
      <th>Nilai (Rp)</th>
      <th>Nama Mengetahui</th>
      <th>Jabatan Mengetahui</th>
      <th>Ket. Foto/Dokumen</th>
      <th></th>
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
                Are you sure to delete No BASTB <b><i class="title"></i></b> ?
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
                Upload File Untuk No BASTB <b><i class="title"></i></b>
            </div>
            <div class="modal-body">
              <div class="col_full">
                <label>UPLOAD FOTO/DOKUMEN KONTRAK</label>
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

<!-- modal toggle table column -->
<div class="modal fade" id="toggle-table-column" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black; width: 95%; ">
        <div class="modal-content">
            <div class="modal-header">
                Show / Hide Column
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col-md-3">
                      <input class="form-check-input" name="showControl" type="checkbox" value="std" id="chkStandar" checked>
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
              <div class="row custom">
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="1" id="chkShowTahunAnggaran" checked>
                      <label class="form-check-label" for="chkShowTahunAnggaran">Tahun Anggaran</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="2" id="chkShowTitikSerah" checked>
                      <label class="form-check-label" for="chkShowTitikSerah">Kelompok Penerima</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="3" id="chkNoBASTB" checked>
                      <label class="form-check-label" for="chkNoBASTB">No. BASTB</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="4" id="chkTanggal" checked>
                      <label class="form-check-label" for="chkTanggal">Tanggal</label>
                  </div>

              </div>
              <div class="row custom">

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="5" id="chkPihakPenyerah" checked>
                      <label class="form-check-label" for="chkPihakPenyerah">Pihak yang Menyerahkan</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="6" id="chkNamaPenyerah" checked>
                      <label class="form-check-label" for="chkNamaPenyerah">Nama yang Menyerahkan</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="7" id="chkJabatanPenyerah" checked>
                      <label class="form-check-label" for="chkJabatanPenyerah">Jabatan yang Menyerahkan</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="8" id="chkTelpPenyerah" checked>
                      <label class="form-check-label" for="chkTelpPenyerah">Telp yang Menyerahkan</label>
                  </div>
              </div>
              <div class="row custom">

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="9" id="chkAlamatPenyerah" checked>
                      <label class="form-check-label" for="chkAlamatPenyerah">Alamat yang Menyerahkan</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="10" id="chkProvPenyerah" checked>
                      <label class="form-check-label" for="chkProvPenyerah">Provinsi yang Menyerahkan</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="11" id="chkKabPenyerah" checked>
                      <label class="form-check-label" for="chkKabPenyerah">Kabupaten yang Menyerahkan</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="12" id="chkPihakPenerima" checked>
                      <label class="form-check-label" for="chkPihakPenerima">Pihak yang Menerima</label>
                  </div>                  
              </div>
              <div class="row custom">

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="13" id="chkNamaPenerima" checked>
                      <label class="form-check-label" for="chkNamaPenerima">Nama yang Menerima</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="14" id="chkJabatanPenerima" checked>
                      <label class="form-check-label" for="chkJabatanPenerima">Jabatan yang Menerima</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="15" id="chkTelpPenerima" checked>
                      <label class="form-check-label" for="chkTelpPenerima">Telp yang Menerima</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="16" id="chkAlamatPenerima" checked>
                      <label class="form-check-label" for="chkAlamatPenerima">Alamat yang Menerima</label>
                  </div>
              </div>
              <div class="row custom">


                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="17" id="chkProvPenerima" checked>
                      <label class="form-check-label" for="chkProvPenerima">Provinsi yang Menerima</label>
                  </div>
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="18" id="chkKabPenerima" checked>
                      <label class="form-check-label" for="chkKabPenerima">Kabupaten/Kota yang Menerima</label>
                  </div>
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="19" id="chkKecPenerima" checked>
                      <label class="form-check-label" for="chkKecPenerima">Kecamatan yang Menerima</label>
                  </div>
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="20" id="chkKelPenerima" checked>
                      <label class="form-check-label" for="chkKelPenerima">Kelurahan/Desa yang Menerima</label>
                  </div>

              </div>
              <div class="row custom">
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="21" id="chkNoKontrak" checked>
                      <label class="form-check-label" for="chkNoKontrak">No Kontrak</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="22" id="chkNamaBarang" checked>
                      <label class="form-check-label" for="chkNamaBarang">Nama Barang</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="23" id="chkMerk" checked>
                      <label class="form-check-label" for="chkMerk">Merk</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="24" id="chkJumlah" checked>
                      <label class="form-check-label" for="chkJumlah">Jumlah</label>
                  </div>

              </div>

              <div class="row custom">
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="25" id="chkNilai" checked>
                      <label class="form-check-label" for="chkNilai">Nilai (Rp)</label>
                  </div>
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="26" id="chkNamaMengetahui" checked>
                      <label class="form-check-label" for="chkNamaMengetahui">Nama Mengetahui</label>
                  </div>                
                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="27" id="chkJabatanMengetahui" checked>
                      <label class="form-check-label" for="chkJabatanMengetahui">Jabatan Mengetahui</label>
                  </div>

                  <div class="col-md-3">
                      <input class="form-check-input" name="show" type="checkbox" value="28" id="chkKetFoto" checked>
                      <label class="form-check-label" for="chkKetFoto">Ket. Foto/Dokumen</label>
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
    <div class="modal-dialog" style="color:black; width: 95%; ">
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
                  <table class="table table-striped" style="display: block; height: 500px; overflow-y: scroll;">
                    <tr>
                      <!-- <td rowspan="9">
                      </td> -->
                      <td>Tahun Anggaran</td>
                      <td>:</td>
                      <td id="prev_tahun_anggaran"></td>
                      <!-- <td rowspan="9">
                      </td> -->
                    </tr>
                    <tr>
                      <td>Kelompok Penerima</td>
                      <td>:</td>
                      <td id="prev_kelompok_penerima"></td>
                    </tr>
                    <tr>
                      <td>No BASTB</td>
                      <td>:</td>
                      <td id="prev_no_bastb"></td>
                    </tr>
                    <tr>
                      <td>Tanggal</td>
                      <td>:</td>
                      <td id="prev_tanggal"></td>
                    </tr>
                    <tr>
                      <td>Pihak yang Menyerahkan</td>
                      <td>:</td>
                      <td id="prev_pihak_penyerah"></td>
                    </tr>
                    <tr>
                      <td>Nama yang Menyerahkan</td>
                      <td>:</td>
                      <td id="prev_nama_penyerah"></td>
                    </tr>
                    <tr>
                      <td>Jabatan yang Menyerahkan</td>
                      <td>:</td>
                      <td id="prev_jabatan_penyerah"></td>
                    </tr>
                    <tr>
                      <td>Telp yang Menyerahkan</td>
                      <td>:</td>
                      <td id="prev_telp_penyerah"></td>
                    </tr>
                    <tr>
                      <td>Alamat yang Menyerahkan</td>
                      <td>:</td>
                      <td id="prev_alamat_penyerah"></td>
                    </tr>
                    <tr>
                      <td>Provinsi yang Menyerahkan</td>
                      <td>:</td>
                      <td id="prev_provinsi_penyerah"></td>
                    </tr>
                    <tr>
                      <td>Kabupaten/Kota yang Menyerahkan</td>
                      <td>:</td>
                      <td id="prev_kabupaten_penyerah"></td>
                    </tr>
                    <tr>
                      <td>Pihak yang Menerima</td>
                      <td>:</td>
                      <td id="prev_pihak_penerima"></td>
                    </tr>
                    <tr>
                      <td>Nama yang Menerima</td>
                      <td>:</td>
                      <td id="prev_nama_penerima"></td>
                    </tr>
                    <tr>
                      <td>Jabatan yang Menerima</td>
                      <td>:</td>
                      <td id="prev_jabatan_penerima"></td>
                    </tr>
                    <tr>
                      <td>Telp yang Menerima</td>
                      <td>:</td>
                      <td id="prev_telp_penerima"></td>
                    </tr>
                    <tr>
                      <td>Alamat yang Menerima</td>
                      <td>:</td>
                      <td id="prev_alamat_penerima"></td>
                    </tr>
                    <tr>
                      <td>Provinsi yang Menerima</td>
                      <td>:</td>
                      <td id="prev_provinsi_penerima"></td>
                    </tr>
                    <tr>
                      <td>Kabupaten/Kota yang Menerima</td>
                      <td>:</td>
                      <td id="prev_kabupaten_penerima"></td>
                    </tr>
                    <tr>
                      <td>Kecamatan yang Menerima</td>
                      <td>:</td>
                      <td id="prev_kecamatan_penerima"></td>
                    </tr>
                    <tr>
                      <td>Kelurahan/Desa yang Menerima</td>
                      <td>:</td>
                      <td id="prev_kelurahan_penerima"></td>
                    </tr>
                    <tr>
                      <td>No Kontrak</td>
                      <td>:</td>
                      <td id="prev_no_kontrak"></td>
                    </tr>
                    <tr>
                      <td>Nama Barang</td>
                      <td>:</td>
                      <td id="prev_nama_barang"></td>
                    </tr>
                    <tr>
                      <td>Merk</td>
                      <td>:</td>
                      <td id="prev_merk"></td>
                    </tr>
                    <tr>
                      <td>Jumlah Barang</td>
                      <td>:</td>
                      <td id="prev_jumlah_barang"></td>
                    </tr>
                    <tr>
                      <td>Nilai Barang (Rp)</td>
                      <td>:</td>
                      <td id="prev_nilai_barang"></td>
                    </tr>
                    <tr>
                      <td>Nama yang Mengetahui</td>
                      <td>:</td>
                      <td id="prev_nama_mengetahui"></td>
                    </tr>
                    <tr>
                      <td>Jabatan yang Mengetahui</td>
                      <td>:</td>
                      <td id="prev_jabatan_mengetahui"></td>
                    </tr>
                  </table>
                </div>
                <!-- div zoom image -->
                <!-- <div id="zoom-container" style="z-index:9999 !important;"></div> -->
              </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal preview -->

<script src="<?php echo base_url('assets/js/dropzone.js'); ?>"></script>
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

  //script for dropzone
  Dropzone.options.myDropzone= {
    // The configuration we've talked about above
    url: '<?php echo base_url("BASTBProvinsi/Test"); ?>',
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
          formData.append("id_bastb", jQuery("#id_row").val());
      });
    },
    success: function (file, response) {
          
          window.location="<?php echo base_url('BASTBProvinsi'); ?>";
    },
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

    $("#show-column-save-btn").click();

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
      url: '<?php echo site_url("BASTBProvinsi/doExport"); ?>',
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
        "url": '<?php echo base_url("BASTBProvinsi/AjaxGetAllData"); ?>',
        "data": function(d) {
          $("#dt_search_input").val(JSON.stringify(d));
          return d;
        },
        "dataType": "json"
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
        { data: "kelompok_penerima" },
        { data: "no_bastb" },
        { data: "tanggal" },
        { data: "pihak_penyerah" },
        { data: "nama_penyerah" },
        { data: "jabatan_penyerah" },
        { data: "notelp_penyerah" },
        { data: "alamat_penyerah" },
        { data: "provinsi_penyerah" },
        { data: "kabupaten_penyerah" },
        { data: "pihak_penerima" },
        { data: "nama_penerima" },
        { data: "jabatan_penerima" },
        { data: "notelp_penerima" },
        { data: "alamat_penerima" },
        { data: "provinsi_penerima" },
        { data: "kabupaten_penerima" },
        { data: "kecamatan_penerima" },
        { data: "kelurahan_penerima" },
        { data: "no_kontrak" },
        { data: "nama_barang" },
        { data: "merk" },
        { data: "jumlah_barang" },
        { data: "nilai_barang" },
        { data: "nama_mengetahui" },
        { data: "jabatan_mengetahui" },
        { data: "ketfoto" },
        { data: "tools" },
    ],

    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:last-child').attr('nowrap', 'nowrap');
    },
    "order": [[ 28, "desc" ]],
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

  $('.search-input-select').on( 'change', function () {   // for text boxes
      var i =$(this).attr('data-column');  // getting column index
      var v =$(this).val();  // getting search input value
      table.columns(i).search(v).draw();
  } );

  $("#show-column-save-btn").click(function () {
      var checked = [];
      if($("#chkKustom").prop('checked') == true){
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
      }

      if($("#chkLengkap").prop('checked') == true){

        $("#toggle-table-column").modal('hide');

        $("input[name='show']").each(function (key, val) {
            var column = table.column(val.value-1);
            column.visible(true);
            $("input[name='show']").prop('checked', true);
        });
      }

      if($("#chkStandar").prop('checked') == true){

        $("input[name='show']").prop('checked', true);

        $("#chkShowTitikSerah").prop('checked', false);
        $("#chkPihakPenyerah").prop('checked', false);
        $("#chkNamaPenyerah").prop('checked', false);
        $("#chkJabatanPenyerah").prop('checked', false);
        $("#chkTelpPenyerah").prop('checked', false);
        $("#chkAlamatPenyerah").prop('checked', false);
        $("#chkProvPenyerah").prop('checked', false);

        $("#chkNamaPenerima").prop('checked', false);
        $("#chkJabatanPenerima").prop('checked', false);
        $("#chkTelpPenerima").prop('checked', false);
        $("#chkAlamatPenerima").prop('checked', false);
        $("#chkProvPenerima").prop('checked', false);
        $("#chkKecPenerima").prop('checked', false);
        $("#chkKelPenerima").prop('checked', false);

        $("#chkNoKontrak").prop('checked', false);
        $("#chkNamaMengetahui").prop('checked', false);
        $("#chkJabatanMengetahui").prop('checked', false);

        $("#chkKetFoto").prop('checked', false);

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

  $('#toggle-table-column').on('show.bs.modal', function(e) {
    if($("#chkKustom").prop('checked') == true){
      $(".custom").show();
    }
    else{
      $(".custom").hide();
    }
  });

  $("#chkStandar").click(function (){
      $(".custom").hide();
      $("#chkLengkap").prop('checked', false);
      $("#chkKustom").prop('checked', false);
  });

  $("#chkLengkap").click(function (){
      $(".custom").hide();
      $("#chkStandar").prop('checked', false);
      $("#chkKustom").prop('checked', false);
  });

  $("#chkKustom").click(function (){
    if($("#chkKustom").prop('checked') == true){
      $(".custom").show();
      $("#chkLengkap").prop('checked', false);
      $("#chkStandar").prop('checked', false);
    }
    else{
      $(".custom").hide();
      $("#chkStandar").prop('checked', true);
    } 
  });

  $('#upload-modal').on('show.bs.modal', function(e) {
      var data = $(e.relatedTarget).data();
      $('.title', this).text(data.recordTitle);
      $('#id_row', this).val(data.recordId);
      showUploadedFiles(data.recordId);
  });

  function showUploadedFiles(id_bastb)
  {
    $.ajax({
      url: "<?php echo base_url('BASTBProvinsi/GetBASTB'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_bastb : id_bastb},
      success: function(data) {

        if(data['nama_file'] != '' && data['nama_file'] != "null" ){

          var obj = JSON.parse(data['nama_file']);

          var content = "";

          for(var i=0;i<obj.length;i++)
          {
            content += "<tr>";
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'PDF' || file_format == 'pdf'){
              
              content += "<td>"+(i+1)+"</td><td><img src='../upload/pdflogo.png'/></td><td>"+obj[i].substring(10)+"</td>";
              
            }
            else{
              content += "<td>"+(i+1)+"</td><td><img src='../upload/bastb_provinsi/"+obj[i]+"'/></td><td>"+obj[i].substring(10)+"</td>";
            }
            content += "<td><a class='btn btn-s btn-danger' onclick='removeImage("+id_bastb+", "+i+")'><i class='glyphicon glyphicon-trash'></i></a></td>";
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

  function removeImage(id_bastb, urutan)
  {

    var txt = '';
    var r = confirm("Apakah anda yakin untuk menghapus gambar ini ?");
    if (r == true) {
      $.ajax({
        url: "<?php echo base_url('BASTBProvinsi/RemoveImage'); ?>",
        dataType: 'JSON',
        type: 'GET',
        data: {id_bastb : id_bastb, urutanfile: urutan},
        success: function(data) {

          window.location="<?php echo base_url('BASTBProvinsi'); ?>";

        },
        error: function(jqXHR, textStatus, errorThrown) {
            window.location="<?php echo base_url('BASTBProvinsi'); ?>";
        }
      });
    }
  }

  $('#confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

      $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
  });

  $('#confirm-delete').on('show.bs.modal', function(e) {
      var data = $(e.relatedTarget).data();
      $('.title', this).text(data.recordTitle);
  });

  function LoadData(id_bastb)
  {
    $.ajax({
      url: "<?php echo base_url('BASTBProvinsi/GetBASTB'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_bastb : id_bastb},
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
              content += "<div class='mySlides'><div class='numbertext'> "+(i+1)+" / "+obj.length+" </div><img id='zoom_"+i+"' src='../upload/bastb_provinsi/"+obj[i]+"' height='300' /><div class='text'>"+obj[i].substring(10)+"&nbsp;&nbsp;&nbsp;<a class='btn btn-xs btn-primary btn-sm' href='../upload/bastb_provinsi/"+obj[i]+"' download><i class='glyphicon glyphicon-save-file'></i></a></div></div>";
            }
            
          }
          content += "<a class='prevpointer' onclick='plusSlides(-1)'>&#10094;</a><a class='nextpointer' onclick='plusSlides(1)'>&#10095;</a>";
          $("#slideshow_container").html(content);

          // console.log(content);

          for(var i=0;i<obj.length;i++)
          {
            var file_format = obj[i].substr(obj[i].length - 3); 
            if(file_format == 'pdf' || file_format == 'PDF'){
              PDFObject.embed("../upload/bastb_provinsi/"+obj[i], "#embedPDF_"+i);
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
        else{
         
        }


        $('#prev_tahun_anggaran').html(data['tahun_anggaran']);
        $('#prev_kelompok_penerima').html(data['kelompok_penerima']);
        $('#prev_no_bastb').html(data['no_bastb']);
        $('#prev_tanggal').html(data['tanggal']);

        $('#prev_pihak_penyerah').html(data['pihak_penyerah']);
        $('#prev_nama_penyerah').html(data['nama_penyerah']);
        $('#prev_jabatan_penyerah').html(data['jabatan_penyerah']);
        $('#prev_telp_penyerah').html(data['notelp_penyerah']);
        $('#prev_alamat_penyerah').html(data['alamat_penyerah']);
        $('#prev_provinsi_penyerah').html(data['provinsi_penyerah']);
        $('#prev_kabupaten_penyerah').html(data['kabupaten_penyerah']);

        $('#prev_pihak_penerima').html(data['pihak_penerima']);
        $('#prev_nama_penerima').html(data['nama_penerima']);
        $('#prev_jabatan_penerima').html(data['jabatan_penerima']);
        $('#prev_telp_penerima').html(data['notelp_penerima']);
        $('#prev_alamat_penerima').html(data['alamat_penerima']);
        $('#prev_provinsi_penerima').html(data['provinsi_penerima']);
        $('#prev_kabupaten_penerima').html(data['kabupaten_penerima']);
        $('#prev_kecamatan_penerima').html(data['kecamatan_penerima']);
        $('#prev_kelurahan_penerima').html(data['kelurahan_penerima']);

        $('#prev_no_kontrak').html(data['no_kontrak']);
        $('#prev_nama_barang').html(data['nama_barang']);
        $('#prev_merk').html(data['merk']);
        $('#prev_jumlah_barang').html(data['jumlah_barang']);
        $('#prev_nilai_barang').html(data['nilai_barang']);

        $('#prev_nama_mengetahui').html(data['nama_mengetahui']);
        $('#prev_jabatan_mengetahui').html(data['jabatan_mengetahui']);

        var jumlah_barang = data['jumlah_barang'];
        var nilai_barang = data['nilai_barang'];
        // var harga_satuan = nilai_barang / jumlah_barang;

        // $('#prev_harga_satuan').html(harga_satuan);
        $('#prev_jumlah_barang').number( true, 0 );
        $('#prev_nilai_barang').number( true, 0 );
        // $('#prev_harga_satuan').number( true, 0 );

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