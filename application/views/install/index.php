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
	<script src="<?php echo base_url();?>assets/js/jquery-1.11.0.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/jquery-1.11.3.min.js"></script>


	

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
	  <form id="reg" method="post" action="<?php echo base_url() . 'index.php?loading/setup';?>" role="form">
        <div class="logo-wy">
          <a href="<?php echo base_url();?>"><img alt="" src="uploads/logo-color.png" width="35%"></a>
        </div>

          <div class="step-content" id="stepContent3">
		<?php $formdata = array('enctype' => 'multipart/form-data' , 'setup_process' =>1); ?>
          <legend><span> School System Settings</span></legend>
      <div class="row">
      <div class="form-group col-sm-6">
      <label class="col-form-label" for=""> School Subdomain Name</label>
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0047_home_flat"></i>
        </div>
        <input class="form-control" placeholder="Characters only- No Spaces" name="system_name" required type="text">
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
                <option value="arabic">Arabic</option>
				<option value="english">English</option>
				<option value="english">French</option>
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
		 <div class="col-sm-12">
                <div class="form-group">
                  <label for=""> Authorization Token*</label><input class="form-control" required placeholder="If you don't have your token, please contact us at (+202 2310 3490) or (info@schoolxapp.com)" name="authtoken" type="text">
                </div>
              </div>
                <div class="col-sm-6">
                <div class="form-group">
                  <label for=""> Admin username*</label><input class="form-control" required placeholder="Admin Username" name="admin" type="text">
                </div>
              </div>
               <div class="col-sm-6">
                <div class="form-group">
                  <label for=""> Admin password*</label><input class="form-control" required placeholder="Admin Password" id="password" name="adminpass" type="password">
                </div>
              </div> 
			  <div class="col-sm-6">
                
              </div> 
			  <div class="col-sm-6">
              <input type="checkbox" onclick="ShowPassFunction()">Show Password	
              </div> 			  
          
        </div>
		      <div class="form-buttons-w text-right">
                  <center><button class="btn btn-rounded btn-primary"    name="register" type="submit" id="register" >Register</button><center>
              </div>
			  
			<br><br>
		    <div id="load" style='display: none;'>
				  <center><h2>Make sure all fields are filled out.</h2><center>
				  <center><h2>Please wait while we create your school work area.....</h2><center>
				  <center><div class="loader"></div><center>
            </div>

		</div>
		</form>
	</div>
	
<script>
  function ShowPassFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
  
</script>

 <script type='text/javascript'>
$(document).ready(function(){	
 $("#register").click(function(){ 
$("#load").show();
 var dataString = $("#reg").serialize();
  $.ajax({
   beforeSend: function(){
    // Show image container
    $("#load").show();
   },	  
   url: baseurl + 'index.php?loading/setup',
   type: 'POST',
   data: dataString,
   
   success: function(response){
    $('.response').empty();
    $('.response').append(response);
   },
   complete:function(data){
    // Hide image container
    $("#load").hide();
   }
  });
 
 });
});
</script>	



 
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