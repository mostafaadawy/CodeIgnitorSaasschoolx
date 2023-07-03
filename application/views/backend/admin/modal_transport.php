<?php $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?>
<style>
	
	 h4{
		color: white !important;
	}
		  </style>
<?php  $edit_data = $this->db->get_where('transport' , array('transport_id' => $param2) )->result_array();
        foreach($edit_data as $row):
?>    
        <?php echo form_open(base_url() . 'admin/school_bus/update/'.$row['transport_id'], array('enctype' => 'multipart/form-data')); ?>
          <br>
          <div class="form-group row">
        <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('transport_name');?></label>
        <div class="col-sm-8">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0470_bus_transport"></i>
          </div>
        <input class="form-control" name="route_name" value="<?php echo $row['route_name'];?>" required type="text">
        </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('route');?></label>
        <div class="col-sm-8">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0545_map_travel_distance_directions"></i>
          </div>
        <input class="form-control" name="route" value="<?php echo $row['route'];?>" type="text">
        </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('bus_id');?></label>
        <div class="col-sm-8">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0487_van_truck_transport_vehicle"></i>
          </div>
        <input class="form-control" value="<?php echo $row['number_of_vehicle'];?>" name="number_of_vehicle" type="text">
        </div>
        </div>
      </div>
	  
	  <div class="form-group row">
			  <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('supervisor');?></label>
			  <div class="col-sm-8">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0701_user_profile_avatar_man_male"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('supervisor');?>" value="<?php echo $row['supervisor_name'];?>" name="supervisor_name" type="text">
				</div>
			  </div>
			</div>
			
			<div class="form-group row">
			  <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('supervisor_phone');?></label>
			  <div class="col-sm-8">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0289_mobile_phone_call_ringing_nfc"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('supervisor_phone');?>" value="<?php echo $row['supervisor_phone'];?>" name="supervisor_phone" type="text">
				</div>
			  </div>
			</div>
			
			 <div class="form-group row">
            <label class="col-form-label col-sm-4" for=""> <?php echo get_phrase('supervisor_photo');?></label>
            <div class="col-sm-8 profile-side-user">
          <button type="button" class="avatar-preview avatar-preview-128">
				<img id="ava_super_modal" src="<?php echo base_url().$subdomain.'uploads/supervisor_image/' .$row['supervisor_name']. '.jpg';?>" alt=""/>
				<span class="update">
					<i class="font-icon picons-thin-icon-thin-0617_picture_image_photo"></i>
					<?php echo get_phrase('upload');?>
				</span>
				<input name="supervisorfile" accept="image/x-png,image/gif,image/jpeg" id="imgpre_super_modal" type="file"/>
			</button></div>
            </div>
			
	  
	  
      <div class="form-group row">
        <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('driver');?></label>
        <div class="col-sm-8">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0699_user_profile_avatar_man_male"></i>
          </div>
        <input class="form-control" placeholder="<?php echo get_phrase('driver_name');?>" value="<?php echo $row['driver_name'];?>" name="driver_name" type="text">
        </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('driver_phone');?></label>
        <div class="col-sm-8">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0294_phone_call_ringing"></i>
          </div>
        <input class="form-control" value="<?php echo $row['driver_phone'];?>" name="driver_phone" type="text">
        </div>
        </div>
      </div>
	  <div class="form-group row">
            <label class="col-form-label col-sm-4" for=""> <?php echo get_phrase('driver_photo');?></label>
            <div class="col-sm-8 profile-side-user">
          <button type="button" class="avatar-preview avatar-preview-128">
				<img id="ava_driver_modal" src="<?php echo base_url().$subdomain.'uploads/driver_image/' .$row['driver_name']. '.jpg';?>" alt=""/>
				<span class="update">
					<i class="font-icon picons-thin-icon-thin-0617_picture_image_photo"></i>
					<?php echo get_phrase('upload');?>
				</span>
				<input name="driverfile" accept="image/x-png,image/gif,image/jpeg" id="imgpre_driver_modal" type="file"/>
			</button></div>
            </div>
      <div class="form-group row">
        <label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('price');?></label>
        <div class="col-sm-8">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0406_money_dollar_euro_currency_exchange_cash"></i>
          </div>
        <input class="form-control" placeholder="$200" value="<?php echo $row['route_fare'];?>" type="text" name="route_fare">
        </div>
        </div>
      </div>

          <div class="form-buttons-w">
            <button class="btn btn-primary" style="float: right;" type="submit"> <?php echo get_phrase('update');?></button><br>
          </div>
        <?php echo form_close();?>
<?php endforeach; ?>

<script type="text/javascript">
function readURL1(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#ava_super_modal').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgpre_super_modal").change(function(){
    readURL1(this);
}); 


</script>


<script type="text/javascript">
function readURL2(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#ava_driver_modal').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgpre_driver_modal").change(function(){
    readURL2(this);
}); 
</script>

