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
<div class="col-md-12 npl npr mb20 dt-buttons">
  <?php if($bolehtambah) { ?>
  <a class="button button-circle" href="<?php echo base_url('KodimKorem/Add'); ?>">
    TAMBAH DATA
  </a>
  <?php } ?>
  <button type="button" class="button button-circle" id="ExportReporttoExcel">
    EXPORT EXCEL
  </button>
</div>

<table id="Table1" class="table table-bordered table-striped">
<thead>
  <tr>
    <th>Brigade</th>
    <th>Nama Kodim/Korem</th>
    <th style="width: 100px;"></th>
  </tr>
</thead>
<tbody>
  <?php 
    for($i=0; $i<count($kodim_korem); $i++){
      echo "<tr>
              <td>".$kodim_korem[$i]->brigade."</td>
              <td>".$kodim_korem[$i]->nama_kodim_korem."</td>
              <td>";
  ?>
  <?php if($bolehedit) { ?>
            <a class="btn btn-xs btn-primary btn-sm" href="<?php echo base_url('KodimKorem/Edit?id=').$kodim_korem[$i]->id; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
  <?php } 
            if($bolehhapus) {
          ?>
            <a class="btn btn-xs btn-danger btn-sm" data-href="<?php echo base_url('KodimKorem/doDelete?id=').$kodim_korem[$i]->id; ?>" data-toggle="modal" data-record-title="<?php echo $kodim_korem[$i]->nama_kodim_korem; ?>" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>
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
                Are you sure to delete kodim/korem <b><i class="title"></i></b> ?
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
    // "pageLength": 10,
    // "lengthChange": true,
    "processing" : true,
    "serverSide" : true,
    "searching": true,
    "scrollY" : 450,
    "fixedHeader" : true,
    // "scrollX" : true,
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
        "url": '<?php echo base_url("KodimKorem/AjaxGetAllData"); ?>',
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
        { data: "brigade" },
        { data: "nama_kodim_korem" },
        { data: "tools" },
    ],
  });

  var currlen = 10;

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