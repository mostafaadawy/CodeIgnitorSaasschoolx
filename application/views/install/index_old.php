<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to New School Registeration wizard | SchoolX</title>
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
	<style>
.loader {
	border: 16px solid #f3f3f3; /* Light grey */
	border-top: 16px solid blue;
	border-right: 16px solid green;
	border-bottom: 16px solid red;
	border-radius: 50%;
	width: 120px;
	height: 120px;
	animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
  </head>
  <body class="auth-wrapper login" style="background: url('uploads/bglogin.jpg');background-size: cover;background-repeat: no-repeat;">
      <div class="auth-box-w wider">
        <div class="logo-wy">
          <a href="<?php echo base_url();?>"><img alt="" src="uploads/logo-color.png" width="35%"></a>
        </div>

          <div class="step-content" id="stepContent3">
		  <?php
		    $formSubmit = $this->input->post('register');
			if ($formSubmit == ""):
			$formdata = array('enctype' => 'multipart/form-data' , 'setup_process' =>1);
			//$formdata['setup_process'] =1;
            echo form_open(base_url() . 'index.php?install/setup' , $formdata );
		  ?>
          <legend><span> System Settings</span></legend>
      <div class="row">
      <div class="form-group col-sm-6">
      <label class="col-form-label" for=""> System Subdomain Name</label>
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0047_home_flat"></i>
        </div>
        <input class="form-control" placeholder="Choose your subdomain" name="system_name" required type="text">
        </div>
      </div>
      <div class="form-group col-sm-6">
      <label class="col-form-label" for=""> School Name</label>
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
        </div>
        <input class="form-control" placeholder="Your Beautiful School" name="system_title" required type="text">
        </div>
        </div>
        <div class="form-group col-sm-6">
        <label class="col-form-label" for=""> Language</label>
          <div class="input-group">
          <div class="input-group-addon">
            <i class="picons-thin-icon-thin-0307_chat_discussion_yes_no_pro_contra_conversation"></i>
          </div>
          <select class="form-control" name="language" required="">
                <option value="">Select</option>
                <option value="english">English</option>
                <option value="arabic">Arabic</option>
          </select>
        </div>
        </div>
      <div class="form-group col-sm-6">
      <label class="col-form-label" for=""> Currency</label>
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0406_money_dollar_euro_currency_exchange_cash"></i>
        </div>
        <input class="form-control" placeholder="EGP" name="currency" type="text">
        </div>
        </div>
        <div class="form-group col-sm-12">
        <label class="col-form-label" for=""> Theme</label>
          <div class="input-group">
          <div class="input-group-addon">
            <i class="picons-thin-icon-thin-0307_chat_discussion_yes_no_pro_contra_conversation"></i>
          </div>
          <select class="form-control" name="theme" required="">
                <option value="">Select theme color</option>
                <option value="red">Red</option>
                <option value="blue">Blue</option>
                <option value="yellow">Yellow</option>
                <option value="success">Success</option>
                <option value="main">Main</option>
          </select>
        </div>
        </div>
                <div class="col-sm-6">
                <div class="form-group">
                  <label for=""> Admin username*</label><input class="form-control" required placeholder="Admin Username" name="admin" type="text">
                </div>
              </div>
               <div class="col-sm-6">
                <div class="form-group">
                  <label for=""> Admin password*</label><input class="form-control" required placeholder="Admin Password" name="adminpass" type="password">
                </div>
              </div>  
          
        </div>
		      <div class="form-buttons-w text-right">
                  <center><button class="btn btn-primary"  value="register" name="register" type="submit">Register</button><center>
              </div>
			  
		<?php endif;?>	  
	    
		<?php if ($formSubmit == "register"):
		      $formdata['setup_process'] = 1;
              echo form_open(base_url() . 'index.php?install/setup' , $formdata );
			?>
		    </div>
				  <center><h2>Please Wait While We Prepare Your Beautiful School Work Area.....</h2><center>
				  <center><div class="loader"></div><center>
            </div>
			
		<?php endif;?>
		</div>
         <?php echo form_close();?>
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

<script src="<?php echo base_url();?>style/cms/js/toastr.js"></script>

<?php if ($this->session->flashdata('flash_message') != ""):?>

<script type="text/javascript">
	toastr.success('<?php echo $this->session->flashdata("flash_message");?>');
</script>

<?php endif;?>

<?php if ($this->session->flashdata('error_message') != ""):?>

<script type="text/javascript">
	toastr.error('<?php echo $this->session->flashdata("error_message");?>');
</script>

<?php endif;?>

  </body>
</html>