<?php 
  $bolehtambah = 0;
  $bolehedit = 0;
  $bolehhapus = 0;
  foreach($this->session->userdata('logged_in')->crud_items as $crud){
    if(rtrim($crud->crud_action) == 'TAMBAH DATA')
      $bolehtambah = 1;
    if(rtrim($crud->crud_action) == 'EDIT DATA')
      $bolehedit = 1;
    if(rtrim($crud->crud_action) == 'HAPUS DATA')
      $bolehhapus = 1;
  }
?>
<style>
  .search-input-text{
    border-radius: 5px;
    padding-left: 4px;
  }
</style>
<div class="col-md-12 npl npr mb20 dt-buttons">
  <?php if($bolehtambah) { ?>
  <a class="button button-circle" href="<?php echo base_url('JenisBarangPusat/Add'); ?>">
    TAMBAH DATA
  </a>
  <?php } ?>
  <button type="button" class="button button-circle" id="ExportReporttoExcel">
    EXPORT EXCEL
  </button>
</div>
<table class="table table-striped table-bordered" id="Table1">
<thead>
  <tr>
    <td><input type="text" data-column="0"  class="search-input-text"></td>
    <td><input type="text" data-column="1"  class="search-input-text"></td>
    <td><input type="text" data-column="2"  class="search-input-text"></td>
    <td><input type="text" data-column="3"  class="search-input-text"></td>
    <td><input type="text" data-column="4"  class="search-input-text"></td>
    <td><input type="text" data-column="5"  class="search-input-text"></td>

  </tr>
  <tr>
    <th>Jenis Barang</th>
    <th>Nama Barang</th>
    <th>Merk</th>
    <th>Nama Penyedia</th>
    <th>Kode Barang</th>
    <th>Akun</th>
    <th style="width: 100px;"></th>
  </tr>
</thead>
<tbody>
  <?php 
    for($i=0; $i<count($jenis_barang_pusat); $i++){
      echo "<tr>
              <td>".$jenis_barang_pusat[$i]->jenis_barang."</td>
              <td>".$jenis_barang_pusat[$i]->nama_barang."</td>
              <td>".$jenis_barang_pusat[$i]->merk."</td>
              <td>".$jenis_barang_pusat[$i]->nama_penyedia_pusat."</td>
              <td>".$jenis_barang_pusat[$i]->kode_barang."</td>
              <td>".$jenis_barang_pusat[$i]->akun."</td>
              <td>";
  ?>
  <?php if($bolehedit) { ?>
            <a class="btn btn-xs btn-primary btn-sm" href="<?php echo base_url('JenisBarangPusat/Edit?id=').$jenis_barang_pusat[$i]->id; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
  <?php } 
            if($bolehhapus) {
          ?>
            <a class="btn btn-xs btn-danger btn-sm" data-href="<?php echo base_url('JenisBarangPusat/doDelete?id=').$jenis_barang_pusat[$i]->id; ?>" data-toggle="modal" data-record-title="<?php echo $jenis_barang_pusat[$i]->nama_barang; ?>" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>
  <?php } ?>
  <?php
      echo "</td>
            </tr>";
    }
  ?>
</tbody>
</table>

<!-- modal delete confirmation -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="color:black;">
        <div class="modal-content">
            <div class="modal-header">
                Delete Confirmation
            </div>
            <div class="modal-body">
                Are you sure to delete jenis barang <b><i class="title"></i></b> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<!-- end modal confirmation -->

<script>
  var isExport = 0;
  var currlen = 10;

  $(document).ready(function() {
    $('.buttons-excel').hide();
  });

  var table = $('#Table1').DataTable({
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
        "url": '<?php echo base_url("JenisBarangPusat/AjaxGetAllData"); ?>',
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
        { data: "jenis_barang" },
        { data: "nama_barang" },
        { data: "merk" },
        { data: "nama_penyedia_pusat" },
        { data: "kode_barang" },
        { data: "akun" },
        { data: "tools" },
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

</script>