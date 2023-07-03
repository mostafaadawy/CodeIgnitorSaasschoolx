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
<?php if($exams==1):?>
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
			<li class="nav-item">
			  <a class="nav-link active" href="<?php echo base_url();?>student/online_exams/"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo get_phrase('online_exams');?></span></a>
			</li>
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
			<h5 class="form-header"><?php echo get_phrase('online_exams');?></h5><br>
		  <div class="table-responsive">
			<table id="dataTable1" width="100%" class="table table-lightborder table-lightfont">
			<thead>
				<tr>
					<th><?php echo get_phrase('title');?></th>
					<th><?php echo get_phrase('subject');?></th>
					<th><?php echo get_phrase('teacher');?></th>
					<th><?php echo get_phrase('start_date');?></th>
					<th><?php echo get_phrase('limit_date');?></th>
					<th class="text-center"><?php echo get_phrase('options');?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
				$count    = 1;
				$class_id = $this->db->get_where('enroll' , array('student_id' => $this->session->userdata('login_user_id') , 'year' => $running_year))->row()->class_id;
				$section_id = $this->db->get_where('enroll' , array('student_id' => $this->session->userdata('login_user_id') , 'year' => $running_year))->row()->section_id;
				$exams = $this->db->get_where('exams' , array('class_id' => $class_id , 'section_id' => $section_id)); 
				$exam = $exams->result_array();
				foreach ($exam as $row):
				$students = $this->db->get_where('subject', array('subject_id' => $row['subject_id'], 'year' => $running_year))->row()->students;
			    $type     = $this->db->get_where('subject', array('subject_id' => $row['subject_id'], 'year' => $running_year))->row()->type;
					if(!$type ==1  || strpos($students ,'"'.$student_id.'"' )!= false ):
				$this->db->where('exam_code', $row['exam_code']);
        		$exam_ques = $this->db->get('questions');       
        		$query = $exam_ques->num_rows();
			?>
			<?php 
			//$dbstart = $row['availablefrom'].' '.$row['clock_start'];
			$dbstart = implode('', array_reverse(explode('/', $row['availablefrom']))) .implode('',explode(':',$row['clock_start']));
            //$dbend = $row['availableto'].' '.$row['clock_end'];
			$dbend   = implode('', array_reverse(explode('/', $row['availableto']))) .implode('',explode(':',$row['clock_end']));
			$todaydate_space = explode(' ', date('d/m/Y H:i'));
			$todaydate = implode('', array_reverse(explode('/', $todaydate_space[0]))) .implode('',explode(':',$todaydate_space[1]));
			?>
				<tr>
					<td><?php echo $row['title'];?></td>
					<td><?php echo $this->db->get_where('subject' , array('subject_id'=> $row['subject_id']))->row()->name; ?></td>
					<td><?php if($row['type'] == "admin"):?><?php echo $this->db->get_where('admin' , array('admin_id'=> $row['teacher_id']))->row()->name; ?><?php endif;?><?php if($row['type'] == "teacher"):?><?php echo $this->db->get_where('teacher' , array('teacher_id'=> $row['teacher_id']))->row()->name; ?><?php endif;?></td>
					<td><a class="btn btn-rounded btn-sm btn-success" style="color:white"><?php echo $row['availablefrom'];?> <?php echo $row['clock_start'];?></a></td>
					<td><a class="btn btn-rounded btn-sm btn-danger" style="color:white"><?php echo $row['availableto'];?> <?php echo $row['clock_end'];?></a></td>
					<td class="text-center">
					<?php if($todaydate < $dbstart && $this->db->get_where('student_question',array('exam_code'=>$row['exam_code'],'student_id'=>$this->session->userdata('login_user_id')))->row()->answered != 'answered'):?>
						<a class="btn nc btn-rounded btn-sm btn-warning" style="color:white"><?php echo 'Not Started';?></a>
					<?php endif;?>
					<?php if($todaydate >= $dbend &&  $this->db->get_where('student_question',array('exam_code'=>$row['exam_code'],'student_id'=>$this->session->userdata('login_user_id')))->row()->answered != 'answered'):?>
						<a class="btn nc btn-rounded btn-sm btn-danger" style="color:white"><?php echo 'Deadline Passed';?></a>
					<?php endif;?>
					<?php if($this->db->get_where('student_question',array('exam_code'=>$row['exam_code'],'student_id'=>$this->session->userdata('login_user_id')))->row()->answered != 'answered' && $query > 0 && $todaydate >= $dbstart && $todaydate < $dbend):?><a class="btn btn-rounded btn-sm btn-success" href="<?php echo base_url();?>student/examroom/<?php echo $row['exam_code'];?>"><?php echo 'Take Exam';?></a>
					<?php endif;?>
					<?php if($query <= 0 && $todaydate <  $dbend && $todaydate >= $dbstart):?>
						<a class="btn nc btn-rounded btn-sm btn-info" style="color:white"><?php echo get_phrase('no_questions');?></a>
					<?php endif;?>
					<?php if($this->db->get_where('student_question',array('exam_code'=>$row['exam_code'],'student_id' => $this->session->userdata('login_user_id')))->row()->answered == 'answered'):?>
						<a class="btn btn-rounded btn-sm btn-primary" href="<?php echo base_url();?>student/exam_results/<?php echo $row['exam_code'];?>" style="color:white"><?php echo get_phrase('view_results');?></a>
					<?php endif;?>
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
<?php endif;?>
<?php if(!$exams==1):?>

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
