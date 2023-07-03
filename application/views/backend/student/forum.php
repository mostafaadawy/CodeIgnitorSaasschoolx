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
<?php if($forums == 1):?>
<div class="content-w">
	<div class="os-tabs-w menu-shad">
		<div class="os-tabs-controls">
		  <ul class="nav nav-tabs upper">
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>student/homework/"><i class="os-icon picons-thin-icon-thin-0014_notebook_paper_todo"></i><span><?php echo get_phrase('homework');?></span></a>
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
			<li class="nav-item">
			  <a class="nav-link active" href="<?php echo base_url();?>student/forum/"><i class="os-icon picons-thin-icon-thin-0281_chat_message_discussion_bubble_reply_conversation"></i><span><?php echo get_phrase('forum');?></span></a>
			</li>
		  </ul>
		</div>
	  </div>
	<div class="content-i">
	<div class="content-box">
	<div class="col-lg-12">
	<div class="element-wrapper">
		<div class="element-box lined-primary shadow">
			<h5 class="form-header"><?php echo get_phrase('forum');?></h5><br>
		  <div class="table-responsive">
			<table id="dataTable1" width="100%" class="table table-lightborder table-lightfont">
			<thead>
				<tr>
					<th><?php echo get_phrase('title');?></th>
					<th><?php echo get_phrase('subject');?></th>
					<th><?php echo get_phrase('date');?></th>
					<th class="text-center"><?php echo get_phrase('details');?></th>
				</tr>
			</thead>
			<tbody>
			<?php
	    		$class_id = $this->db->get_where('enroll' , array('student_id' => $this->session->userdata('login_user_id') , 'year' => $running_year))->row()->class_id;
				$section_id = $this->db->get_where('enroll' , array('student_id' => $this->session->userdata('login_user_id') , 'year' => $running_year))->row()->section_id;
    			$this->db->order_by('post_id', 'desc');
    			$post = $this->db->get('forum')->result_array();
    			foreach ($post as $row):
				$students = $this->db->get_where('subject', array('subject_id' => $row['subject_id'], 'year' => $running_year))->row()->students;
			    $type     = $this->db->get_where('subject', array('subject_id' => $row['subject_id'], 'year' => $running_year))->row()->type;
				if(!$type ==1  || strpos($students ,'"'.$student_id.'"' )!= false ):
    		?>
    			<?php  if ($class_id == $row['class_id'] && $section_id == $row['section_id']) { ?>
				<tr>
					<td><?php echo $row['title']; ?></td>
					<td><?php echo $this->crud_model->get_type_name_by_id('subject',$row['subject_id']);?></td>
					<td><a class="btn nc btn-rounded btn-sm btn-success" style="color:white"><?php echo date("d M, Y" , $row['timestamp']);?></a></td>
					<td class="row-actions">
						<a class="btn btn-rounded btn-sm btn-primary" style="color:white" href="<?php echo base_url();?>student/forumroom/<?php echo $row['post_code']; ?>"><i class="picons-thin-icon-thin-0043_eye_visibility_show_visible"></i> <?php echo get_phrase('view');?></a>
					</td>
				</tr>
				    <?php } ?>
				<?php endif; endforeach; ?>		
			</tbody>
			</table>
		  </div>
		</div>
	  </div>
	</div>
	</div>  
</div>
</div>
<?php endif;?>

<?php if(!$forums==1):?>
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
<body class="auth-wrapper login" style="background: url('uploads/bglogin.jpg');background-size: cover;background-repeat: no-repeat;">
      <div class="auth-box-w wider">
        <div class="logo-wy">
          <a href="<?php echo base_url();?>"><img alt="" src="uploads/logo-color.png" width="35%"></a>
        </div>
		
              <center><h2>This Module is currently disabled. Please contact SchoolX to purchase this module.</h2><center>
              <center><div class="loader"></div><center>
		 <div class="form-group">
		 </div>
      </div>
 </body>		 
<?php endif;?>