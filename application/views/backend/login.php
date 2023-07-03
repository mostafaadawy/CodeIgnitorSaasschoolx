<?php $title = $this->db->get_where('settings' , array('type'=>'system_title'))->row()->description; ?>
<?php $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?>
<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <title><?php echo get_phrase('login');?> | <?php echo $title;?></title>
    
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="style/cms/favicon.png" rel="shortcut icon">
    <link href="<?php echo base_url();?>style/cms/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>style/cms/bower_components/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/bower_components/dropzone/dist/dropzone.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/icon_fonts_assets/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/icon_fonts_assets/picons-thin/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>style/cms/css/main.css?version=3.1" rel="stylesheet">
    <script src="<?php echo base_url();?>assets/js/jquery-1.11.0.min.js"></script>
  </head>
    <body class="auth-wrapper login" style="background: url('<?php echo base_url();?>uploads/bglogin.jpg');background-size: cover;background-repeat: no-repeat;">
  <script type="text/javascript">var baseurl = '<?php echo base_url();?>';</script>
      <div class="auth-box-w">
        <div class="logo-w">
          <a href="<?php echo base_url();?>"><img alt="" src="<?php echo base_url();?>uploads/logo-color.png" width="100%"></a>
        </div>
        <h4 class="auth-header">
          <?php echo $this->db->get_where('settings', array('type' => 'system_title'))->row()->description;?>
        </h4>
        <div id="errorss">
          <div class="form-login-error">
            <center><a class="btn btn-sm btn-rounded btn-danger" style="color:white"><?php echo get_phrase('invalid_data');?>.</a></center>
			<button type="button" class="close" id="clearMsg"><span aria-hidden="true">&times;</span></button>
			<span id="message"></span>
          </div>
        </div>
        <form method="post" role="form"  id="form_login" >
          <div class="form-group">
            <label for=""><?php echo get_phrase('username');?></label><input class="form-control" name="email" id="email" placeholder="<?php echo get_phrase('username');?>" required type="text">
            <div class="pre-icon icon-user"></div>
          </div>
          <div class="form-group">
            <label for=""><?php echo get_phrase('password');?></label><input class="form-control" name="password" id="password" placeholder="<?php echo get_phrase('password');?>" required type="password">
            <div class="pre-icon icon-lock"></div>
          </div>
		  <input type="checkbox" onclick="ShowPassFunction()">Show Password
          <div class="buttons-w logs row">
            <div class="col-sm-6"><button class="btn btn-rounded btn-primary"   id="login" ><?php echo get_phrase('login');?></button></div>
            <?php if($this->db->get_where('settings', array('type' => 'register'))->row()->description == 1):?>
			<div class="col-sm-6 reg"><a class="btn btn-rounded btn-secondary"  href="<?php echo base_url();?>register/"><?php echo get_phrase('register');?></a></div>			<?php endif;?>
          </div>
		  <br><center><a href="<?php echo base_url();?>login/lost_password/"><?php echo get_phrase('recover_your_password');?></a></center>
        </form>
      </div>
       <script src="<?php echo base_url();?>assets/js/gsap/main-gsap.js"></script>
	<script src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/neon-login.js"></script>
       <script src="<?php echo base_url();?>style/cms/js/toastr.js"></script>
  </body>
  
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

  
  <script type="text/javascript">
    $('#errorss').hide();
  </script>
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
</html>