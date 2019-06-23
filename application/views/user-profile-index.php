<div class="col_two_fifth col_centered nobottommargin">
  <div class="well well-lg nobottommargin">
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('UserProfile/UpdateData'); ?>">
      <input type="hidden" name="id" value="<?php echo $user_data->id; ?>" />
      <h3>UBAH DATA PROFIL</h3>
      <hr>
      <div class="col_full">
        <label>EMAIL / ID PENGGUNA</label>
        <input type="text" name="id_pengguna" id="id_pengguna" class="form-control" value="<?php echo $user_data->id_pengguna; ?>" readonly />
      </div>
      <div class="col_full">
        <label>ROLE AKUN</label>
        <input type="text" name="role_pengguna" id="role_pengguna" class="form-control" value="<?php echo $user_data->role_pengguna; ?>" readonly />
      </div>
      <?php 
        if($user_data->id_penyedia_pusat){
      ?>
      <div class="col_full">
        <label>PT PENYEDIA</label>
        <input type="text" class="form-control" value="<?php echo $user_data->nama_penyedia_pusat; ?>" readonly />
      </div>
      <?php 
        }
      ?>
      <?php 
        if($user_data->id_penyedia_provinsi){
      ?>
      <div class="col_full">
        <label>PT PENYEDIA</label>
        <input type="text" class="form-control" value="<?php echo $user_data->nama_penyedia_provinsi; ?>" readonly />
      </div>
      <?php
       }
      ?>
      <?php 
        if($user_data->id_provinsi){
      ?>
      <div class="col_full">
        <label>PROVINSI</label>
        <input type="text" class="form-control" value="<?php echo $user_data->nama_provinsi; ?>" readonly />
      </div>
      <?php
       }
      ?>
      <?php 
        if($user_data->id_kabupaten){
      ?>
      <div class="col_full">
        <label>KABUPATEN</label>
        <input type="text" class="form-control" value="<?php echo $user_data->nama_kabupaten; ?>" readonly />
      </div>
      <?php
       }
      ?>
      <div class="col_full">
        <label>NAMA LENGKAP</label>
        <input type="text" name="nama" class="form-control" value="<?php echo $user_data->nama; ?>" required />
      </div>
      <div class="col_full">
        <label>NO TELEPON</label>
        <input type="text" name="no_telepon" class="form-control numberFilter" value="<?php echo $user_data->no_telepon; ?>" required />
      </div>
      <div class="col_full" id="changePassword">
        <label>PASSWORD</label>
        <input type="password" class="form-control" placeholder="Ubah Password" />
      </div>
      <input type="hidden" name="hdnIsChangePassword" id="hdnIsChangePassword" value="0">
      <div class="col_full" id="currentPassword">
        <label>PASSWORD SAAT INI</label>
        <input type="password" name="currentPassword" class="form-control" />
      </div>
      <div class="col_full" id="newPassword">
        <label>PASSWORD BARU</label>
        <input type="password" name="newPassword" class="form-control" />
      </div>
      <div class="col_full" id="reNewPassword">
        <label>KONFIRMASI PASSWORD BARU</label>
        <input type="password" name="reNewPassword" class="form-control" />
      </div>
      <div class="col_full">
        <label>FOTO PROFIL</label>
        <input type="file" name="file_avatar" id="file_avatar" class="form-control" />
        <img id="prev_img" src="<?php echo "../upload/user_profile/".$user_data->file_avatar; ?>" />
      </div>
      <div class="col_full nobottommargin">
        
        <button type="button" class="button button-3d button-white button-light nomargin mr10" onclick="window.history.back();">
            Cancel
        </button>
        
        <button type="submit" class="button button-3d nomargin">Save</button>
      </div>
    </form>
  </div>
</div>
<script>
  function readURL(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#prev_img').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#file_avatar").change(function() {
    readURL(this);
  });

  $(document).ready(function() {
    $('#currentPassword').hide();
    $('#newPassword').hide();
    $('#reNewPassword').hide();
  });

  $('#changePassword').click(function(){
    $('#changePassword').hide();
    $('#currentPassword').show();
    $('#newPassword').show();
    $('#reNewPassword').show();
    $('#hdnIsChangePassword').val("1");
  });

  $(".numberFilter").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
         // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
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
</script>