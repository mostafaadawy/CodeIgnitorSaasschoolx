<?php 
$running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
$class_info = $this->db->get('class')->result_array();
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
			  <a class="nav-link" href="<?php echo base_url();?>teacher/homework/"><i class="os-icon picons-thin-icon-thin-0014_notebook_paper_todo"></i><span><?php echo get_phrase('homework');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/study_material/"><i class="os-icon picons-thin-icon-thin-0009_book_reading_read_manual"></i><span><?php echo get_phrase('study_material');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/unit_content/"><i class="os-icon picons-thin-icon-thin-0008_book_reading_read_manual"></i><span><?php echo get_phrase('syllabus');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link active" href="<?php echo base_url();?>teacher/online_exams/"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo get_phrase('online_exams');?></span></a>
			</li>
			<?php if($forums==1):?>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/forum/"><i class="os-icon picons-thin-icon-thin-0281_chat_message_discussion_bubble_reply_conversation"></i><span><?php echo get_phrase('forum');?></span></a>
			</li>
			<?php endif;?>
		  </ul>
		</div>
	  </div>
	<div class="content-i">
	<div class="content-box">	
	<div class="col-lg-12">	
	<div style="margin-bottom:15px;text-align:right;"><button class="btn btn-primary btn-rounded btn-upper" data-target="#exampleModal1" data-toggle="modal" type="button">+ <?php echo get_phrase('new_exam');?></button></div>
	<div class="element-wrapper">	
		<div class="element-box lined-primary shadow">
			<h5 class="form-header"><?php echo get_phrase('online_exams');?></h5><br>
		  <div class="table-responsive">
			<table id="dataTable1" width="100%" class="table table-lightborder table-lightfont">
			<thead>
			<tr>
				<th><?php echo get_phrase('title');?></th>
				<th class="text-center"><?php echo get_phrase('class');?></th>
				<th class="text-center"><?php echo get_phrase('section');?></th>
				<th><?php echo get_phrase('subject');?></th>
				<th><?php echo get_phrase('start');?></th>
				<th><?php echo get_phrase('end');?></th>
				<th class="text-center"><?php echo get_phrase('options');?></th>
			</tr>
			</thead>
			<tbody>
			<?php
    				$this->db->order_by('exam_id', 'desc');
    				$post = $this->db->get_where('exams', array('type' => 'teacher', 'teacher_id' => $this->session->userdata('login_user_id')))->result_array();
    				foreach ($post as $row):
        		?>
    			<?php  if ($this->session->userdata('login_user_id') == $row['teacher_id']) { ?>
				<tr>
					<td><?php echo $row['title'];?></td>
					<td class="text-center"><a class="btn nc btn-rounded btn-sm btn-primary" style="color:white"><?php echo $this->crud_model->get_type_name_by_id('class',$row['class_id']);?></a></td>
					<td class="text-center"><a class="btn nc btn-rounded btn-sm btn-secondary" style="color:white"><?php echo $this->crud_model->get_type_name_by_id('section',$row['section_id']);?></a></td>
					<td><?php echo $this->crud_model->get_type_name_by_id('subject',$row['subject_id']);?></td>
					<td><a class="btn nc btn-rounded btn-sm btn-success" style="color:white"><?php echo $row['availablefrom'];?> <?php echo $row['clock_start'];?></a></td>
					<td><a class="btn nc btn-rounded btn-sm btn-danger" style="color:white"><?php echo $row['availableto'];?> <?php echo $row['clock_end'];?></a></td>
					<td class="row-actions">
						<a href="<?php echo base_url();?>teacher/examroom/<?php echo $row['exam_code'];?>"><button class="btn btn-primary btn-rounded btn-sm"><i class="picons-thin-icon-thin-0043_eye_visibility_show_visible"></i><?php echo get_phrase('view');?></button></a>
						<a class="danger" onClick="return confirm('<?php echo get_phrase('confirm_delete');?>')" href="<?php echo base_url();?>teacher/manage_exams/delete/<?php echo $row['exam_id'];?>"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i></a>
					</td>
				</tr>
				    <?php } ?>
				<?php endforeach; ?>
			</tbody>
			</table>
		  </div>
		</div>
	  </div>
	</div>
	<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade bd-example-modal-lg" id="exampleModal1" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php echo get_phrase('new_exam');?></h5>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
          </div>
          <div class="modal-body">
            <?php echo form_open(base_url() . 'teacher/create_exam/create/' , array('enctype' => 'multipart/form-data'));?>
				<div class="row" id="class_div">
			  <div class="col-sm-2">
     			  <div class="form-group">
				  <label class="col-form-label" for=""><?php echo get_phrase('semester');?></label> 
				  <select name="exam_id" class="form-control"> <option value=""><?php echo get_phrase('select');?></option>
				  <?php $semesters = $this->db->get_where('exam' , array('year' => $running_year))->result_array(); 
				  foreach($semesters as $rows):   ?>   
				  <option value="<?php echo $rows['exam_id'];?>"><?php echo $rows['name'];?></option>   
				  <?php endforeach;?>   
				  </select> 
				 </div>
			</div>
			
				<div class="col-sm-3">
				<div class="form-group">
				<label class="col-form-label" for=""><?php echo get_phrase('class');?></label>
					<div class="input-group">
					<div class="input-group-addon">
						<i class="os-icon picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
					</div>
				   <select name="class_id[]" class="form-control" id="class_id" onchange="get_class_subject(this.value); get_class_sections(this.value);">
                      <option value=""><?php echo get_phrase('select');?></option>
							<?php foreach ($class_info as $rowc) { ?>
                               <option value="<?php echo $rowc['class_id']; ?>"><?php echo $rowc['name']; ?></option>
                            <?php } ?>
                        </select>
				  </div>
				</div></div>
				
				
				<div class="col-sm-3">
			  <div class="form-group">
				<label class="col-form-label" for=""><?php echo get_phrase('section');?></label>
					<div class="input-group">
					<div class="input-group-addon">
						<i class="os-icon picons-thin-icon-thin-0002_write_pencil_new_edit"></i>
					</div>
				  <select class="form-control" id="section_selector_holder" required="" name="section_id[]" onchange="get_class_subject(class_id.value , this.value);">
					<option value=""><?php echo get_phrase('select');?></option>
				  </select>
				  </div>
				</div>
			</div>
			
				<div class="col-sm-3">
				<div class="form-group">
				<label class="col-form-label" for=""><?php echo get_phrase('subject');?></label>
					<div class="input-group">
					<div class="input-group-addon">
						<i class="picons-thin-icon-thin-0004_pencil_ruler_drawing"></i>
					</div>
				  <select class="form-control" id="subject_selector_holder" required="" name="subject_id">
					<option value=""><?php echo get_phrase('select');?></option>
				  </select>
				  </div>
			  </div>
				</div>
		</div>	
		<div id="class_entry_append"></div>
            <button class="btn btn-success btn-rounded savech text-right" style="margin:20px 5px" id="add_more" type="buton" disabled onclick="append_class_entry()">+ <?php echo get_phrase('add_more');?></button>
        <br><hr>
