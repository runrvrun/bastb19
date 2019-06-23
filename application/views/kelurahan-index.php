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
  <a class="button button-circle" href="<?php echo base_url('Kelurahan/Add'); ?>">
    TAMBAH DATA
  </a>
  <?php } ?>
  <button type="button" class="button button-circle" id="ExportReporttoExcel">
    EXPORT EXCEL
  </button>
</div>
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
  </tr>
  <tr>
    <th>Kode</th>
    <th>Provinsi</th>
    <th>Kabupaten/Kota</th>
    <th>Kecamatan</th>
    <th>Kelurahan/Desa</th>
    <th style="width: 100px"></th>
  </tr>
</thead>
<tbody>
  <?php 
    for($i=0; $i<count($kelurahan); $i++){
      echo "<tr>
              <td>".$kelurahan[$i]->kode_kelurahan."</td>
              <td>".$kelurahan[$i]->nama_provinsi."</td>
              <td>".$kelurahan[$i]->nama_kabupaten."</td>
              <td>".$kelurahan[$i]->nama_kecamatan."</td>
              <td>".$kelurahan[$i]->nama_kelurahan."</td>
              <td>";
  ?>
  <?php if($bolehedit) { ?>
            <a class="btn btn-xs btn-primary btn-sm" href="<?php echo base_url('Kelurahan/Edit?id=').$kelurahan[$i]->id; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
  <?php } 
            if($bolehhapus) {
          ?>
            <a class="btn btn-xs btn-danger btn-sm" data-href="<?php echo base_url('Kelurahan/doDelete?id=').$kelurahan[$i]->id; ?>" data-toggle="modal" data-record-title="<?php echo $kelurahan[$i]->nama_kelurahan; ?>" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>
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
                Are you sure to delete kelurahan/desa <b><i class="title"></i></b> ?
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

  $(document).ready(function() {
    $('.buttons-excel').hide();
  });
  
  var table = $('#Table1').DataTable({
    "processing" : true,
    "serverSide" : true,
    "searching": true,
    "scrollY" : 450,
    "fixedHeader" : true,
    // "scrollX" : true,
    "lengthMenu": [[10, 25, 50, 75, 100, 150, -1], [10, 25, 50, 75, 100, 150, 'All']],
    // "pageLength": 25,
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
        "url": '<?php echo base_url("Kelurahan/AjaxGetAllData"); ?>',
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
        { data: "kode_kelurahan" },
        { data: "nama_provinsi" },
        { data: "nama_kabupaten" },
        { data: "nama_kecamatan" },
        { data: "nama_kelurahan" },
        { data: "tools" },
    ],
    
    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:last-child').attr('nowrap', 'nowrap');
    },
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

  $('.search-input-text').on( 'keyup click', function () {   // for text boxes
      var i =$(this).attr('data-column');  // getting column index
      var v =$(this).val();  // getting search input value
      table.columns(i).search(v).draw();
  } );

  var currlen = 10;

  $("#ExportReporttoExcel").on("click", function() {
    // isExport = 1;
    // currlen = table.page.len();
    // table.page.len( -1 ).draw();
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("Kelurahan/doExport"); ?>',
      data: Object.assign(JSON.parse($("#dt_search_input").val()), JSON.parse($("#dt_columns").val())),
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

</script>