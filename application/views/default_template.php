<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Vislog" />

	<link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" />

	<!-- Stylesheets
	============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/bootstrap.css'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url('assets/theme/style.css?v=0.0.5'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/dark.css?v=0.0.3'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/font-icons.css'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/animate.css'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/magnific-popup.css'); ?>" type="text/css" />
	
	<!-- <link rel="stylesheet" href="/assets/css/vendor/font-awesome.min.css" type="text/css" /> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

	<link rel="stylesheet" href="<?php echo base_url('assets/css/main.css?v=0.0.2'); ?>" type="text/css" />

	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/responsive.css'); ?>" type="text/css" />

	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- CSS Datatables -->
  	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/components/bs-datatable.css'); ?>" type="text/css" />
  	<link rel="stylesheet" href="<?php echo base_url('assets/datatables/jquery.dataTables.min.css'); ?>" type="text/css" />
  	<link rel="stylesheet" href="<?php echo base_url('assets/datatables/buttons.dataTables.min.css'); ?>" type="text/css" />

  	<!-- CSS SELECT2 -->
  	<link href="<?php echo base_url('assets/select2/css/select2.min.css'); ?>" rel="stylesheet" />

	<!-- External JavaScripts
	============================================= -->
	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/plugins.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/download.js'); ?>"></script>

	<!-- Bootstrap Data Table Plugin -->
  	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/components/bs-datatable.js'); ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url('assets/datatables/jquery.dataTables.min.js'); ?>"></script>

  	<script type="text/javascript" src="<?php echo base_url('assets/datatables/buttons.html5.min.js'); ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url('assets/datatables/dataTables.buttons.min.js'); ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url('assets/datatables/jszip.min.js'); ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url('assets/datatables/pdfmake.min.js'); ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url('assets/datatables/vfs_fonts.js'); ?>"></script>

	<!-- Other JavaScript Plugin -->
	<script src="<?php echo base_url('assets/js/vendor/ytplayer/jquery.mb.YTPlayer.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/select2/js/select2.full.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-number/jquery.number.min.js'); ?>"></script>

	<!-- Datepicker -->
    <link href="<?php echo base_url('assets/datepicker/css/bootstrap-datepicker.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datepicker/css/bootstrap-datepicker.min.css');?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script src="<?php echo base_url('assets/datepicker/js/bootstrap-datepicker.min.js');?>"></script>


	<!-- Document Title
	============================================= -->
	<title>BASTB App | {title}</title>

</head>

<body class="stretched">
	<div style="display:<?php if(!$this->session->flashdata('info')) echo 'none';?>; position:absolute; top:0; left:0; width: 100%; z-index: 99999 !important;" id="info-alert" class="alert alert-success col-sm-12">
	<!-- info msg here -->
	<?php 
	  echo $this->session->flashdata('info'); 
	?>
	</div>

	<div style="display:<?php if(!$this->session->flashdata('error')) echo 'none';?>; position:absolute; top:0; left:0; width: 100%; z-index: 99999 !important; font-family:Arial" id="error-alert" class="alert alert-danger col-sm-12">
	<!-- error msg here -->
	<?php 
	  echo $this->session->flashdata('error'); 
	?>
	</div>

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<?php include 'navbar.php'; ?>

    	<!-- Page Title
	    ============================================= -->
	    <section id="page-title">

	      <div class="container clearfix">
	        <h1>{title}</h1>
	        <ol class="breadcrumb">
	          <li><a href="<?php echo base_url('Home'); ?>">HOME</a></li>
	          <li class="active">{content-path}</li>
	        </ol>
	      </div>

	    </section><!-- #page-title end -->

	    <!-- Content
	    ============================================= -->
	    <section id="content">

	      <div class="content-wrap">

	        <div class="container clearfix" style="color:black; font-color:black; ">
	          {content}
	        </div>

	      </div>

	    </div>


		</div><!-- #wrapper end -->

		<!-- Footer
		============================================= -->
		<footer id="footer" class="dark">

			<!-- Copyrights
			============================================= -->
			<div id="copyrights">

				<div class="container clearfix">

					<div class="col_half">
						<!-- Copyrights &copy; DIREKTORAT ALAT DAN MESIN PERTANIAN 2018.<br> -->
						<!-- <div class="copyright-links"><a href="#">Terms of Use</a> / <a href="#">Privacy Policy</a></div> -->
						Copyrights &copy; 2018 <br>
			            DIREKTORAT ALAT DAN MESIN PERTANIAN <br>
			            DIREKTORAT JENDERAL PRASARANA DAN SARANA PERTANIAN <br>
			            KEMENTERIAN PERTANIAN
					</div>

					<!-- <div class="col_half col_last tright">
						<div class="fright clearfix">
							<a href="#" class="social-icon si-small si-borderless si-facebook">
								<i class="icon-facebook"></i>
								<i class="icon-facebook"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-twitter">
								<i class="icon-twitter"></i>
								<i class="icon-twitter"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-gplus">
								<i class="icon-gplus"></i>
								<i class="icon-gplus"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-pinterest">
								<i class="icon-pinterest"></i>
								<i class="icon-pinterest"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-vimeo">
								<i class="icon-vimeo"></i>
								<i class="icon-vimeo"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-github">
								<i class="icon-github"></i>
								<i class="icon-github"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-yahoo">
								<i class="icon-yahoo"></i>
								<i class="icon-yahoo"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-linkedin">
								<i class="icon-linkedin"></i>
								<i class="icon-linkedin"></i>
							</a>
						</div> -->

						<div class="clear"></div>

						<!-- <i class="icon-envelope2"></i> info@vislog.id 
						<span class="middot">&middot;</span> 
						<i class="icon-headphones"></i> +91-11-6541-6369 
						<span class="middot">&middot;</span> 
						<i class="icon-skype2"></i> VislogOnSkype -->
					</div>

				</div>

			</div><!-- #copyrights end -->

		</footer><!-- #footer end -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- Footer Scripts
	============================================= -->
	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/functions.js'); ?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

	<!-- Custom JavaScript -->
	<script>
	setTimeout(function() {
	    $('#error-alert').fadeOut('slow');
	}, 3000);

	setTimeout(function() {
	    $('#info-alert').fadeOut('slow');
	}, 3000);

    $('input[type=text]').not("#id_pengguna, .nocaps").keyup(function() {
    	$(this).val($(this).val().toUpperCase());
    });
	</script>
</body>
</html>