<br>
			  <div class="form-group">
				<label for=""> <?php echo get_phrase('title');?></label><input class="form-control" required="" name="title" required="" type="text">
			  </div>
			  <div class="form-group">
				  <label> <?php echo get_phrase('description');?></label><textarea cols="80" id="ckeditor1" required name="description" rows="2"></textarea>
				</div>
			  <div class="row">
				  <div class="col-sm-3">
					<div class="form-group">
					  <label for=""> <?php echo get_phrase('start_date');?></label><input class="form-control" required="" name="availablefrom" id="availablefrom" type="text" value="">
					</div>
				  </div>
				  <div class="col-sm-3">
					  <label for=""> <?php echo get_phrase('start_hour');?></label><div class="input-group clockpicker" data-align="top" data-autoclose="true">
					<input type="text" required="" class="form-control" name="clock_start" value="09:30">
					<span class="input-group-addon">
						<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
					</span>
					</div>
				  </div>
				  <div class="col-sm-3">
					<div class="form-group">
					  <label for=""> <?php echo get_phrase('end_date');?></label><input class="form-control" name="availableto" id="availableto" required type="text" value="">
					</div>
				  </div>
				  <div class="col-sm-3">
					  <label for=""> <?php echo get_phrase('end_hour');?></label><div class="input-group clockpicker" data-align="top" data-autoclose="true">
					<input type="text" required="" name="clock_end" class="form-control" value="09:30">
					<span class="input-group-addon">
						<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
					</span>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="col-sm-4">
					<div class="form-group">
					<label for=""> <?php echo get_phrase('total_questions');?></label><input class="form-control" required placeholder="Questions" type="number" name="questions">
					</div>
				  </div>
				  <div class="col-sm-4">
					<div class="form-group">
					<label for=""> <?php echo get_phrase('exam_duration');?></label><input class="form-control" required="" type="number" name="duration">
					</div>
				  </div>
				  <div class="col-sm-4">
					<div class="form-group">
					<label for=""> <?php echo get_phrase('average_required').' (%)';?></label><input class="form-control" name="pass" required="" type="text">
					</div>
				  </div>
				</div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-rounded btn-success" type="submit"> <?php echo get_phrase('save');?></button>
          </div>
          <?php echo form_close();?>
        </div>
      </div>
    </div>
	</div>  
