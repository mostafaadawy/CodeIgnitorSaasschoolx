<?php 
    $student_id = $this->session->userdata('login_user_id');
	$running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description; 
	$class_id = $this->db->get_where('enroll', array('student_id' => $this->session->userdata('login_user_id'), 'year' => $running_year))->row()->class_id;
	$section_id = $this->db->get_where('enroll' , array('student_id' => $this->session->userdata('login_user_id'),'class_id' => $class_id,'year' => $running_year))->row()->section_id;
?>
<?php $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?>
<div class="content-w">
 <div class="os-tabs-w menu-shad">
        <div class="os-tabs-controls">
          <ul class="nav nav-tabs upper">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url();?>student/class_routine/"><i class="os-icon picons-thin-icon-thin-0024_calendar_month_day_planner_events"></i><span><?php echo get_phrase('class_routine');?></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo base_url();?>student/exam_routine/"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo get_phrase('exam_routine');?></span></a>
            </li>
          </ul>
        </div>
      </div>
  <div class="content-i">
    <div class="content-box">
          <div class="element-wrapper">
            <div class="element-box table-responsive lined-primary shadow">
			<div class="row m-b">
				<div style="display:inline-block">
				<img style="max-height:80px;margin:0px 10px 20px 20px" src="<?php echo base_url().$subdomain;?>uploads/logo.png" alt=""/>		
				</div>
				<div style="padding-left:20px;display:inline-block;">
				<h5><?php echo get_phrase('exam_routine');?></h5>
				<p><?php echo $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;?><br><?php echo $this->db->get_where('section' , array('section_id' => $section_id))->row()->name;?></p>
				</div>
			</div>
			<table class="table table-bordered table-schedule table-hover" cellpadding="0" cellspacing="0" width="100%">
			<?php 
                $days = $this->db->get_where('academic_settings', array('type' => 'routine'))->row()->description; 
        		if($days == 2) { $nday = 6;}else{$nday = 7;}
        		for($d=$days; $d <= $nday; $d++):
                if($d==1)$day = 'Saturday';
				else if($d==2) $day ='Sunday';
				else if($d==3) $day ='Monday';
				else if($d==4) $day = 'Tuesday';
				else if($d==5) $day ='Wednesday';
				else if($d==6) $day ='Thursday';
				else if($d==7) $day ='Friday';
			?>
				<tr>
				<table class="table table-schedule table-hover" cellpadding="0" cellspacing="0">
					<td width="120" height="90" style="text-align: center;"><strong><?php echo ucwords($day);?></strong></td>
					 <?php
                        $this->db->order_by("time_start", "asc");
                        $this->db->where('day' , $day);
                        $this->db->where('class_id' , $class_id);
                        $this->db->where('section_id' , $section_id);
                        $this->db->where('year' , $running_year);
                        $routines   =   $this->db->get('horarios_examenes')->result_array();
                        foreach($routines as $row2):
						$students = $this->db->get_where('subject', array('subject_id' => $row2['subject_id'], 'year' => $running_year))->row()->students;
									$type     = $this->db->get_where('subject', array('subject_id' => $row2['subject_id'], 'year' => $running_year))->row()->type;
									if(!$type ==1  || strpos($students ,'"'.$student_id.'"' )!= false ):
                    ?>
					<td style="text-align:center">
					<strong><?php echo $row2['fecha'];?></strong><br>
					<?php
                        if ($row2['time_start_min'] == 0 && $row2['time_end_min'] == 0) 
                        echo '('.$row2['time_start'].'-'.$row2['time_end'].')';
                        if ($row2['time_start_min'] != 0 || $row2['time_end_min'] != 0)
                        echo '('.$row2['time_start'].':'.$row2['time_start_min'].' - '.$row2['time_end'].':'.$row2['time_end_min'].')';
                    ?>
                    <br><b><?php echo $this->crud_model->get_subject_name_by_id($row2['subject_id']);?></b><br><small><?php echo $this->db->get_where('teacher', array('teacher_id' => $row2['teacher_id']))->row()->name;?></small>
                    </td>
                    <?php endif; endforeach;?>
				</tr>
				<?php endfor;?>  
				</table>
            </div>
          </div>
        </div>
      </div>
    </div>