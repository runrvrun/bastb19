<form method="post" action="<?php echo base_url('HakAkses/Simpan'); ?>">
  <div class="col-md-12 npl npr mb20 dt-buttons">
  <input type="submit" class="button button-circle" value="SIMPAN">
</div>
  <table id="Table1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>MENU</th>
      <th>SUBMENU 1</th>
      <th>SUBMENU 2</th>
      <th>ADMIN LO</th>
      <th>ADMIN PROVINSI</th>
      <th>ADMIN KABUPATEN/KOTA</th>
      <th>ADMIN PENYEDIA PUSAT</th>
      <th>ADMIN PENYEDIA TP PROVINSI</th>
      <th>ADMIN HIBAH</th>
      <th>ADMIN KHUSUS</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      foreach($menus as $parent){
        if($parent->menu_level == 'PARENT'){
          echo "
          <tr>
            <td>".$parent->menu_name."</td>
            <td></td>
            <td></td>
            <td><input type='checkbox' name='".$parent->id."_lo' id='".$parent->id."_lo' value='1' ".($parent->admin_lo_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$parent->id."_provinsi' id='".$parent->id."_provinsi' value='1' ".($parent->admin_provinsi_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$parent->id."_kabupaten' id='".$parent->id."_kabupaten' value='1' ".($parent->admin_kabupaten_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$parent->id."_penypusat' id='".$parent->id."_penypusat' value='1' ".($parent->admin_penyedia_pusat_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$parent->id."_penyprov' id='".$parent->id."_penyprov' value='1' ".($parent->admin_penyedia_provinsi_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$parent->id."_hibah' id='".$parent->id."_hibah' value='1' ".($parent->admin_hibah_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$parent->id."_khusus' id='".$parent->id."_khusus' value='1' ".($parent->admin_khusus_acc == 1 ? 'checked' : '')."></td>
          </tr>";
        }
        foreach($menus as $menu){

          if($menu->menu_level == 'MENU' and $menu->menu_parent == $parent->id){
            echo "
            <tr>
              <td></td>
              <td>".$menu->menu_name."</td>
              <td></td>
              <td><input type='checkbox' name='".$menu->id."_lo' id='".$menu->id."_lo' value='1' ".($menu->admin_lo_acc == 1 ? 'checked' : '')."></td>
              <td><input type='checkbox' name='".$menu->id."_provinsi' id='".$menu->id."_provinsi' value='1' ".($menu->admin_provinsi_acc == 1 ? 'checked' : '')."></td>
              <td><input type='checkbox' name='".$menu->id."_kabupaten' id='".$menu->id."_kabupaten' value='1' ".($menu->admin_kabupaten_acc == 1 ? 'checked' : '')."></td>
              <td><input type='checkbox' name='".$menu->id."_penypusat' id='".$menu->id."_penypusat' value='1' ".($menu->admin_penyedia_pusat_acc == 1 ? 'checked' : '')."></td>
              <td><input type='checkbox' name='".$menu->id."_penyprov' id='".$menu->id."_penyprov' value='1' ".($menu->admin_penyedia_provinsi_acc == 1 ? 'checked' : '')."></td>
              <td><input type='checkbox' name='".$menu->id."_hibah' id='".$menu->id."_hibah' value='1' ".($menu->admin_hibah_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$menu->id."_khusus' id='".$menu->id."_khusus' value='1' ".($menu->admin_khusus_acc == 1 ? 'checked' : '')."></td>
            </tr>";
          }

          foreach($menus as $submenu){

            if($submenu->menu_level == 'SUBMENU' and $submenu->menu_parent == $menu->id and $menu->menu_parent == $parent->id){
              echo "
              <tr>
                <td></td>
                <td></td>
                <td>".$submenu->menu_name."</td>
                <td><input type='checkbox' name='".$submenu->id."_lo' id='".$submenu->id."_lo' value='1' ".($submenu->admin_lo_acc == 1 ? 'checked' : '')."></td>
                <td><input type='checkbox' name='".$submenu->id."_provinsi' id='".$submenu->id."_provinsi' value='1' ".($submenu->admin_provinsi_acc == 1 ? 'checked' : '')."></td>
                <td><input type='checkbox' name='".$submenu->id."_kabupaten' id='".$submenu->id."_kabupaten' value='1' ".($submenu->admin_kabupaten_acc == 1 ? 'checked' : '')."></td>
                <td><input type='checkbox' name='".$submenu->id."_penypusat' id='".$submenu->id."_penypusat' value='1' ".($submenu->admin_penyedia_pusat_acc == 1 ? 'checked' : '')."></td>
                <td><input type='checkbox' name='".$submenu->id."_penyprov' id='".$submenu->id."_penyprov' value='1' ".($submenu->admin_penyedia_provinsi_acc == 1 ? 'checked' : '')."></td>
                <td><input type='checkbox' name='".$submenu->id."_hibah' id='".$submenu->id."_hibah' value='1' ".($submenu->admin_hibah_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$submenu->id."_khusus' id='".$submenu->id."_khusus' value='1' ".($submenu->admin_khusus_acc == 1 ? 'checked' : '')."></td>
              </tr>";
            }
            
          }
        }
      }

      foreach($cruds as $crud){
         echo "
          <tr>
            <td>".$crud->crud_action."</td>
            <td></td>
            <td></td>
            <td><input type='checkbox' name='".$crud->id."_lo_crud' id='".$crud->id."_lo_crud' value='1' ".($crud->admin_lo_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$crud->id."_provinsi_crud' id='".$crud->id."_provinsi_crud' value='1' ".($crud->admin_provinsi_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$crud->id."_kabupaten_crud' id='".$crud->id."_kabupaten_crud' value='1' ".($crud->admin_kabupaten_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$crud->id."_penypusat_crud' id='".$crud->id."_penypusat_crud' value='1' ".($crud->admin_penyedia_pusat_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$crud->id."_penyprov_crud' id='".$crud->id."_penyprov_crud' value='1' ".($crud->admin_penyedia_provinsi_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$crud->id."_hibah_crud' id='".$crud->id."_hibah_crud' value='1' ".($crud->admin_hibah_acc == 1 ? 'checked' : '')."></td>
            <td><input type='checkbox' name='".$crud->id."_khusus_crud' id='".$crud->id."_khusus_crud' value='1' ".($crud->admin_khusus_acc == 1 ? 'checked' : '')."></td>
          </tr>";

      }
      // for($i=0; $i<count($akses); $i++){
      //   if($akses[$i]->menu_level == 'PARENT'){
      //     echo "
      //     <tr>
      //       <td>".$akses[$i]->menu_name."</td>
      //       <td></td>
      //       <td></td>
      //       <td><input type='checkbox' name='".$akses[$i]->id."_lo' id='".$akses[$i]->id."_lo' value='1'></td>
      //       <td><input type='checkbox' name='".$akses[$i]->id."_provinsi' id='".$akses[$i]->id."_provinsi' value='1'></td>
      //       <td><input type='checkbox' name='".$akses[$i]->id."_kabupaten' id='".$akses[$i]->id."_kabupaten' value='1'></td>
      //       <td><input type='checkbox' name='".$akses[$i]->id."_penypusat' id='".$akses[$i]->id."_penypusat' value='1'></td>
      //       <td><input type='checkbox' name='".$akses[$i]->id."_penyprov' id='".$akses[$i]->id."_penyprov' value='1'></td>
      //     </tr>"; 
      //   }
           
      // }
    ?>
  </tbody>
  </table>
