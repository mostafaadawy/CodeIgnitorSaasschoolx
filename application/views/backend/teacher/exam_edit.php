<?php $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description; ?>
<?php $exam = $this->db->get_where('exams', array('exam_code' => $exam_code))->result_array();
	?>
<div class="content-w">
	  <div class="os-tabs-w menu-shad">
		<div class="os-tabs-controls">
		  <ul class="nav nav-tabs upper">
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/examroom/<?php echo $exam_code;?>/"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo get_phrase('exam_details');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/exam_questions/<?php echo $exam_code;?>/"><i class="os-icon picons-thin-icon-thin-0067_line_thumb_view"></i><span><?php echo get_phrase('questions');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/exam_results/<?php echo $exam_code;?>/"><i class="os-icon picons-thin-icon-thin-0100_to_do_list_reminder_done"></i><span><?php echo get_phrase('results');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link active" href="<?php echo base_url();?>teacher/exam_edit/<?php echo $exam_code;?>/"><i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i><span><?php echo get_phrase('edit');?></span></a>
			</li>
		  </ul>
		</div>
	  </div>
	  
<?php 
   $ii=0;
   foreach ($exam as $row): ?>	  
  <div class="content-i">
    <div class="content-box">
	<div class="row">
	
	<div class="col-sm-8">
	   <?php if($ii==0) :?>
		<div class="pipeline white lined-primary">
		  <div class="pipeline-header">
			<h5 class="pipeline-name">
			  <?php echo $row['title']; ?>
			</h5>
		  </div>
			<?php echo form_open(base_url() . 'teacher/online_exams/edit/' . $row['exam_code'], array('enctype' => 'multipart/form-data')); ?>
			  <div class="form-group">
				<label for=""> <?php echo get_phrase('title');?></label><input class="form-control" required="" name="title" type="text" value="<?php echo $row['title']; ?>">
			  </div>
			  <div class="form-group">
				  <label> <?php echo get_phrase('description');?></label><textarea cols="80" id="ckeditor1" name="description" required="" rows="2"><?php echo $row['description']; ?></textarea>
				</div>
			  <div class="row">
				  <div class="col-sm-3">
					<div class="form-group">
					  <label for=""> <?php echo get_phrase('start_date');?></label><input class="form-control" required="" type="text" value="<?php echo $row['availablefrom']; ?>" name="availablefrom" id="availablefrom">
					</div>
				  </div>
				  <div class="col-sm-3">
					<div class="form-group">
					  <label for=""> <?php echo get_phrase('start_clock');?></label><div class="input-group clockpicker" data-align="top" data-autoclose="true">
					<input type="text" required="" class="form-control" name="clock_start" value="<?php echo $row['clock_start']; ?>">
					<span class="input-group-addon">
						<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
					</span>
					</div>
					</div>
				  </div>
				  <div class="col-sm-3">
					<div class="form-group">
					  <label for=""> <?php echo get_phrase('end_date');?></label><input class="form-control" required="" type="text" value="<?php echo $row['availableto']; ?>" name="availableto" id="availableto">
					</div>
				  </div>
				  <div class="col-sm-3">
					<div class="form-group">
					  <label for=""> <?php echo get_phrase('end_clock');?></label><div class="input-group clockpicker" data-align="top" data-autoclose="true">
					<input type="text" required="" class="form-control" name="clock_end" value="<?php echo $row['clock_end']; ?>">
					<span class="input-group-addon">
						<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
					</span>
					</div>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="col-sm-4">
					<div class="form-group">
					<label for=""> <?php echo get_phrase('total_questions');?></label><input class="form-control"  required="" name="questions" type="number" value="<?php echo $row['questions']; ?>">
					</div>
				  </div>
				  <div class="col-sm-4">
					<div class="form-group">
					<label for=""> <?php echo get_phrase('exam_duration');?></label><input class="form-control" required="" type="number" value="<?php echo $row['duration']; ?>" name="duration">
					</div>
				  </div>
				  <div class="col-sm-4">
					<div class="form-group">
					<label for=""> <?php echo get_phrase('average_required').' (%)';?></label><input class="form-control" required="" name="pass" type="number" value="<?php echo $row['pass']; ?>">
					</div>
				  </div>
				</div>
			<div class="form-buttons-w text-right">
			  <button class="btn btn-rounded btn-success" type="submit"><?php echo get_phrase('update');?></button>
			</div>
			<?php echo form_close();?>
		</div>
		<?php $ii=1; endif;?>
		</div>
	 
	<div class="col-sm-4">
	
			<div class="pipeline white lined-secondary">
		  <div class="pipeline-header">
			<h5 class="pipeline-name">
			 <?php echo get_phrase('information');?>
			</h5>
		  </div>
		  <div class="table-responsive">
		  <table class="table table-lightbor table-lightfont">
		      <tr>
				<th>
				  <?php echo get_phrase('class');?>:
				</th>
				<td>
				 <?php echo $this->crud_model->get_type_name_by_id('class',$row['class_id']);?>
				</td>
			  </tr>
			  <tr>
				<th>
				  <?php echo get_phrase('section');?>:
				</th>
				<td>
				  <?php echo $this->crud_model->get_type_name_by_id('section',$row['section_id']);?>
				</td>
			  </tr>
			  <tr>
				<th>
				 <?php echo get_phrase('start_date');?>:
				</th>
				<td>
				  <?php echo $row['availablefrom'];?> - <?php echo $row['clock_start'];?>
				</td>
			  </tr>
			  <tr>
				<th>
				  <?php echo get_phrase('end_date');?>
				</th>
				<td>
				 <?php echo $row['availableto'];?> - <?php echo $row['clock_end'];?>
				</td>
			  </tr>
			  <tr>
				<th>
				 <?php echo get_phrase('average_required');?>:
				</th>
				<td>
				  <a class="btn btn-rounded btn-sm btn-primary" style="color:white"><?php echo $row['pass'];?>%</a>
				</td>
			  </tr>
			  <tr>
				<th>
				 <?php echo get_phrase('total_questions');?>:
				</th>
				<td>
				  <a class="btn btn-rounded btn-sm btn-secondary" style="color:white"><?php echo $row['questions'];?></a>
				</td>
			  </tr>
			  <tr>
				<th>
				 <?php echo get_phrase('exam_duration');?>:
				</th>
				<td>
				  <a class="btn btn-rounded btn-sm btn-success" style="color:white"><?php echo $row['duration'];?> mins.</a>
				</td>
			  </tr>
		  </table>
		</div>
		</div>
	
		
	</div>
</div>
<?php endforeach;?>
</div>
</div>
</div>