</div>
</div>

 <script type="text/javascript">
   var blank_class_entry ='';
   var counter=0;
   
   
   $(document).ready(function() {
      blank_class_entry = $('#class_entry').html();
	  
   });

   function append_class_entry()
   {
	   
	   counter++;
	   blank_class_entry = $('<div class="form-group row" id="class_div'+counter+'"> <div class="col-sm-2">     </div><div class="col-sm-3"><div class="form-group"><label class="col-form-label" for=""><?php echo get_phrase('class');?></label><div class="input-group"><div class="input-group-addon">				<i class="os-icon picons-thin-icon-thin-0003_write_pencil_new_edit"></i>								</div>							   <select name="class_id[]" class="form-control" id="class_id'+counter+'" onchange="get_class_sections(this.value); get_class_subject(this.value, section_selector_holder'+counter+'.value)" required="">  <option value=""><?php echo get_phrase('select');?></option><?php foreach ($class_info as $row) { ?><option value="<?php echo $row['class_id']; ?>"><?php echo $row['name']; ?></option>	<?php } ?></select>	</div>	</div>				</div>				<div class="col-sm-3">	 <div class="form-group">	<label class="col-form-label" for=""><?php echo get_phrase('section');?></label>	<div class="input-group"><div class="input-group-addon"><i class="os-icon picons-thin-icon-thin-0002_write_pencil_new_edit"></i>								</div>							  <select class="form-control" id="section_selector_holder'+counter+'" onchange="get_class_subject(class_id'+counter+'.value, this.value);"  required="" name="section_id[]">		<option value=""><?php echo get_phrase('select');?></option>	  </select>	</div>		</div>	</div>	<div class="col-sm-3">	</div><div class="form-group"><label class="col-form-label" for=""><?php echo get_phrase('delete');?></label>	 <div class="input-group">    <button class="btn btn-sm btn-danger bulk text-center" href="#" onclick="deleteParentElement(this)"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i></button> </div></div> </div> ');
      $("#class_entry_append").append(blank_class_entry);
	  
   }
   
   
   function get_classes_by_subject(subject_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>teacher/get_classes_by_subject/' + subject_id,
            success: function (response)
			
            {
				jQuery('#class_id').html(response);
            }
        });
    }
   
    function get_class_subject(class_id, section_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>teacher/get_class_section_subject/' + class_id + '/' + section_id,
            success: function (response)
			
            {
				if (counter>0)
				   {
					jQuery('#subject_selector_holder'+counter+'').html(response);
					}
			   else
			   {
				   jQuery('#subject_selector_holder').html(response);
				   $('#add_more').prop('disabled', false);
			   }
            }
        });
    }
	
	
	 function get_class_sections(class_id) 
    {
        $.ajax({
            url: '<?php echo base_url();?>teacher/get_class_section/' + class_id ,
            success: function(response)
            {
				if (counter>0)
				{
					jQuery('#section_selector_holder'+counter+'').html(response);
				}
				else
				{
					jQuery('#section_selector_holder').html(response);
				}
                
            }
        });
    }

   function deleteParentElement(n)
   {
	   
	   if(counter>0)
	   {
		       var element = document.getElementById('class_div'+counter+'');
               element.parentNode.removeChild(element);
		   //n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		   counter--;

	   }
       
   }

</script>
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
