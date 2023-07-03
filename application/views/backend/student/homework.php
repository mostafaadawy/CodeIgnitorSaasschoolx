<?php 
$student_id = $this->session->userdata('login_user_id');
$running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description; ?>
<?php 
$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
$query          = 'SELECT * FROM `clients` WHERE `client_subdomain`="'.$subdomain.'"';
$result         = $this->db->query($query);
$forums         = $result->row()->forums;
$accounting     = $result->row()->accounting;
$polls_purchase = $result->row()->polls;
$notifications  = $result->row()->notify;
$messaging      = $result->row()->messaging;
$exams          = $result->row()->exams;
?>
<div class="content-w">
	<div class="os-tabs-w menu-shad">
		<div class="os-tabs-controls">
		  <ul class="nav nav-tabs upper">
			<li class="nav-item">
			  <a class="nav-link active" href="<?php echo base_url();?>student/homework/"><i class="os-icon picons-thin-icon-thin-0014_notebook_paper_todo"></i><span><?php echo get_phrase('homework');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>student/study_material/"><i class="os-icon picons-thin-icon-thin-0009_book_reading_read_manual"></i><span><?php echo get_phrase('study_material');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>student/syllabus/"><i class="os-icon picons-thin-icon-thin-0008_book_reading_read_manual"></i><span><?php echo get_phrase('syllabus');?></span></a>
			</li>
			<?php if($exams==1):?>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>student/online_exams/"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo get_phrase('online_exams');?></span></a>
			</li>
			<?php endif;?>
			<?php if($forums==1):?>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>student/forum/"><i class="os-icon picons-thin-icon-thin-0281_chat_message_discussion_bubble_reply_conversation"></i><span><?php echo get_phrase('forum');?></span></a>
			</li>
			<?php endif;?>
		  </ul>
		</div>
	  </div>
	<div class="content-i">
	<div class="content-box">
	<div class="col-lg-12">
	<div class="element-wrapper">
		<div class="element-box lined-primary shadow">
			<h5 class="form-header"><?php echo get_phrase('homework');?></h5><br>
		  <div class="table-responsive">
			<table id="dataTable1" width="100%" class="table table-lightborder table-lightfont">
			<thead>
				<tr>
					<th><?php echo get_phrase('title');?></th>
					<th><?php echo get_phrase('subject');?></th>
					<th><?php echo get_phrase('teacher');?></th>
					<th><?php echo get_phrase('delivery_date');?></th>
					<th class="text-center"><?php echo get_phrase('details');?></th>
				</tr>
			</thead>
			<tbody>
			<?php
    			$counter = 1;
    			$class_id = $this->db->get_where('enroll' , array('student_id' => $this->session->userdata('login_user_id') , 'year' => $running_year))->row()->class_id;
				$section_id = $this->db->get_where('enroll' , array('student_id' => $this->session->userdata('login_user_id') , 'year' => $running_year))->row()->section_id;
    			$this->db->order_by('homework_id', 'desc');
    			$homeworks = $this->db->get_where('homework', array('class_id' => $class_id , 'section_id' => $section_id))->result_array();
    			foreach ($homeworks as $row):
				      $students = $this->db->get_where('subject', array('subject_id' => $row['subject_id'], 'year' => $running_year))->row()->students;
					  $type     = $this->db->get_where('subject', array('subject_id' => $row['subject_id'], 'year' => $running_year))->row()->type;
					  if(!$type ==1  || strpos($students ,'"'.$student_id.'"' )!= false ):
        	?>
				<tr>
					<td><?php echo $row['title']; ?></td>
					<td><a class="btn nc btn-rounded btn-sm btn-secondary" style="color:white"><?php echo $this->crud_model->get_type_name_by_id('subject',$row['subject_id']);?></a></td>
					<td><?php if($row['user'] == "admin"):?><?php echo $this->db->get_where('admin' , array('admin_id'=> $row['uploader_id']))->row()->name; ?><?php endif;?><?php if($row['user'] == "teacher"):?><?php echo $this->db->get_where('teacher' , array('teacher_id'=> $row['uploader_id']))->row()->name; ?><?php endif;?></td>
					<td><a class="btn nc btn-rounded btn-sm btn-danger" style="color:white"><?php echo $row['date_end'];?></a></td>
					<td class="row-actions">
						<a class="btn btn-rounded btn-sm btn-primary" style="color:white" href="<?php echo base_url();?>student/homeworkroom/<?php echo $row['homework_code']; ?>"><i class="picons-thin-icon-thin-0043_eye_visibility_show_visible"></i> <?php echo get_phrase('view');?></a>
					</td>
				</tr>
			<?php endif; endforeach;?>
			</tbody>
			</table>
		  </div>
		</div>
	  </div>
	</div>
	</div>  
</div>
</div>