<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$baseurl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$baseurl .= "://".$_SERVER['HTTP_HOST'];
$baseurl .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>SchoolX Error Page</title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="style/cms/favicon.png" rel="shortcut icon">
    <link href="style/cms/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet" type="text/css">
    <link href="style/cms/bower_components/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="style/cms/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="style/cms/bower_components/dropzone/dist/dropzone.css" rel="stylesheet">
    <link href="style/cms/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="style/cms/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
    <link href="style/cms/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" rel="stylesheet">
  <link href="style/cms/icon_fonts_assets/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="style/cms/icon_fonts_assets/picons-thin/style.css" rel="stylesheet">
    <link href="style/cms/css/main.css?version=3.3" rel="stylesheet">
	<script src="<?php echo $baseurl;?>assets/js/jquery-1.11.0.min.js"></script>
	<script src="<?php echo $baseurl;?>assets/js/jquery-1.11.3.min.js"></script>

  </head>

  <body class="auth-wrapper login" style="background: url('uploads/bglogin.jpg');background-size: cover;background-repeat: no-repeat;">
    
      <div class="auth-box-w">
			<div class="logo-w">
			  <a href="<?php echo $baseurl;?>"><img alt="" src="<?php echo $baseurl;?>uploads/logo-color.png" width="100%"></a>
			</div>
			<h4 class="auth-header">
			  <?php echo 'Somethong Went Wrong!!!!';?>
			</h4>
	  
	</div>
	


 


 
  <script src="style/cms/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="style/cms/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="style/cms/bower_components/moment/moment.js"></script>
    <script src="style/cms/bower_components/chart.js/dist/Chart.min.js"></script>
    <script src="style/cms/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="style/cms/bower_components/bootstrap-validator/dist/validator.min.js"></script>
    <script src="style/cms/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="style/cms/bower_components/tether/dist/js/tether.min.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/util.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/alert.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/button.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/carousel.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/collapse.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/dropdown.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/modal.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/tab.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/tooltip.js"></script>
    <script src="style/cms/bower_components/bootstrap/js/dist/popover.js"></script>
  <script src="style/cms/js/main.js?version=3.3"></script>
  
  



  </body>
</html>