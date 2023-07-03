<?php $class_info = $this->db->get('class')->result_array(); ?>

<style>
	
	 h4{
		color: white !important;
	}
		  </style>
<?php 
$edit_data		=	$this->db->get_where('grade' , array('grade_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
        <?php echo form_open(base_url() . 'admin/grade/update/'.$row['grade_id'] , array('enctype' => 'multipart/form-data'));?>
         
		 <div class="form-group row">
				<label class="col-sm-4  col-form-label" for=""><?php echo get_phrase('class');?></label>
				   <div class="col-sm-8">
					<div class="input-group">
					<div class="input-group-addon">
						<i class="os-icon picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
					</div>
				   <select name="class_id" class="form-control" id="class_id" onchange="get_class_subject2(this.value);">
                      <option value=""><?php echo get_phrase('select');?></option>
							<?php foreach ($class_info as $row2) { ?>
                               <option value="<?php echo $row2['class_id']; ?>"><?php echo $row2['name']; ?></option>
                            <?php } ?>
                        </select>
				  </div>
				</div>
		</div>
	
				<div class="form-group row">
				<label class="col-sm-4 col-form-label" for=""><?php echo get_phrase('subject');?></label>
				    <div class="col-sm-8">
					<div class="input-group">
					<div class="input-group-addon">
						<i class="picons-thin-icon-thin-0004_pencil_ruler_drawing"></i>
					</div>
				  <select class="form-control" id="subject_selector_holder2" required="" name="subject_id" >
					<option value=""><?php echo get_phrase('select');?></option>
				  </select>
				  </div>
			  </div>
				</div>
         
		  
		 <div class="form-group row">
              <label class="col-sm-4 col-form-label" for=""> <?php echo get_phrase('name');?></label>
              <div class="col-sm-8">
              <div class="input-group">
                <div class="input-group-addon">
                    <i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
                  </div>
                <input class="form-control" name="name" value="<?php echo $row['name'];?>" required="" type="text">
                </div>
              </div>
            </div>
         <div class="form-group row">
              <label class="col-sm-4 col-form-label" for=""> <?php echo get_phrase('point');?></label>
              <div class="col-sm-8">
              <div class="input-group">
                <div class="input-group-addon">
                    <i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
                  </div>
                <input class="form-control" name="point" value="<?php echo $row['grade_point'];?>" required="" type="text">
                </div>
              </div>
            </div>
          <div class="form-group row">
              <label class="col-sm-4 col-form-label" for=""> <?php echo get_phrase('mark_from');?></label>
              <div class="col-sm-8">
              <div class="input-group">
                <div class="input-group-addon">
                    <i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
                  </div>
                <input class="form-control" name="from" value="<?php echo $row['mark_from'];?>" required="" type="text">
                </div>
              </div>
            </div>
           <div class="form-group row">
              <label class="col-sm-4 col-form-label" for=""> <?php echo get_phrase('mark_to');?></label>
              <div class="col-sm-8">
              <div class="input-group">
                <div class="input-group-addon">
                    <i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
                  </div>
                <input class="form-control" name="to" value="<?php echo $row['mark_upto'];?>" required="" type="text">
                </div>
              </div>
            </div>
          <div class="form-buttons-w">
            <button class="btn btn-rounded btn-primary" style="float: right;" type="submit"> <?php echo get_phrase('update');?></button><br>
          </div>
        <?php echo form_close();?>
<?php endforeach; ?>

<script type="text/javascript">
    function get_class_subject2(class_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/get_class_subject/' + class_id,
            success: function (response)
            {
                jQuery('#subject_selector_holder2').html(response);
            }
        });
    }
</script>