<?php $status = $this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->row()->owner_status;?>
<div class="content-w">
    <?php if($this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->row()->users == 1):?>
<div class="os-tabs-w menu-shad">
			<div class="os-tabs-controls">
			  <ul class="nav nav-tabs upper">
				 <?php if($status == 1):?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url();?>admin/admins/"><i class="os-icon picons-thin-icon-thin-0710_business_tie_user_profile_avatar_man_male"></i><span><?php echo get_phrase('admins');?></span></a>
        </li>
      <?php endif;?>
				<li class="nav-item">
				  <a class="nav-link" href="<?php echo base_url();?>admin/teachers/"><i class="os-icon picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></i><span><?php echo get_phrase('teachers');?></span></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link active" href="<?php echo base_url();?>admin/parents/"><i class="os-icon picons-thin-icon-thin-0703_users_profile_group_two"></i><span><?php echo get_phrase('parents');?></span></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link" href="<?php echo base_url();?>admin/add_student/"><i class="os-icon picons-thin-icon-thin-0729_student_degree_science_university_school_graduate"></i><span><?php echo get_phrase('students');?></span></a>
				</li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url();?>admin/admissions/"><i class="os-icon picons-thin-icon-thin-0706_user_profile_add_new"></i><span><?php echo get_phrase('admissions');?></span></a>
        </li>
			  </ul>
			</div>
		  </div>
     <div class="content-i">
      <div class="content-box">
      <div class="tab-content">
      <div class="os-tabs-w">
         <div class="os-tabs-controls">
           <ul class="nav nav-tabs upper">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#parents"><?php echo get_phrase('parents');?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#new"><?php echo get_phrase('new');?></a>
            </li>
			<li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#import"><?php echo get_phrase('excel_import');?></a>
            </li>
           </ul>
         </div>
        </div>
      <div class="tab-pane active" id="parents">
      <div class="col-lg-12">
      <div class="element-wrapper">
         <div class="element-box shadow lined-primary">
           <div class="table-responsive">
           <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
               <thead>
                  <tr>
				     <th width="50px"><input type="checkbox" id="master"></th>
                     <th><?php echo get_phrase('name');?></th>
                     <th><?php echo get_phrase('username');?></th>
                     <th><?php echo get_phrase('email');?></th>
                     <th><?php echo get_phrase('phone');?></th>
                     <th><?php echo get_phrase('profession');?></th>
                     <th><?php echo get_phrase('options');?></th>
                  </tr>
               </thead>
               <tbody>
               <?php 
                $this->db->order_by('parent_id', 'desc');
                $parents = $this->db->get('parent')->result_array();
               foreach($parents as $row):
               ?>
                  <tr>
				      <td><input type="checkbox" class="sub_chk" data-id="<?php echo $row['parent_id']; ?>"></td>
                     <td><img alt="" src="<?php echo $this->crud_model->get_image_url('parent', $row['parent_id']);?>" width="25px" style="border-radius: 10px;margin-right:5px;"> <?php echo $row['name']; ?></td>
                     <td><?php echo $row['username']; ?></td>
                     <td><?php echo $row['email']; ?></td>
                     <td><?php echo $row['phone']; ?></td>
                     <td style="text-align: center;"><div class="pt-btn"><a class="btn nc btn-success btn-sm btn-rounded"><font color="white"><?php echo $row['profession']; ?></font></a></div></td>
                     <td><a href="<?php echo base_url();?>admin/parent_profile/<?php echo $row['parent_id'];?>/"><button class="btn btn-primary btn-rounded btn-sm"><i class="os-icon os-icon-user-male-circle"></i> <?php echo get_phrase('profile');?></button></a> <a  onClick="return confirm('<?php echo get_phrase('confirm_delete');?>')" href="<?php echo base_url();?>admin/parents/delete/<?php echo $row['parent_id'];?>"><button class="btn btn-danger btn-rounded btn-sm"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i> <?php echo get_phrase('delete');?></button></a></td>
                  </tr>
               <?php endforeach;?>
               </tbody>
            </table>
			
			<button style="margin-bottom: 10px" class="btn btn-primary delete_all">Delete Selected</button>
           </div>
         </div>
        </div>
      </div>
      </div>
      
      <div class="tab-pane" id="new">
      <div class="col-lg-12">
      <div class="element-wrapper">
        <div class="element-box lined-primary shadow">
                  <?php echo form_open(base_url() . 'admin/parents/create/' , array('enctype' => 'multipart/form-data'));?>
           <h5 class="form-header"><?php echo get_phrase('add');?></h5><br>
           <div class="form-group row">
              <label class="col-sm-3 col-form-label" for=""> <?php echo get_phrase('name');?></label>
              <div class="col-sm-9">
              <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0701_user_profile_avatar_man_male"></i>
                 </div>
               <input class="form-control" placeholder="<?php echo get_phrase('name');?>" required name="name" type="text">
               </div>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label" for=""><?php echo get_phrase('username');?></label>
              <div class="col-sm-9">
               <div class="input-group">
                 <div class="input-group-addon">
                 <i class="picons-thin-icon-thin-0313_email_at_sign"></i> 
                 </div>
                 <input class="form-control" placeholder="<?php echo get_phrase('username');?>" required="" name="username" type="text">
               </div>
              </div>
            </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('email');?></label>
            <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0319_email_mail_post_card"></i>
               </div>
              <input class="form-control" placeholder="user@school.edu" name="email" type="email">
              </div>
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('phone');?></label>
            <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0289_mobile_phone_call_ringing_nfc"></i>
               </div>
              <input class="form-control" placeholder="<?php echo get_phrase('phone');?>" name="phone" type="phone">
              </div>
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('address');?></label>
            <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0535_navigation_location_drop_pin_map"></i>
               </div>
              <input class="form-control" placeholder="<?php echo get_phrase('address');?>" name="address" type="text">
              </div>
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('profession');?></label>
            <div class="col-sm-9">
               <div class="input-group">
               <div class="input-group-addon">
               <i class="picons-thin-icon-thin-0379_business_suitcase"></i>
               </div>
              <input class="form-control" placeholder="<?php echo get_phrase('profession');?>" name="profession" type="text">
              </div>
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('password');?></label>
            <div class="col-sm-9">
              <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0136_rotation_lock"></i>
               </div>
              <input class="form-control" placeholder="<?php echo get_phrase('password');?>" name="password" required="" type="password">
              </div>
            </div>
           </div>
           <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('photo');?></label>
            <div class="col-sm-9 profile-side-user">
          <button type="button" class="avatar-preview avatar-preview-128">
				<img id="ava" src="<?php echo base_url();?>style/cms/img/avatar-1-256.png" alt=""/>
				<span class="update">
					<i class="font-icon picons-thin-icon-thin-0617_picture_image_photo"></i>
					<?php echo get_phrase('upload');?>
				</span>
				<input name="userfile" accept="image/x-png,image/gif,image/jpeg" id="imgpre" type="file"/>
			</button></div>
            </div>
           <div class="form-buttons-w" >
            <button class="btn btn-primary btn-rounded" type="submit"><?php echo get_phrase('save');?></button>
           </div>
           </div>
         <?php echo form_close();?>
        </div>
      </div>
      </div>
	  
	  
	  <div class="tab-pane" id="import">
      <div class="element-box lined-primary shadow">
      <div class="b-b"><h5 class="form-header">
       <?php echo get_phrase('excel_import');?>
      </h5>
	  <h6 class="form-header">
       <?php echo 'All fields of the Excel Sheet are required. Blank fields and/or duplicate user-names are not allowed!!';?>
      </h6>
        <div class="text-right" style="margin-top:-25px;margin-bottom:25px;"><a href="<?php echo base_url();?>uploads/import/parents_excel.xlsx"><button class="btn btn-primary btn-rounded btn-sm"><i class="picons-thin-icon-thin-0105_download_clipboard_box"></i>  <?php echo get_phrase('download_model');?></button></a></div>
		</div>
		<div style="margin:30px 10px;">
      <?php echo form_open(base_url() . 'admin/parent_bulk/excel/' , array('class' => 'form-inline', 'enctype' => 'multipart/form-data'));?>
		<div class="form-group col-sm-4">
            <label class="col-form-label" for=""> <?php echo get_phrase('file');?></label>
              <div class="input-group">
               <div class="input-group-addon">
                  <i class="picons-thin-icon-thin-0042_attachment"></i>
               </div>
              <input class="form-control" placeholder="<?php echo get_phrase('file');?>" required name="excel" type="file">
              </div>
            </div>
         </div>
      <div class="form-buttons-w">
          <button class="btn btn-primary btn-rounded" type="submit"><?php echo get_phrase('import');?></button>
      </div>
      <?php echo form_close();?>
      </div>
      </div>
	  
	  
	  
	  
	  
      </div>
      </div>
      </div>
    <?php endif;?>
     </div>
	 
	 <script type="text/javascript">
    $(document).ready(function () {
 
        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });
 
        $('.delete_all').on('click', function(e) {
 
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  
 
            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  
 
                var check = confirm("Are you sure you want to delete?");  
                if(check == true){  
 
                    var join_selected_values = allVals.join(","); 
 
                    $.ajax({
                        url: "<?php echo base_url();?>admin/delete_multiple_parents/",
                        type: 'POST',
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                          console.log(data);
                          $(".sub_chk:checked").each(function() {  
                              $(this).parents("tr").remove();
                          });
						  window.location.href = document.URL;
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
 
                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });
    });
</script>