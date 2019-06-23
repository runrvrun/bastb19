<style>
  .button{
    background-color: gray;
  }
</style>
<div style="width: 120%; margin-left: -50px; margin-top: -50px;">
  <button type="button" class="button button-circle" id="btnNaskahHibah" onclick="setActive(0);">
    NASKAH HIBAH
  </button>
  <button type="button" class="button button-circle" id="btnBASTBMN" onclick="setActive(1);">
    BAST BMN
  </button>
  <button type="button" class="button button-circle" id="btnSuratPernyataan" onclick="setActive(2);">
    SURAT PERNYATAAN
  </button>
  <button type="button" class="button button-circle" id="btnLampNaskahHibah" onclick="setActive(3);">
    LAMP. NASKAH HIBAH
  </button>
  <button type="button" class="button button-circle" id="btnLampBASTBMN" onclick="setActive(4);">
    LAMP. BAST BMN
  </button>
  <button type="button" class="button button-circle" id="btnLampSuratPernyataan" onclick="setActive(5);">
    LAMP. SURAT PERNYATAAN
  </button>
</div>

<div style="margin-top: 50px; height: 800px;">
  <div id='embedPDF'></div>
</div>

<div style="margin-top: 20px;">
  <a href="<?php echo base_url('HibahProvinsi'); ?>">
    <button type="button" class="button button-circle">
      KEMBALI KE HIBAH
    </button>
  </a>
</div>

<script src="<?php echo base_url('assets/js/pdfobject.min.js'); ?>"></script>
<script>
  
  var id_hibah = "<?php echo $hibah_provinsi->id; ?>";

  var no_naskah_hibah = "<?php echo $no_naskah_hibah; ?>";
  var no_bast_bmn = "<?php echo $no_bast_bmn; ?>";
  var no_surat_pernyataan = "<?php echo $no_surat_pernyataan; ?>";

  var lamp_naskah_hibah = "<?php echo 'lamp_'.$no_naskah_hibah; ?>";
  var lamp_bast_bmn = "<?php echo 'lamp_'.$no_bast_bmn; ?>";
  var lamp_surat_pernyataan = "<?php echo 'lamp_'.$no_surat_pernyataan; ?>";

  $(document).ready(function() {
    $("#btnNaskahHibah").css('background-color', 'green');
    setActive(0);
  });

  function setActive(id){
    if(id == 0){
      $("#btnNaskahHibah").css('background-color', 'green');
      $("#btnBASTBMN").css('background-color', 'gray');
      $("#btnSuratPernyataan").css('background-color', 'gray');
      $("#btnLampNaskahHibah").css('background-color', 'gray');
      $("#btnLampBASTBMN").css('background-color', 'gray');
      $("#btnLampSuratPernyataan").css('background-color', 'gray');

      PDFObject.embed("../../pdf_hibah/hibah_provinsi/naskah_hibah/"+no_naskah_hibah+"_"+id_hibah+".pdf", "#embedPDF");
      $('#embedPDF').height(800);
    }
    if(id == 1){
      $("#btnNaskahHibah").css('background-color', 'gray');
      $("#btnBASTBMN").css('background-color', 'green');
      $("#btnSuratPernyataan").css('background-color', 'gray');
      $("#btnLampNaskahHibah").css('background-color', 'gray');
      $("#btnLampBASTBMN").css('background-color', 'gray');
      $("#btnLampSuratPernyataan").css('background-color', 'gray');

      PDFObject.embed("../../pdf_hibah/hibah_provinsi/bast_bmn/"+no_bast_bmn+"_"+id_hibah+".pdf", "#embedPDF");
      $('#embedPDF').height(800);
    }
    if(id == 2){
      $("#btnNaskahHibah").css('background-color', 'gray');
      $("#btnBASTBMN").css('background-color', 'gray');
      $("#btnSuratPernyataan").css('background-color', 'green');
      $("#btnLampNaskahHibah").css('background-color', 'gray');
      $("#btnLampBASTBMN").css('background-color', 'gray');
      $("#btnLampSuratPernyataan").css('background-color', 'gray');

      PDFObject.embed("../../pdf_hibah/hibah_provinsi/surat_pernyataan/"+no_surat_pernyataan+"_"+id_hibah+".pdf", "#embedPDF");
      $('#embedPDF').height(800);
    }
    if(id == 3){
      $("#btnNaskahHibah").css('background-color', 'gray');
      $("#btnBASTBMN").css('background-color', 'gray');
      $("#btnSuratPernyataan").css('background-color', 'gray');
      $("#btnLampNaskahHibah").css('background-color', 'green');
      $("#btnLampBASTBMN").css('background-color', 'gray');
      $("#btnLampSuratPernyataan").css('background-color', 'gray');

      PDFObject.embed("../../pdf_hibah/hibah_provinsi/lamp_naskah_hibah/"+lamp_naskah_hibah+"_"+id_hibah+".pdf", "#embedPDF");
      $('#embedPDF').height(800);
    }
    if(id == 4){
      $("#btnNaskahHibah").css('background-color', 'gray');
      $("#btnBASTBMN").css('background-color', 'gray');
      $("#btnSuratPernyataan").css('background-color', 'gray');
      $("#btnLampNaskahHibah").css('background-color', 'gray');
      $("#btnLampBASTBMN").css('background-color', 'green');
      $("#btnLampSuratPernyataan").css('background-color', 'gray');

      PDFObject.embed("../../pdf_hibah/hibah_provinsi/lamp_bast_bmn/"+lamp_bast_bmn+"_"+id_hibah+".pdf", "#embedPDF");
      $('#embedPDF').height(800);
    }
    if(id == 5){
      $("#btnNaskahHibah").css('background-color', 'gray');
      $("#btnBASTBMN").css('background-color', 'gray');
      $("#btnSuratPernyataan").css('background-color', 'gray');
      $("#btnLampNaskahHibah").css('background-color', 'gray');
      $("#btnLampBASTBMN").css('background-color', 'gray');
      $("#btnLampSuratPernyataan").css('background-color', 'green');

      PDFObject.embed("../../pdf_hibah/hibah_provinsi/lamp_surat_pernyataan/"+lamp_surat_pernyataan+"_"+id_hibah+".pdf", "#embedPDF");
      $('#embedPDF').height(800);
    }
  }
</script>