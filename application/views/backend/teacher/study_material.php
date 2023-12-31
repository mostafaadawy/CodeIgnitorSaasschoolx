<?php $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description; ?>
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
			  <a class="nav-link" href="<?php echo base_url();?>teacher/homework/"><i class="os-icon picons-thin-icon-thin-0014_notebook_paper_todo"></i><span><?php echo get_phrase('homework');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link active" href="<?php echo base_url();?>teacher/study_material/"><i class="os-icon picons-thin-icon-thin-0009_book_reading_read_manual"></i><span><?php echo get_phrase('study_material');?></span></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/unit_content/"><i class="os-icon picons-thin-icon-thin-0008_book_reading_read_manual"></i><span><?php echo get_phrase('syllabus');?></span></a>
			</li>
			<?php if($exams==1):?>
			<li class="nav-item">
			  <a class="nav-link" href="<?php echo base_url();?>teacher/online_exams/"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo get_phrase('online_exams');?></span></a>
			</li>
			<?php endif;?>
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
	<div style="margin-bottom:15px;text-align:right;"><button class="btn btn-primary btn-rounded btn-upper" data-target="#exampleModal1" data-toggle="modal" type="button">+ <?php echo get_phrase('add');?></button></div>
	<div class="element-wrapper">
		<div class="element-box lined-primary shadow">
			<h6 class="form-header">
			 <?php echo get_phrase('study_material');?>
			</h6>
		  <div class="table-responsive">
			<table id="dataTable1" width="100%" class="table table-lightborder table-lightfont">
			<thead>
				<tr>
					<th><?php echo get_phrase('type');?></th>
					<th><?php echo get_phrase('title');?></th>
					<th><?php echo get_phrase('description');?></th>
					<th><?php echo get_phrase('class');?></th>
					<th><?php echo get_phrase('subject');?></th>
					<th><?php echo get_phrase('download');?></th>
					<th class="text-center"><?php echo get_phrase('options');?></th>
				</tr>
			</thead>
			<tbody>
			<?php
        		$count = 1;
        		$this->db->order_by('timestamp', 'desc');
        		$study_material_info = $this->db->get_where('document', array('teacher_id' => $this->session->userdata('login_user_id'), 'type' => 'teacher'))->result_array();
        		foreach ($study_material_info as $row):
        	?>   
				<tr>
					<td><?php if($row['file_type'] == 'PDF'):?>
					<i class="picons-thin-icon-thin-0077_document_file_pdf_adobe_acrobat" style="font-size:25px"></i>
				<?php endif;?>
				<?php if($row['file_type'] == 'Zip'):?>
					<i class="picons-thin-icon-thin-0076_document_file_zip_archive_compressed_rar" style="font-size:25px"></i>
				<?php endif;?>
				<?php if($row['file_type'] == 'RAR'):?>
					<i class="picons-thin-icon-thin-0076_document_file_zip_archive_compressed_rar" style="font-size:25px"></i>
				<?php endif;?>
				<?php if($row['file_type'] == 'Doc'):?>
					<i class="picons-thin-icon-thin-0078_document_file_word_office_doc_text" style="font-size:25px"></i>
				<?php endif;?>
				<?php if($row['file_type'] == 'Image'):?>
					<i class="picons-thin-icon-thin-0082_image_photo_file" style="font-size:25px"></i>
				<?php endif;?>
				<?php if($row['file_type'] == 'Other'):?>
					<i class="picons-thin-icon-thin-0111_folder_files_documents" style="font-size:25px"></i>
				<?php endif;?></td>
					<td><?php echo $row['title'];?></td>
					<td><?php echo $row['description']?></td>
					<td><a class="btn nc btn-rounded btn-sm btn-primary" style="color:white"><?php $name = $this->db->get_where('class' , array('class_id' => $row['class_id'] ))->row()->name;
                        echo $name;?></a></td>
					<td><?php $name = $this->db->get_where('subject' , array('subject_id' => $row['subject_id'] ))->row()->name;
                        echo $name;?></td>
					<td><a class="btn btn-rounded btn-sm btn-secondary" style="color:white" href="<?php echo base_url().$subdomain.'uploads/document/'.$row['file_name']; ?>"><i class="picons-thin-icon-thin-0042_attachment"></i> <?php echo get_phrase('download');?></a></td>
					<td class="row-actions">
						<a class="danger" onClick="return confirm('<?php echo get_phrase('confirm_delete');?>')" href="<?php echo base_url();?>teacher/study_material/delete/<?php echo $row['document_id']?>"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i></a>
					</td>
				</tr>
			<?php endforeach;?>
			</tr>
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
            <h5 class="modal-title" id="exampleModalLabel"><?php echo get_phrase('upload');?></h5><br>
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
          </div>
          <div class="modal-body">
            <?php echo form_open(base_url() . 'teacher/study_material/create', array('enctype' => 'multipart/form-data')); ?>
				<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					<label class="col-form-label" for=""><?php echo get_phrase('class');?></label>
						<div class="input-group">
						<div class="input-group-addon">
							<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
						</div>
					  <select class="form-control" required="" name="class_id[]" onchange="get_class_subject(this.value);"">
						<option value=""><?php echo get_phrase('select');?></option>
						<?php $cl = $this->db->get('class')->result_array();
						 foreach($cl as $row):
						?>
						 <option value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
					  <?php endforeach;?>
					  </select>
					  </div>
					</div>
				</div>
				
				<div class="col-sm-6">
				<div class="form-group">
				<label class="col-form-label" for=""><?php echo get_phrase('subject');?></label>
					<div class="input-group">
					<div class="input-group-addon">
						<i class="picons-thin-icon-thin-0004_pencil_ruler_drawing"></i>
					</div>
				  <select class="form-control" name="subject_id" required="" id="subject_selector_holder">
					<option value=""><?php echo get_phrase('select');?></option>					
				  </select>
				  </div>
			  </div>
			  </div> 
			  
			  </div>
			  <div id="class_entry_append"></div>
            <button class="btn btn-success btn-rounded savech text-right" style="margin:20px 5px" id="add_more" type="buton" disabled onclick="append_class_entry()">+ <?php echo get_phrase('add_more');?></button>
			
				
			  <div class="form-group">
				<label for=""> <?php echo get_phrase('title');?></label><input required="" name="title" class="form-control"  type="text">
			  </div>
			  <div class="form-group">
				  <label> <?php echo get_phrase('description');?></label><textarea required="" class="form-control" name="description" rows="3"></textarea>
				</div>
				<div class="form-group">
				<label class="col-form-label" for=""> <?php echo get_phrase('file');?></label>
				  <div class="input-group form-control">
				  <input type="file" name="file_name" id="file-3" class="inputfile inputfile-3" style="display:none"/>
					<label for="file-3"><i class="os-icon picons-thin-icon-thin-0042_attachment"></i> <span><?php echo get_phrase('send_file');?>...</span></label>
				  </div>
				  </div>
				  <div class="form-group">
				<label class="col-form-label" for=""><?php echo get_phrase('type');?></label>
					<div class="input-group">
					<div class="input-group-addon">
						<i class="picons-thin-icon-thin-0073_documents_files_paper_text_archive_copy"></i>
					</div>
				  <select class="form-control" name="file_type" required="">
						<option value="PDF">PDF</option>
						<option value="Doc">Doc</option>
						<option value="Zip">Zip</option>
						<option value="RAR">RAR</option>
						<option value="Image"><?php echo get_phrase('image');?></option>
						<option value="Other"><?php echo get_phrase('other');?></option>
				  </select>
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
	   blank_class_entry = $('<div class="form-group row" id="class_div'+counter+'"> <div class="col-sm-6"><div class="form-group"><label class="col-form-label" for=""><?php echo get_phrase('class');?></label><div class="input-group"><div class="input-group-addon">				<i class="os-icon picons-thin-icon-thin-0003_write_pencil_new_edit"></i>								</div>							   <select name="class_id[]" class="form-control" id="class_id'+counter+'" onchange="get_class_sections(this.value); get_class_subject(this.value, section_selector_holder'+counter+'.value)" required="">  <option value=""><?php echo get_phrase('select');?></option><?php foreach ($cl as $row) { ?><option value="<?php echo $row['class_id']; ?>"><?php echo $row['name']; ?></option>	<?php } ?></select>	</div>	</div>				</div>				<div class="col-sm-3">	</div>	<div class="col-sm-2">	</div><div class="form-group"><label class="col-form-label" for=""><?php echo get_phrase('delete');?></label>	 <div class="input-group">    <button class="btn btn-sm btn-danger bulk text-center" href="#" onclick="deleteParentElement(this)"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i></button> </div></div> </div> ');
      $("#class_entry_append").append(blank_class_entry);
	  
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

<script type="text/javascript">
    function get_class_subject(class_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>teacher/get_class_subject/' + class_id,
            success: function (response)
            {
                jQuery('#subject_selector_holder').html(response);
				$('#add_more').prop('disabled', false);
            }
        });
    }
</script>