</form>
<script>
  // var table = $('#Table1').DataTable({
  //   "paging": false,
  //   "lengthChange": false,
  //   "searching": false,
  //   "scrollY" : 600,
  //   "scrollX" : true,
  //   "fixedHeader" : true,
  // });
  $(document).ready(function() {
    
    <?php 
      foreach($menus as $menu){
    ?>
        
        // CheckAccess(<?php echo $menu->id; ?>, 'ADMIN LO');
        // CheckAccess(<?php echo $menu->id; ?>, 'ADMIN PROVINSI');
        // CheckAccess(<?php echo $menu->id; ?>, 'ADMIN KABUPATEN');
        // CheckAccess(<?php echo $menu->id; ?>, 'ADMIN PENYEDIA PUSAT');
        // CheckAccess(<?php echo $menu->id; ?>, 'ADMIN PENYEDIA PROVINSI');

    <?php
      }
    ?>
  });
  
  function CheckAccess(idmenu, role)
  {
    $.ajax({
      url: "<?php echo base_url('HakAkses/GetByMenuRole'); ?>",
      dataType: 'JSON',
      type: 'GET',
      data: {id_menu : idmenu, role_pengguna : role},
      success: function(data) {
          if(data == 1){
            if(role == 'ADMIN LO'){
              $('#'+idmenu+"_lo").prop('checked', 1);
            }
            if(role == 'ADMIN PROVINSI'){
              $('#'+idmenu+"_provinsi").prop('checked', 1);
            }
            if(role == 'ADMIN KABUPATEN'){
              $('#'+idmenu+"_kabupaten").prop('checked', 1);
            }
            if(role == 'ADMIN PENYEDIA PUSAT'){
              $('#'+idmenu+"_penypusat").prop('checked', 1);
            }
            if(role == 'ADMIN PENYEDIA PROVINSI'){
              $('#'+idmenu+"_penyprov").prop('checked', 1);
            }
            
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
      }
    });
  }
</script>
