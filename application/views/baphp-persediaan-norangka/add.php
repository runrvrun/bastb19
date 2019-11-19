<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<style>
.search-input-text {
    border-radius: 5px;
    padding-left: 4px;
}
.dataTables_scrollHeadInner{
    margin: 0 auto;
  }
</style>
<div class="col_three_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" id="frmrceate" action="<?php echo base_url('Baphp_persediaan_norangka/store'); ?>" style="font-color:black;">
      <input type="hidden" name="id_baphp_persediaan" value="<?php echo $id_baphp_persediaan;?>"/>
      <input type="hidden" name="id_kontrak_pusat" value="<?php echo $id_kontrak_pusat;?>"/>
      <div>
        <div style="float:left"><a href="<?php echo base_url('Baphp_persediaan_norangka?id_baphp_persediaan='.$id_baphp_persediaan) ?>" class="btn btn-warning">&laquo; Kembali</a></div>
        <div style="float:right"><input type="submit" name="submit" href="#" class="btn btn-primary" style="width:100px;" value="Pilih"></input></div>
      </div>
      <p style="clear:both"></p>
      <hr>
      <table class="table table-striped table-bordered" id="Table1">
      <thead>
        <tr>
          <td></td>
          <?php foreach($cols as $key=>$val){ ?>
            <td><input type="text" data-column="<?php echo $key;?>"  class="search-input-text"></td>
          <?php } ?>
        </tr>
        <tr>
            <th></th>
          <?php foreach($cols as $key=>$val){ ?>
            <th><?php echo $val['caption'];?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
      </tbody>
      </table>
    </form>
  </div>
</div>
<script>  

  $(document).ready(function() {    
      $('.input-daterange').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
      });
  });
  
  var table = $('#Table1').DataTable({
    // "pageLength": 10,
    // "lengthChange": true,
    "processing" : true,
    "serverSide" : true,
    "searching": true,
    "scrollX" : true,
    "scrollY" : 450,
    "fixedHeader" : true,
    "ajax": {
        "type": "POST",
        "url": '<?php echo base_url("Norangka/index_unselbaphp_json"); ?>',
        <?php if(isset($baphp_persediaan)){ ?>
        "data": {
           id_kontrak_pusat : '<?php echo $baphp_persediaan->id_kontrak_pusat; ?>',
        },
        <?php } ?>
        "dataType": "json",
    },
    'columnDefs': [
         {
            'targets': 0,
            'checkboxes': {
               'selectRow': true
            }
         }
      ],
      'select': {
         'style': 'multi'
      },
    "drawCallback": function( settings ) {
       
    },
    columns: [
        {data: "id"},
      <?php foreach($cols as $val){ ?>
        { data: "<?php echo $val['column'];?>" },
      <?php } ?>
    ],

    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:last-child').attr('nowrap', 'nowrap');
    }
  });

  function readURL(input, no) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      $('#image_preview_'+no).show();
        reader.onload = function(e) {
        $('#image_preview_'+no).attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $('.search-input-text').on( 'keyup click', function () {   // for text boxes
      var i =$(this).attr('data-column');  // getting column index
      var v =$(this).val();  // getting search input value
      table.columns(i).search(v).draw();
  });

   // Handle form submission event
   $('#frmrceate').on('submit', function(e){
      var form = this;

      var rows_selected = table.column(0).checkboxes.selected();

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
         );
      });
   });

  $(".numberFilter").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
      // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
      ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
      // Allow: home, end, left, right, down, up
      (e.keyCode >= 35 && e.keyCode <= 40)) {
      // let it happen, don't do anything
      return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
  });
  function addCommas(nStr) {
      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
</script>