<?php $user = $this->session->userdata('login_type')."-".$this->session->userdata('login_user_id');?>
<div class="content-w">
  <div class="os-tabs-w menu-shad">
    <div class="os-tabs-controls">
      <ul class="nav nav-tabs upper">
        <li class="nav-item">
          <a class="nav-link active" ><i class="os-icon picons-thin-icon-thin-0389_gavel_hammer_law_judge_court"></i><span><?php echo get_phrase('reports');?></span></a>
        </li>
      </ul>
    </div>
  </div>
  <div class="content-i">
    <div class="content-box">
    <div class="element-wrapper">
         <div class="tab-content">
        <div class="tab-pane active" id="students">
          <div class="element-box lined-primary shadow">
          <div class="form-header">
            <h5><?php echo get_phrase('student_reports');?></h5>
          </div>
          <div class="table-responsive">
            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
            <thead>
                <tr>
                    <th><?php echo get_phrase('student');?></th>
                    <th><?php echo get_phrase('created_by');?></th>
                    <th><?php echo get_phrase('class');?></th>
                    <th><?php echo get_phrase('section');?></th>
                    <th><?php echo get_phrase('reason');?></th>
					<th><?php echo get_phrase('approved');?></th>
                    <th><?php echo get_phrase('date');?></th>
                    <th><?php echo get_phrase('priority');?></th>
                    <th class="text-center"><?php echo get_phrase('options');?></th>
                </tr>
            </thead>
            <tbody>
                <?php $reports = $this->db->get_where('reports', array('student_id' => $student_id ))->result_array();
                    foreach($reports as $row):
                ?>
                <tr>
                    <?php $user = $row['user_id'];
                        $re = explode('-', $user);
                    ?>
                    <td><img alt="" src="<?php echo $this->crud_model->get_image_url('student', $row['student_id']);?>" width="25px" style="border-radius: 10px;margin-right:5px;"> <?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name;?></td>
                    <td><img alt="" src="<?php echo $this->crud_model->get_image_url($re[0], $re[1]);?>" width="25px" style="border-radius: 10px;margin-right:5px;"> <?php echo $this->db->get_where($re[0], array($re[0]."_id" => $re[1]))->row()->name;?></td>
                    <td><a class="btn nc btn-rounded btn-sm btn-primary" style="color:white"><?php echo $this->db->get_where('class', array('class_id' => $row['class_id']))->row()->name;?></a></td>
                    <td><a class="btn nc btn-rounded btn-sm btn-purple" style="color:white"><?php echo $this->db->get_where('section', array('section_id' => $row['section_id']))->row()->name;?></a></td>
                    <td><a href="<?php echo base_url();?>teacher/view_report/<?php echo $row['code'];?>"><?php echo $row['title'];?></a></td>
					<td><?php if($row['approved'] == '0'):?>
                        <a class="btn nc btn-rounded btn-sm btn-danger" style="color:white"><?php echo get_phrase('no');?></a>
                    <?php endif;?>
                    <?php if($row['approved'] == '1'):?>
                        <a class="btn nc btn-rounded btn-sm btn-success" style="color:white"><?php echo get_phrase('yes');?></a>
                    <?php endif;?>	
                    <td><a class="btn nc btn-rounded btn-sm btn-success" style="color:white"><?php echo $row['date'];?></a></td>
                    <td><?php if($row['priority'] == 'alta'):?>
                        <a class="btn nc btn-rounded btn-sm btn-danger" style="color:white"><?php echo get_phrase('high');?></a>
                    <?php endif;?>
                    <?php if($row['priority'] == 'media'):?>
                        <a class="btn nc btn-rounded btn-sm btn-warning" style="color:white"><?php echo get_phrase('medium');?></a>
                    <?php endif;?>
                    <?php if($row['priority'] == 'baja'):?>
                        <a class="btn nc btn-rounded btn-sm btn-info" style="color:white"><?php echo get_phrase('low');?></a>
                        <?php endif;?></td>
                    <td class="row-actions">
                        <a href="<?php echo base_url();?>teacher/view_report/<?php echo $row['code'];?>/" class="btn btn-rounded btn-sm btn-primary" style="color:white"><i class="picons-thin-icon-thin-0043_eye_visibility_show_visible"></i> View</a>
                    </td>
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


