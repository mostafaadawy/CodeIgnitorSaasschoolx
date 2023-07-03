<?php $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?>
<?php $student_info = $this->db->get_where('student', array('student_id' => $this->session->userdata('login_user_id')))->result_array();
  foreach($student_info as $row):
?>
<div class="content-w">
<ul class="breadcrumb hidden-xs-down hidden-sm-down">
<div class="back">
	<a href="<?php echo base_url();?>student/panel/"><i class="os-icon os-icon-common-07"></i></a>
</div>
</ul>
  <div class="content-i">
    <div class="content-box">
    <div class="row">
  <div class="col-sm-5 m-b">
    <div class="user-profile compact">
      <div class="up-head-w" style="background-image:url(<?php echo base_url().$subdomain;?>uploads/back.jpg)">        
        <div class="up-main-info">
    <div class="user-avatar-w"> 
    <div class="user-avatar">
      <img alt="" src="<?php echo $this->crud_model->get_image_url('student', $row['student_id']);?>">
      </div></div>
          <h2 class="up-header"><?php echo $row['name'];?></h2>
          <h6 class="up-sub-header"><?php echo get_phrase('student');?></h6>
        </div>
        <svg class="decor" width="842px" height="219px" viewBox="0 0 842 219" preserveAspectRatio="xMaxYMax meet" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(-381.000000, -362.000000)" fill="#FFFFFF"><path class="decor-path" d="M1223,362 L1223,581 L381,581 C868.912802,575.666667 1149.57947,502.666667 1223,362 Z"></path></g></svg>
      </div>
    
      <div class="up-controls">
        <div class="row">
          <div class="col-sm-6">
           <div class="value-pair">
              <div class="label">
                <?php echo get_phrase('status');?>:
              </div>
              <?php $status = $row['student_session']; ?>
            <?php if($status == 1):?>
              <div class="value badge badge-pill badge-success">
                <?php echo get_phrase('active');?>
              </div>
            <?php endif;?>
            <?php if($status != 1):?>
              <div class="value badge badge-pill badge-danger">
                <?php echo get_phrase('inactive');?>
              </div>
            <?php endif;?>
            </div>
          </div>
        </div>
      </div>
    
    
       <div class="padded b-t">
    <div class="row">
          <div class=" col-sm-12">
              <div class="infopadd row"><div class="infogi col-sm-2"><?php echo get_phrase('username');?>:</div> <div class="infogg col-sm-9"> @<?php echo $row['username'];?></div></div>
        <div class="infopadd row"><div class="infogi col-sm-2"><?php echo get_phrase('email');?>:</div> <div class="infogg col-sm-9"> <?php echo $row['email'];?></div></div>
        <div class="infopadd row"><div class="infogi col-sm-2"><?php echo get_phrase('phone');?>:</div> <div class="infogg col-sm-9"> <?php echo $row['phone'];?></div></div>
        <div class="infopadd row"><div class="infogi col-sm-2"><?php echo get_phrase('address');?>:</div> <div class="infogg col-sm-9"> <?php echo $row['address'];?></div></div>
        <div class="infopadd row"><div class="infogi col-sm-2"><?php echo get_phrase('birthday');?>:</div> <div class="infogg col-sm-9"> <?php echo $row['birthday'];?></div></div>
        <div class="infopadd row"><div class="infogi col-sm-2"><?php echo get_phrase('gender');?>:</div> <div class="infogg col-sm-9"> <?php echo $row['sex'];?></div></div>
          </div></div>
      </div>
     
    </div>
</div>

  <div class="col-sm-7">
    <div class="element-wrapper">
      <div class="element-box">
          <div class="element-info">
            <div class="element-info-with-icon">
              <div class="element-info-icon">
                <div class="os-icon os-icon picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></div>
              </div>
              <div class="element-info-text">
                <h5 class="element-inner-header"><?php echo get_phrase('personal_information');?></h5>
              </div>
            </div>
          </div>
          <?php echo form_open(base_url() . 'student/my_profile/update' , array('enctype' => 'multipart/form-data'));?>
		        <div class="form-group row">
              <label class="col-sm-3 col-form-label" for=""> <?php echo get_phrase('name');?></label>
              <div class="col-sm-9">
              <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0701_user_profile_avatar_man_male"></i>
                 </div>
               <input class="form-control" name="name" required="" readonly type="text" value="<?php echo $row['name'];?>">
               </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('username');?></label>
              <div class="col-sm-9">
               <div class="input-group">
                 <div class="input-group-addon">
            <i class="picons-thin-icon-thin-0313_email_at_sign"></i> 
            </div>
                 <input class="form-control" type="text" value="<?php echo $row['username'];?>" >
               </div>
              </div>
            </div>
			<div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('password');?></label>
            <div class="col-sm-9">
              <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0136_rotation_lock"></i>
               </div>
              <input class="form-control" placeholder="<?php echo get_phrase('update_password');?>" name="password" id="password" type="password">
              </div>
			  <input type="checkbox" onclick="ShowPassFunction()">Show Password
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('email');?></label>
            <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0319_email_mail_post_card"></i>
               </div>
              <input class="form-control" value="<?php echo $row['email'];?>" name="email" type="email">
              </div>
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('phone');?></label>
            <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0289_mobile_phone_call_ringing_nfc"></i>
               </div>
              <input class="form-control" value="<?php echo $row['phone'];?>" name="phone" type="number">
              </div>
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('address');?></label>
            <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0535_navigation_location_drop_pin_map"></i>
               </div>
              <input class="form-control" value="<?php echo $row['address'];?>" name="address" type="text">
              </div>
            </div>
           </div>
           <div class="form-group row">
              <label class="col-sm-3 col-form-label" for=""> <?php echo get_phrase('birthday');?></label>
              <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0447_gift_wrapping"></i>
               </div>
               <input class="form-control" name="birthday"  id="birthday" value="<?php echo $row['birthday'];?>" type="text" value="">
               </div>
              </div>
            </div>
           
           <div class="form-group row">
            <label class="col-sm-3 col-form-label"> <?php echo get_phrase('gender');?></label>
            <div class="col-sm-9">
              <div class="form-check">
               <label class="form-check-label"><input class="form-check-input" disabled="" type="radio" value="Male" <?php if($row['sex'] == 'Male') echo 'checked';?>><?php echo get_phrase('male');?></label>
               <label class="form-check-label"><input class="form-check-input" disabled type="radio" value="Female" <?php if($row['sex'] == 'Female') echo 'checked';?>><?php echo get_phrase('female');?></label>
              </div>
            </div>
           </div>
           <div class="form-group">
			<label class="col-form-label" for=""> <?php echo get_phrase('photo');?></label>
          <div class="profile-side-user">
          <button type="button" class="avatar-preview avatar-preview-128">
				<img id="ava" src="<?php echo base_url().$subdomain;?>uploads/student_image/<?php echo $this->session->userdata('login_user_id');?>.jpg" alt=""/>
				<span class="update">
					<i class="font-icon picons-thin-icon-thin-0617_picture_image_photo"></i>
					<?php echo get_phrase('upload');?>
				</span>
				<input name="userfile" accept="image/x-png,image/gif,image/jpeg" id="imgpre" type="file"/>
			</button></div></div>
		
          <div class="form-buttons-w">
            <button class="btn btn-rounded btn-rounded btn-success" type="submit"> <?php echo get_phrase('update');?></button>
          </div>
          <?php echo form_close();?>
        </div>
      </div>
    </div>
    </div>
    </div>
  </div>
</div>

<?php endforeach;?>
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