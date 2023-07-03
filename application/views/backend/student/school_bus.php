<div class="content-w">
		  <div class="os-tabs-w menu-shad">
			<div class="os-tabs-controls">
			  <ul class="nav nav-tabs upper">
				<li class="nav-item">
				  <a class="nav-link active" data-toggle="tab" href="#bus"><i class="os-icon picons-thin-icon-thin-0470_bus_transport"></i><span><?php echo get_phrase('school_bus');?></span></a>
				</li>
			  </ul>
			</div>
		  </div>
  <div class="content-i">
	<div class="content-box">
	<div class="tab-content">
	
		<div class="tab-pane active" id="bus">
		
		<div class="col-lg-12">
		<div class="element-wrapper">
			<div class="element-box lined-primary shadow">
			<h5 class="form-header"><?php echo get_phrase('school_bus');?></h5><br>
			<div class="row">
		 <?php
		        $student_id = $this->session->userdata('login_user_id');
				$bus_id     = $this->db->get_where('student', array('student_id' => $student_id))->row()->transport_id;
                $bus = $this->db->get('transport', array('transport_id' => $bus_id))->result_array();
				foreach($bus as $buss):
				?>
								 <div class="col-sm-4 m-b">
							<div class="pipeline-item">
							  <div class="pi-foot">
								<a class="extra-info" href="#"><img alt="" src="<?php echo base_url().$subdomain;?>uploads/logo.png" width="10%" style="margin-right:5px"><span><?php echo $this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;?></span></a>
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
		  </div>
		</div>
		
	</div>
	</div>
	</div>
</div>
