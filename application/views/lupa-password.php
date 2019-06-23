<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" />
  <title>BASTB | Log In</title>
  
  <!-- <link href="<?php echo base_url('assets/css/vendor/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet" media="screen"> -->

  <!-- <link href="<?php echo base_url('assets/css/login.css'); ?>" rel="stylesheet"> -->
  <style>
    * {
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
    }
    html, body {
      font-family: Arial, Helvetica, sans-serif;
      height: 100%;
      margin: 0 !important;
      padding: 0;
    }
    a {
      color: #222;
      text-decoration: none;
    }
    .left { float: left; }
    .right { float: right; }
    .clearfix { clear: both; }
    .container {
      clear: left;
      float: left;
      width: 100%;
      height: 100%;
      min-height: 100%;
      overflow: hidden;
      background: #fff;
    }
    #col1 {
      float: left;
      width: 60%;
      height: 100%;
      min-height: 100%;
      position: relative;
      background: url('<?php echo base_url("assets/img/lupapass_bg.jpg"); ?>') no-repeat;
      background-size: 100% 100%;
    }
    #col2 {
      float: left;
      width: 40%;
      height: 100%;
      min-height: 100%;
      position: relative;
      padding: 15px 20px;
    }
    #col2 .top { text-align: center; }
    #col2 .top h2 {
      font-size: 24px;
      font-weight: bold;
      text-transform: uppercase;
    }
    #col2 .center {
      padding-top: 20px;
      padding-bottom: 20px;
    }
    #col2 form .form-group {
      margin: 15px 5px;
    }
    #col2 form label {
      font-size: 13px;
      display: block;
      margin-bottom: 3px;
    }
    #col2 form input[type=text],
    #col2 form input[type=password],
    #col2 form select {
      width: 100%;
      padding: 7px;
      border-radius: 5px;
      border: 1px solid black;
    }
    #col2 form select {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      padding: 7px;
    }
    #col2 form button[type=submit] {
      padding: 5px 20px;
      border-radius: 5px;
      color: #fff;
      font-size: 14px;
      text-align: center;
      text-transform: uppercase;
      background-color: #385624;
    }
    #col2 form button[type=button] {
      padding: 5px 20px;
      border-radius: 5px;
      color: #fff;
      font-size: 14px;
      text-align: center;
      text-transform: uppercase;
      background-color: #a70000;
    }
    #col2 form a {
      font-size: 12px;
    }
    #col2 .bottom {
      width: 100%;
      text-align: center;
      position: absolute;
      bottom: 0;
      padding: 15px 0;
    }
    #col2 .bottom .copyright {
      margin: 3px 0;
      font-size: 13px;
      font-weight: bold;
    }
    #col2 .bottom .copyname {
      margin: 0;
      font-size: 14px;
      font-weight: bold;
      text-transform: uppercase;
    }
  </style>
</head>
<body>
  <!-- <div style="display:<?php if(!$this->session->flashdata('error')) echo 'none';?>; position:absolute; top:0; left:0; width: 100%; z-index: 99999 !important; font-family:Arial" id="error-alert" class="alert alert-danger col-sm-12"> -->
    <!-- error msg here -->
    <?php 
      // echo $this->session->flashdata('error'); 
    ?>
  <!-- </div> -->
  <div class="container">
    <div id="col1"></div>
    <div id="col2">
      <div class="top">
        <a href="<?php echo base_url('Welcome'); ?>">
          <img src="<?php echo base_url('assets/img/logo_png.png'); ?>" width="100" alt="Logo">
        </a>
        <h2>Direktorat<br>alat dan mesin pertanian</h2>
      </div>
      <div class="center">
        <form method="post" action="<?php echo base_url('LupaPassword/doSubmit'); ?>">
          <div class="form-group">
            <label>Email Address:</label>
            <input type="text" name="txtEmailAddress" placeholder="Alamat Email" autofocus>
          </div>
          <div class="clearfix">
            <font color="red">
              <?php 
                echo $this->session->flashdata('error'); 
              ?>
            </font>
          </div>
          <div class="form-group">
            <div class="left">
              <button type="submit">Kirim Password Baru</button>
              <a href="<?php echo base_url('Auth/Login'); ?>">
                <button type="button">Kembali ke Halaman Login</button>
              </a>
            </div>
            <div class="clearfix">
            </div>
          </div>
        </form>
      </div>
      <div class="bottom">
        <p class="copyright">Copyright &copy; 2018</p>
        <p class="copyname">Direktorat jenderal prasarana dan sarana pertanian<br>kementerian pertanian</p>
      </div>
    </div>
  </div>
</body>
<!-- jQuery -->
<script src="<?php echo base_url('assets/js/vendor/jquery/jquery.min.js'); ?>"></script>
<script>
    setTimeout(function() {
        $('#error-alert').fadeOut('slow');
    }, 3000);
</script>
</html>