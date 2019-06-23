<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="BASTB" />

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
	
	<link rel="stylesheet" href="<?php echo base_url('assets/css/main.css?v=0.0.2'); ?>" type="text/css" />

	<link rel="stylesheet" href="<?php echo base_url('assets/theme/css/responsive.css'); ?>" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- jQuery -->
	<script src="<?php echo base_url('assets/js/vendor/jquery/jquery.min.js'); ?>"></script>

	<!-- Boostrap Core JavaScript -->
	<script src="<?php echo base_url('assets/css/vendor/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>

	<!-- Plugin JavaScript -->
	<script src="<?php echo base_url('assets/js/vendor/ytplayer/jquery.mb.YTPlayer.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
  
	<!-- Document Title
	============================================= -->
	<title>BASTB App | Home</title>

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<?php include 'navbar.php'; ?>

	    <section id="slider" class="force-full-screen full-screen">

			<div class="force-full-screen full-screen dark section nopadding nomargin noborder ohidden">

				<div class="container clearfix">
					<div class="slider-caption slider-caption-center">
						<h3 data-animate="fadeInDown">BANTUAN ALAT DAN MESIN PERTANIAN</h3>
						<h2 data-animate="fadeInDown" data-delay="200">TAHUN ANGGARAN <?php echo $this->session->userdata('logged_in')->tahun_pengadaan; ?></h2>

						<h3 data-animate="fadeInUp" style="margin-top: 30%;">DIREKTORAT ALAT DAN MESIN PERTANIAN</h3>
						<p data-animate="fadeInUp" data-delay="200">DIREKTORAT JENDERAL PRASARANA DAN SARANA PERTANIAN</p>
						<p data-animate="fadeInUp" data-delay="200">KEMENTERIAN PERTANIAN</p>
					</div>
				</div>

				<div class="video-wrap">
					<video poster="<?php echo base_url('assets/theme/images/videos/vislog.jpg'); ?>" preload="auto" loop autoplay muted>
						<source src='<?php echo base_url('assets/theme/images/videos/Background3.mp4'); ?>' type='video/mp4' />
						<source src='<?php echo base_url('assets/theme/images/videos/vislog.webm'); ?>' type='video/webm' />
					</video>
					<div class="video-overlay" style="background-color: rgba(0,0,0,0.45);"></div>
				</div>
				
			</div>

		</section>

		<!-- Footer
		============================================= -->
		<footer id="footer" class="dark">

			<!-- Copyrights
			============================================= -->
			<div id="copyrights">

				<div class="container clearfix">

					<div class="col_half">
						<!-- Copyrights &copy; 2018 DIREKTORAT ALAT DAN MESIN PERTANIAN.<br> -->
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

	<!-- External JavaScripts
	============================================= -->
	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/plugins.js'); ?>"></script>

	<!-- Footer Scripts
	============================================= -->
	<script type="text/javascript" src="<?php echo base_url('assets/theme/js/functions.js'); ?>"></script>

</body>
</html>