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
  <a class="button button-circle" href="<?php echo base_url('TahunPengadaan/Add'); ?>">
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
    <th>Tahun Pengadaan</th>
    <th style="width: 100px;"></th>
  </tr>
</thead>
<tbody>
  <?php 
    for($i=0; $i<count($tahun_pengadaan); $i++){
      echo "<tr>
              <td>".$tahun_pengadaan[$i]->tahun_pengadaan."</td>
              <td>";
  ?>
  <?php if($bolehhapus) { ?>
            <a class="btn btn-xs btn-danger btn-sm" data-href="<?php echo base_url('TahunPengadaan/doDelete?id=').$tahun_pengadaan[$i]->id; ?>" data-toggle="modal" data-record-title="<?php echo $tahun_pengadaan[$i]->tahun_pengadaan; ?>" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>
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
                Are you sure to delete tahun pengadaan <b><i class="title"></i></b> ?
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
  $(document).ready(function() {
    $('.buttons-excel').hide();
  });

  var table = $('#Table1').DataTable({
    "pageLength": 10,
    "lengthChange": true,
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
  });

  $("#ExportReporttoExcel").on("click", function() {
    table.button( '.buttons-excel' ).trigger();
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