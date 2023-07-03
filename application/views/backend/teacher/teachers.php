<?php 
$id = $this->session->userdata('login_user_id');
$subdomain      = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
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
				  <a class="nav-link active" href="<?php echo base_url();?>teacher/teacher_list/"><i class="os-icon picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></i> <span><?php echo get_phrase('teachers');?></span></a>
				</li>
			  </ul>
			</div>
		  </div>
     <div class="content-i">
      <div class="content-box">
      <div class="tab-content">
      <div class="col-lg-12">
      <div class="element-wrapper">
         <div class="element-box lined-primary shadow">
           <div class="table-responsive">
            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
               <thead>
                  <tr>
                     <th><?php echo get_phrase('name');?></th>
                     <th><?php echo get_phrase('email');?></th>
                     <th><?php echo get_phrase('birthday');?></th>
                     <th><?php echo get_phrase('send_message');?></th>
                  </tr>
               </thead>
               <tbody>
               <?php 
                $this->db->order_by('teacher_id', 'desc');
                $teachers = $this->db->get('teacher')->result_array();
               foreach($teachers as $row):
               ?>
                  <tr>
                     <td><img alt="" src="<?php echo $this->crud_model->get_image_url('teacher', $row['teacher_id']);?>" width="25px" style="border-radius: 10px;margin-right:5px;"> <?php echo $row['name']; ?></td>
                     <td><?php echo $row['email']; ?></td>
                     <?php $data = 'teacher' ."-".$row['teacher_id'];
                          $send_data = base64_encode($data);
                      ?>
                     <td><div class="pt-btn"><a class="btn nc btn-purple btn-sm btn-rounded" style="color:white"><i class="os-icon picons-thin-icon-thin-0447_gift_wrapping"></i> <?php $dob = explode('/',$row['birthday']); echo $dob[0].'/'.$dob[1]; ?></div></td>
					 <?php if($messaging==1):?>
                     <td><div class="pt-btn"><a class="btn btn-success btn-sm btn-rounded" style="color:white" href="<?php echo base_url();?>teacher/message/message_new/<?php echo $send_data;?>"><i class="os-icon picons-thin-icon-thin-0317_send_post_paper_plane"></i>  <?php echo get_phrase('send_message');?></a></div></td>
					 <?php endif;?>
					 <?php if(!$messaging==1):?>
                     <td><div class="pt-btn" disabled><a class="btn btn-success btn-sm btn-rounded" style="color:white" ><i class="os-icon picons-thin-icon-thin-0317_send_post_paper_plane"></i>  <?php echo 'currently Disabled';?></a></div></td>
					 <?php endif;?>
                  </tr>
               <?php endforeach;?>
               </tbody>
            </table>
           </div>
         </div>
        </div>
      </div>
      
      </div>
      </div>
      </div>
     </div>