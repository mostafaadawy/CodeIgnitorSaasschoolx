<?php $running_year = $this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;
$teacher_id = $this->session->userdata('login_user_id');
?>
<div class="content-w">
		<div class="os-tabs-w menu-shad">
			<div class="os-tabs-controls">
			  <ul class="nav nav-tabs upper">
				<li class="nav-item">
				  <a class="nav-link active"><i class="os-icon picons-thin-icon-thin-0714_identity_card_photo_user_profile"></i><span><?php echo get_phrase('students_area');?></span></a>
				</li>
			  </ul>
			</div>
		  </div>

 <div class="content-i">	
	<div class="content-box">
	  <?php echo form_open(base_url() . 'teacher/students_area/', array('class' => 'form m-b'));?>
			  <div class="row">
				<div class="col-sm-4">
				  <div class="form-group">
					<label class="gi" for=""><?php echo get_phrase('class');?>:</label>
					<select class="form-control" onchange="submit();" name="class_id">
					<option value=""><?php echo get_phrase('select');?></option>
					<?php $cl = $this->db->get('class')->result_array();
                     foreach($cl as $row):
						 $subject = $this->db->get_where('subject' , array('class_id' => $row['class_id'] , 'teacher_id'=> $teacher_id))->result_array();
						 $sections = $this->db->get_where('section' , array('class_id' => $row['class_id'] , 'teacher_id'=> $teacher_id))->result_array();
						 if ($teacher_id == $row['teacher_id'] /*|| ($subject->num_rows() > 0) || ($sections->num_rows() > 0) */):
						?>
						  <option value="<?php echo $row['class_id'];?>" <?php if($class_id == $row['class_id']) echo 'selected';?>><?php echo $row['name'];?></option>
						 <?php endif;?>
                  <?php endforeach;?>
					</select>
				  </div>
				</div>
				
				
				
			  </div>
	<?php echo form_close();?>
	
	<div class="os-tabs-w">
		<div class="os-tabs-controls">
		  <ul class="nav nav-tabs upper">
			<li class="nav-item">
			  <a class="nav-link active" data-toggle="tab" href="#all"><?php echo get_phrase('all');?></a>
			</li>
			<?php $query = $this->db->get_where('section' , array('class_id' => $class_id )); 
               if ($query->num_rows() > 0):
               $secs = $query->result_array();
			   $subjs = $this->db->get_where('subject' , array('class_id' => $class_id , 'teacher_id'=> $teacher_id))->result_array();
               foreach ($secs as $rows):
			   //if ($teacher_id == $rows['teacher_id']  || $subjs->num_rows() > 0):
			   ?>
			<li class="nav-item">
			  <a class="nav-link" data-toggle="tab" href="#tab<?php echo $rows['section_id'];?>"><?php echo get_phrase('section');?> <?php echo $rows['name'];?></a>
			</li>
			 <?php //endif;?>
			<?php endforeach;?>
			<?php endif;?>
		  </ul>
		</div>
	  </div>
	  
	
	<div class="tab-content">
	
	<div class="tab-pane active" id="all">
		<div class="row">
		<?php $students = $this->db->get_where('enroll' , array('class_id' => $class_id , 'year' => $running_year))->result_array();
               foreach($students as $row):?>
		<div class="col-sm-4 m-b">
		<div class="pipeline-item">
		  <div class="pi-foot">
			<a class="extra-info" href="#"><img alt="" src="<?php echo base_url();?>style/cms/img/school1.png" width="10%" style="margin-right:5px"><span><?php echo $this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;?></span></a>
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
					<a href="<?php echo base_url();?>teacher/view_marks/<?php echo $row['student_id'];?>"><i class="os-icon picons-thin-icon-thin-0006_book_writing_reading_read_manual"></i><span><?php echo get_phrase('marks');?></span></a>
				  </li>
				</ul>
				
				<ul>
				  <li>
					<a href="<?php echo base_url();?>teacher/view_attendance/<?php echo $row['student_id'];?>"><i class="os-icon picons-thin-icon-thin-0023_calendar_month_day_planner_events"></i><span><?php echo get_phrase('attendance');?></span></a>
				  </li>
				</ul>
				
				<ul>
				  <li>
					<a href="<?php echo base_url();?>teacher/view_behavior/<?php echo $row['student_id'];?>"><i class="os-icon picons-thin-icon-thin-0389_gavel_hammer_law_judge_court"></i><span><?php echo get_phrase('behavior');?></span></a>
				  </li>
				</ul>
				
			  </div>
			</div>
		  </div>
		  <div class="pi-body">
			<div class="avatar">
			  <img alt="" src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>">
			</div>
			<div class="pi-info">
			  <div class="h6 pi-name"><?php echo $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;?><br>
				<small><?php echo get_phrase('roll');?>: <?php echo $this->db->get_where('enroll' , array('student_id' => $row['student_id']))->row()->roll;?></small>
			  </div>
			  <div class="pi-sub">
				<?php echo $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;?><br>
				<?php echo get_phrase('section');?>: <?php echo $this->db->get_where('section' , array('section_id' => $row['section_id']))->row()->name;?>
			  </div>
			</div>
		  </div>
		</div>
		</div>
	<?php endforeach;?>
	</div></div>
	  <?php $query = $this->db->get_where('section' , array('class_id' => $class_id));
           if ($query->num_rows() > 0):
           $sections = $query->result_array();
        foreach ($sections as $row): ?>
	<div class="tab-pane" id="tab<?php echo $row['section_id'];?>">
	<div class="row">
		<?php $students = $this->db->get_where('enroll' , array('class_id'=> $class_id , 'section_id' => $row['section_id'] , 'year' => $running_year))->result_array();
                foreach($students as $row2):?>
		<div class="col-sm-4 m-b">
		<div class="pipeline-item">
		  <div class="pi-foot">
			<a class="extra-info" href="#"><img alt="" src="<?php echo base_url();?>style/cms/img/school1.png" width="10%" style="margin-right:5px"><span><?php echo $this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;?></span></a>
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
					<a href="<?php echo base_url();?>teacher/view_marks/<?php echo $row2['student_id'];?>"><i class="os-icon picons-thin-icon-thin-0006_book_writing_reading_read_manual"></i><span><?php echo get_phrase('marks');?></span></a>
				  </li>
				</ul>
			  </div>
			</div>
		  </div>
		  <div class="pi-body">
			<div class="avatar">
			  <img alt="" src="<?php echo $this->crud_model->get_image_url('student',$row2['student_id']);?>">
			</div>
			<div class="pi-info">
			  <div class="h6 pi-name"><?php echo $this->db->get_where('student' , array('student_id' => $row2['student_id']))->row()->name;?><br>
				<small><?php echo get_phrase('roll');?>: <?php echo $this->db->get_where('enroll' , array('student_id' => $row2['student_id']))->row()->roll;?></small>
			  </div>
			  <div class="pi-sub">
				<?php echo $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;?><br>
				<?php echo get_phrase('section');?>: <?php echo $this->db->get_where('section' , array('section_id' => $row2['section_id']))->row()->name;?>
			  </div>
			</div>
		  </div>
		</div>
		</div>
	<?php endforeach;?>
	</div>
	</div>
	 <?php endforeach;?>
        <?php endif;?>
	
	</div>
  </div>
</div>
</div>