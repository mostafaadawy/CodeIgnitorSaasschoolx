<?php $run_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description; 
$teacher_id = $this->session->userdata('login_user_id');
$class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name; 
$section_name = $this->db->get_where('section' , array('class_id' => $class_id , 'section_id'=>$section_id))->row()->name; 
?>
<div class="content-w">
	<div class="os-tabs-w menu-shad">
        <div class="os-tabs-controls">
          <ul class="nav nav-tabs upper">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url();?>teacher/upload_marks/"><i class="os-icon picons-thin-icon-thin-0007_book_reading_read_bookmark"></i><span><?php echo get_phrase('upload_marks');?></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo base_url();?>teacher/tab_sheet/"><i class="os-icon picons-thin-icon-thin-0197_layout_grid_view"></i><span><?php echo get_phrase('tabulation_sheet');?></span></a>
            </li>
          </ul>
        </div>
      </div>

  <div class="content-i">
	     <div class="content-box">
	       <div class="element-wrapper">
            <?php echo form_open(base_url() . 'teacher/tab_sheet', array('class' => 'form m-b'));?>
	           <div class="row">
				  
				  <div class="col-sm-3">
                  <div class="form-group">
                    <label class="gi" for=""><?php echo get_phrase('class');?>:</label>
                    <select name="class_id" class="form-control" onchange="get_class_sections(this.value);" required="">
						<option value=""><?php echo get_phrase('select');?></option>
						<?php
							$classes = $this->db->get('class')->result_array();
							foreach($classes as $row):
						?>
							<option value="<?php echo $row['class_id'];?>" <?php if($class_id == $row['class_id']) echo 'selected';?>><?php echo $row['name'];?></option>
						<?php endforeach;?>
					</select>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-group">
                    <label class="gi" for=""><?php echo get_phrase('section');?>:</label>
                    	<select class="form-control" required="" id="sections_selector_holder" name="section_id">
							<option value=""><?php echo get_phrase('select');?></option>
						</select>
                  </div>
                </div>
				  
  	              <div class="col-sm-2">
	                  <div class="form-group">
	                   <label class="gi" for=""><?php echo get_phrase('semester');?>:</label>
	                    <select name="exam_id" class="form-control">
                        <option value=""><?php echo get_phrase('select');?></option>
                        <?php 
                            $exams = $this->db->get_where('exam' , array('year' => $run_year))->result_array();
                            foreach($exams as $row):
                        ?>
                            <option value="<?php echo $row['exam_id'];?>"
                              <?php if ($exam_id == $row['exam_id']) echo 'selected';?>>
                                <?php echo $row['name'];?>
                            </option>
                        <?php endforeach; ?>
                    </select>
	               </div>
                 <input type="hidden" name="operation" value="selection">
            </div>
			
			
			<div class="col-sm-2">
	                  <div class="form-group">
	                   <label class="gi" for=""><?php echo get_phrase('running_year');?>:</label>
	                   <select class="form-control" name="running_year" required="">
					<?php $run_year = $this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;?>
                          <option value=""><?php echo get_phrase('select');?></option>
                          <?php for($i = 0; $i < 10; $i++):?>
                              <option value="<?php echo (2018+$i);?>-<?php echo (2018+$i+1);?>"
                                <?php if($run_year == (2018+$i).'-'.(2018+$i+1)) echo 'selected';?>>
                                  <?php echo (2018+$i);?>-<?php echo (2018+$i+1);?>
                              </option>
                          <?php endfor;?>
				  </select>
	               </div>
                 <input type="hidden" name="operation" value="selection">
            </div>
			

			
	           <div class="col-sm-2">
	             <div class="form-group">
  	             <button type="submit" class="btn btn-primary btn-rounded btn-upper" style="margin-top:20px"><span><?php echo get_phrase('generate');?></span></button>
                </div>
              </div>
	         </div>
	       <?php echo form_close();?>
         <?php if ($class_id != '' && $exam_id != '' && $section_id != ''):?>
	       <div class="element-box">
            <h5 class="form-header"><?php echo get_phrase('tabulation_sheet');?></h5><br><hr>
			<h6 class="form-header"><?php echo get_phrase('class').':'.$class_name?> <br><hr> <h6 class="form-header"><?php echo get_phrase(section).':'.$section_name;?></h6>

              <div class="table-responsive">
                  <table class="table table-lightborder">
                      <thead>
                          <tr>
                              <th><?php echo get_phrase('student');?></th>
                              <?php 
                                $subjects = $this->db->get_where('subject' , array('class_id' => $class_id , 'teacher_id' => $teacher_id , 'section_id' => $section_id , 'year' => $running_year))->result_array();
                                foreach($subjects as $row):
                              ?>
                                <td><?php echo $row['name'];?></td>
                              <?php endforeach;?>
                              <th class="prom"><?php echo get_phrase('average');?></th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php
                          $students = $this->db->get_where('enroll' , array('class_id' => $class_id , 'section_id' => $section_id  , 'year' => $running_year))->result_array();
                          foreach($students as $row):
                        ?>
                        <tr>
                            <td nowrap><img alt="" src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" width="25px" style="border-radius: 10px;margin-right:5px;"> <?php echo $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;?></td>
                            <?php
                                    $total_marks = 0;  foreach($subjects as $row2): ?>
                                    <td style="text-align: center;">
                                    <?php $marks =  $this->db->get_where('mark' , array('class_id' => $class_id ,'exam_id' => $exam_id , 
                                      'subject_id' => $row2['subject_id'] , 'student_id' => $row['student_id'],'year' => $running_year));
                                      if($marks->num_rows() > 0) 
                                      {
                                        $obtained_marks = $marks->row()->labtotal;
                                        echo $obtained_marks;
                                        $total_marks += $obtained_marks;
                                      }
                                    ?>
                                </td>
                            <?php endforeach;?>
                            <td class="average"><?php 
                                $this->db->where('class_id' , $class_id);
								$this->db->where('section_id' , $section_id);
								$this->db->where('teacher_id' , $teacher_id);
                                $this->db->where('year' , $running_year);
                                $this->db->from('subject');
                                $total_subjects = $this->db->count_all_results();
                                echo ($total_marks / $total_subjects); echo "%";
                              ?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                  </table>
		              <div class="form-buttons-w text-center">
		                  <a href="<?php echo base_url();?>teacher/tab_sheet_print/<?php echo $class_id;?>/<?php echo $exam_id;?>"><button class="btn btn-success btn-rounded" type="submit"><i class="picons-thin-icon-thin-0333_printer"></i>  <?php echo get_phrase('print');?></button></a>
		              </div>
              </div>
        </div>
    <?php endif;?>
		  </div>
		</div>
		</div>
	</div>
	
	 <script type="text/javascript">
    function get_class_sections(class_id) 
    {
        $.ajax({
            url: '<?php echo base_url();?>teacher/get_class_section/' + class_id ,
            success: function(response)
            {
                jQuery('#sections_selector_holder').html(response);
            }
        });
    }
</script>