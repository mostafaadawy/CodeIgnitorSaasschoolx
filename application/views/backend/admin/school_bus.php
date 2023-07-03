<?php $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?>
<div class="content-w">
		  <div class="os-tabs-w menu-shad">
			<div class="os-tabs-controls">
			  <ul class="nav nav-tabs upper">
				<li class="nav-item">
				  <a class="nav-link active" data-toggle="tab" href="#bus"><i class="os-icon picons-thin-icon-thin-0470_bus_transport"></i><span><?php echo get_phrase('school_bus');?></span></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link" data-toggle="tab" href="#new"><i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i><span><?php echo get_phrase('new');?></span></a>
				</li>
				<li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#import"><?php echo get_phrase('excel_import');?></a>
            </li>
			  </ul>
			</div>
		  </div>
  <div class="content-i">
	<div class="content-box">
	<div class="tab-content">
	
		<div class="tab-pane active" id="bus">
		<div class="element-box lined-primary shadow">
			<h5 class="form-header"><?php echo get_phrase('school_bus');?></h5><br>
		<div class="row">
		 <?php $bus = $this->db->get('transport')->result_array();
				foreach($bus as $buss):
				?>
								 <div class="col-sm-4 m-b">
							<div class="pipeline-item">
							  <div class="pi-foot">
								<a class="extra-info" href="#"><img alt="" src="<?php echo base_url().$subdomain;?>uploads/logo.png" width="10%" style="margin-right:5px"><span><?php echo $this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;?></span></a>
							  </div>
							  <div class="pi-controls">
								<div class="pi-settings os-dropdown-trigger">
								  <i class="os-icon picons-thin-icon-thin-0069a_menu_hambuger"></i>
								  <div class="os-dropdown">
									<div class="icon-w">
									  <i class="os-icon picons-thin-icon-thin-0069a_menu_hambuger"></i>
									</div>
									<ul>  
									  <li>
										<a class="success" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_bus/<?php echo $buss['transport_id'];?>');"><i class="picons-thin-icon-thin-0729_student_degree_science_university_school_graduate"></i></a>
									  </li>
									  <li>
										<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_transport/<?php echo $buss['transport_id'];?>');"><i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i></a>
									  </li>
									  <li>
										<a onClick="return confirm('<?php echo get_phrase('confirm_delete');?>')" class="danger" href="<?php echo base_url();?>admin/school_bus/delete/<?php echo $buss['transport_id'];?>"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i></a>
									  </li>
									</ul>
								  </div>
								</div>
							  </div>
							  
							  <div class="pi-body bglogo">
							    <div class="avatar">
								  <img alt="" src="<?php echo base_url().'uploads/school_bus.png';?>">
								</div>
								<div class="pi-info">
								  <div class="h6 pi-name"><?php echo get_phrase('name');?>: <?php echo $buss['route_name'];?><br>
								  </div>
								   <div class="h6 pi-name"><?php echo  get_phrase('route');?>: <?php echo $buss['route'];?><br><br>
								  </div>
								   <div class="h6 pi-name"><?php echo  get_phrase('bus_id');?>: <a class="btn nc btn-rounded btn-sm btn-primary" style="color:white"><?php echo $buss['number_of_vehicle'];?></a><br><br>
								  </div>
								  <div class="h6 pi-name"><?php echo  get_phrase('price');?>: <a class="btn nc btn-rounded btn-sm btn-success" style="color:white"><span>EGP</span><?php echo $buss['route_fare'];?></a><br><br>
								  </div>
								</div>
							  </div>
							  
							  <div class="pi-body bglogo">
								<div class="avatar">
								  <img alt="" src="<?php echo base_url().$subdomain.'uploads/supervisor_image/' .$buss['supervisor_name']. '.jpg';?>">
								</div>
								<div class="pi-info">
								  <div class="h6 pi-name"><?php echo get_phrase('supervisor');?>: <?php echo $buss['supervisor_name'];?><br>
									<small> <?php echo $buss['supervisor_phone'];?></small>
								  </div>
								</div>
							  </div>
							  
							  <div class="pi-body bglogo">
								<div class="avatar">
								  <img alt="" src="<?php echo base_url().$subdomain.'uploads/driver_image/' .$buss['driver_name']. '.jpg';?>">
								</div>
								<div class="pi-info">
								  <div class="h6 pi-name"><?php echo get_phrase('driver');?>: <?php echo $buss['driver_name'];?><br>
									<small> <?php echo $buss['driver_phone'];?></small>
								  </div>
								</div>
							  </div>
							  
							</div>
							</div>
							<?php endforeach;?>
		</div>
		</div>
		</div>
		
		<div class="tab-pane" id="new">
		<div class="col-lg-12">
		<div class="element-wrapper">
		  <div class="element-box lined-primary shadow">
		<?php echo form_open(base_url() . 'admin/school_bus/create/' , array('enctype' => 'multipart/form-data'));?>
		  <h5 class="form-header"><?php echo get_phrase('new');?></h5><br>
		  <div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('name');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0470_bus_transport"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('name');?>" name="route_name" required type="text">
				</div>
			  </div>
			</div>
			<div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('route');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0545_map_travel_distance_directions"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('route');?>" name="route" type="text">
				</div>
			  </div>
			</div>
			<div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('bus_id');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0487_van_truck_transport_vehicle"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('bus_id');?>" name="number_of_vehicle" type="text">
				</div>
			  </div>
			</div>
			
			
			<div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('supervisor');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0701_user_profile_avatar_man_male"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('supervisor');?>" name="supervisor_name" type="text">
				</div>
			  </div>
			</div>
			
			<div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('supervisor_phone');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0289_mobile_phone_call_ringing_nfc"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('supervisor_phone');?>" name="supervisor_phone" type="text">
				</div>
			  </div>
			</div>
			
			 <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('supervisor_photo');?></label>
            <div class="col-sm-9 profile-side-user">
          <button type="button" class="avatar-preview avatar-preview-128">
				<img id="ava" src="<?php echo base_url();?>style/cms/img/avatar-1-256.png" alt=""/>
				<span class="update">
					<i class="font-icon picons-thin-icon-thin-0617_picture_image_photo"></i>
					<?php echo get_phrase('upload');?>
				</span>
				<input name="supervisorfile" accept="image/x-png,image/gif,image/jpeg" id="imgpre_super" type="file"/>
			</button></div>
            </div>
			
			
			<div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('driver');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0701_user_profile_avatar_man_male"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('driver');?>" name="driver_name" type="text">
				</div>
			  </div>
			</div>
			
			<div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('driver_phone');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0289_mobile_phone_call_ringing_nfc"></i>
				  </div>
				<input class="form-control" placeholder="<?php echo get_phrase('driver_phone');?>" name="driver_phone" type="text">
				</div>
			  </div>
			</div>
			
			 <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('driver_photo');?></label>
            <div class="col-sm-9 profile-side-user">
          <button type="button" class="avatar-preview avatar-preview-128">
				<img id="ava_driver" src="<?php echo base_url();?>style/cms/img/avatar-1-256.png" alt=""/>
				<span class="update">
					<i class="font-icon picons-thin-icon-thin-0617_picture_image_photo"></i>
					<?php echo get_phrase('upload');?>
				</span>
				<input name="driverfile" accept="image/x-png,image/gif,image/jpeg" id="imgpre_driver" type="file"/>
			</button></div>
            </div>
			
			
			
			
			
			
			
			<div class="form-group row">
			  <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('price');?></label>
			  <div class="col-sm-9">
			  <div class="input-group">
				<div class="input-group-addon">
					<i class="picons-thin-icon-thin-0406_money_dollar_euro_currency_exchange_cash"></i>
				  </div>
				<input class="form-control" placeholder="EGP2000" type="text" name="route_fare">
				</div>
			  </div>
			</div>
		  <div class="form-buttons-w">
			<button class="btn btn-primary btn-rounded" type="submit"> <?php echo get_phrase('save');?></button>
		  </div>
		<?php echo form_close();?>
		</div>
		</div>
		</div>
		</div>
		
		
		
	<div class="tab-pane" id="import">
      <div class="element-box lined-primary shadow">
      <div class="b-b"><h5 class="form-header">
       <?php echo get_phrase('excel_import');?>
      </h5>
	  <h6 class="form-header">
       <?php echo 'All fields of the Excel Sheet are required. Blank fields and/or duplicate names are not allowed!!';?>
      </h6>
        <div class="text-right" style="margin-top:-25px;margin-bottom:25px;"><a href="<?php echo base_url();?>uploads/import/bus_excel.xlsx"><button class="btn btn-primary btn-rounded btn-sm"><i class="picons-thin-icon-thin-0105_download_clipboard_box"></i>  <?php echo get_phrase('download_model');?></button></a></div>
		</div>
		<div style="margin:30px 10px;">
      <?php echo form_open(base_url() . 'admin/bus_bulk/excel/' , array('class' => 'form-inline', 'enctype' => 'multipart/form-data'));?>
		<div class="form-group col-sm-4">
            <label class="col-form-label" for=""> <?php echo get_phrase('file');?></label>
              <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0042_attachment"></i>
               </div>
              <input class="form-control" placeholder="<?php echo get_phrase('file');?>" required name="excel" type="file">
              </div>
            </div>
         </div>
      <div class="form-buttons-w">
          <button class="btn btn-primary btn-rounded" type="submit"><?php echo get_phrase('import');?></button>
      </div>
      <?php echo form_close();?>
      </div>
      </div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	</div>
	</div>
	</div>
</div>
