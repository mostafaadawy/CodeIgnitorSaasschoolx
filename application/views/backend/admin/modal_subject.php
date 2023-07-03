<style>
	
	 h4{
		color: white !important;
	}
		  </style>
<?php  $edit_data = $this->db->get_where('subject' , array('subject_id' => $param2))->result_array();
        foreach($edit_data as $row):
?>    
        <?php echo form_open(base_url() . 'admin/courses/update/'.$row['subject_id'], array('enctype' => 'multipart/form-data')); ?>
          <br>
          <div class="form-group row">
              <label class="col-sm-4 col-form-label" for=""> <?php echo get_phrase('name');?></label>
              <div class="col-sm-8">
              <div class="input-group">
                <div class="input-group-addon">
                    <i class="picons-thin-icon-thin-0004_pencil_ruler_drawing"></i>
                  </div>
                <input class="form-control" name="name" value="<?php echo $row['name'];?>" required="" type="text">
                </div>
              </div> 
            </div>
			
			<div class="form-group row">
      <label class="col-form-label col-sm-4" for=""> <?php echo get_phrase('class');?></label>
      <div class="col-sm-8">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
        </div>
        <select class="form-control" name="class_id" required="" onchange="get_class_sectionss(this.value);">
        <option value=""><?php echo get_phrase('select');?></option>
         <?php $cl = $this->db->get('class')->result_array();
            foreach($cl as $rowc): ?>
            <option value="<?php echo $rowc['class_id'];?>"><?php echo $rowc['name'];?></option>
        <?php endforeach;?>
        </select>
        </div>
      </div>
      </div>
	  
	  <div class="form-group row">
				<label class="col-form-label col-sm-4" for=""> <?php echo get_phrase('section');?></label>
				<div class="col-sm-8">
					<div class="input-group">
					<div class="input-group-addon">
						<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
					</div>
					<select class="form-control" name="section_id" required="" id="section_selector_holders">
						<!--<option value=""><?php //echo get_phrase('select');?></option>-->
					</select>
				  </div>
				</div>
			  </div>
			  
	  <div class="form-group row">
				<label class="col-form-label col-sm-4" for=""> <?php echo get_phrase('type');?></label>
				<div class="col-sm-8">
					<div class="input-group">
					<div class="input-group-addon">
						<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
					</div>
					<select class="form-control" name="subject_type" required="" id="subject_type2" onchange="get_section_studentss(section_selector_holders.value);">
					   <?php if (! $row['type']=='1' ): ?>
							<option value="1"><?php echo get_phrase('obligatory');?></option>
							<option value="0"><?php echo get_phrase('selective');?></option>
						<?php endif;?>
						<?php if ($row['type'] == '1' ): ?>
							<option value="0"><?php echo get_phrase('selective');?></option>
							<option value="1"><?php echo get_phrase('obligatory');?></option>
						<?php endif;?>
					</select>
				  </div>
				</div>
	  </div>

     <!-- List Students for selective courses -->
      <div class="form-group row"  >
				<label class="col-form-label col-sm-4" for=""> <?php echo get_phrase('students');?></label>
				<div class="col-sm-8">
					<div class="input-group">
					<div class="input-group-addon">
						<i class="picons-thin-icon-thin-0729_student_degree_science_university_school_graduate"></i>
					</div>
				  	<select class="form-control" id="student_id2"  name="students_holder[]" multiple="multiple" height="5px">
						<option value=""><?php echo get_phrase('select');?></option>
				  	</select>
				  </div>
				</div>
	 </div> 		  
			
			
          <div class="form-group row">
            <label class="col-form-label col-sm-4" for=""> <?php echo get_phrase('teacher');?></label>
            <div class="col-sm-8">
                <div class="input-group">
                <div class="input-group-addon">
                    <i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></i>
                </div>
              <select class="form-control" name="teacher_id">
                <option value=""><?php echo get_phrase('select');?></option>
                <?php $teachers = $this->db->get('teacher')->result_array(); 
                foreach($teachers as $teacher):
                ?>
                <option value="<?php echo $teacher['teacher_id'];?>" <?php if($row['teacher_id'] == $teacher['teacher_id']) echo 'selected';?>><?php echo $teacher['name'];?></option>
            <?php endforeach;?>
              </select>
              </div>
            </div>
          </div>
          <div class="form-buttons-w">
            <button class="btn btn-rounded btn-primary" style="float: right;" type="submit"> <?php echo get_phrase('update');?></button><br>
          </div>
        <?php echo form_close();?>
<?php endforeach; ?>

<script type="text/javascript">
    function get_class_sectionss(class_id) 
    {
        $.ajax({
            url: '<?php echo base_url();?>admin/get_class_section/' + class_id ,
            success: function(response)
            {
                jQuery('#section_selector_holders').html(response);
            } 
        });
    }
</script>

<script type="text/javascript">
    function get_section_studentss(section_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_section_students/' + section_id,
            success: function (response)
            {
				//$('#student_list').prop("hidden", false);
                jQuery('#student_id2').html(response);
            }
        });
    }
</script>