<?php 
$subdomain = $this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->row()->subdomain;
$query          = 'SELECT * FROM `clients` WHERE `client_subdomain`="'.$subdomain.'"';
$result         = $this->db->query($query);
$forums         = $result->row()->forums;
$accounting     = $result->row()->accounting;
$polls_purchase = $result->row()->polls;
$notifications  = $result->row()->notify;
$messaging      = $result->row()->messaging;
$exams          = $result->row()->exams;

?>


<?php $id = $this->session->userdata('login_user_id');?>
    <div class="content-w">
        <ul class="breadcrumb hidden-xs-down hidden-sm-down">
            <div class="logoutleft">
                <h6><?php echo $this->db->get_where('settings', array('type' => 'system_title'))->row()->description;?></h6>
            </div>
            <div class="logout"><a href="<?php echo base_url();?>login/logout"><span><?php echo get_phrase('logout');?></span><i class="os-icon picons-thin-icon-thin-0040_exit_logout_door_emergency_outside"></i></a></div>
        </ul>
          <div class="content-i">
            <div class="content-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="myCarousel" class="carousel slide m-b" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                    <li data-target="#myCarousel" data-slide-to="1"></li>
                                    <li data-target="#myCarousel" data-slide-to="2"></li>
                                </ol>
                    <div class="carousel-inner">
                      <div class="item active" id="slide1">
                        <img src="<?php echo base_url().$subdomain;?>uploads/slider/slider1.png" alt="Slider 1" style="width:100%; height:250px;">
                      </div>
                      <div class="item" id="slide2">
                        <img src="<?php echo base_url().$subdomain;?>uploads/slider/slider2.png" alt="Slider 2" style="width:100%; height:250px;">
                      </div>
                      <div class="item" id="slide3">
                        <img src="<?php echo base_url().$subdomain;?>uploads/slider/slider3.png" alt="Slider 3" style="width:100%; height:250px;">
                      </div>                      
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    </a>
                    </div>
                  </div>
                     <!-- <div class="row">
                        <div class="col-sm-3">
                          <div class="element-box infodash primary centered padded">
                          <div class="bg-icon">
                            <i class="os-icon picons-thin-icon-thin-0710_business_tie_user_profile_avatar_man_male"></i>
                          </div>
                            <div class="value">
                              <?php //echo $this->db->count_all_results('admin') - 1;?>
                            </div>
                            <div class="label"><?php //echo get_phrase('admins');?></div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="element-box infodash secondary centered padded">
                            <div class="bg-icon">
                            <i class="os-icon picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></i>
                            </div>
                            <div class="value">
                              <?php //echo $this->db->count_all_results('teacher');?>
                            </div>
                            <div class="label"><?php //echo get_phrase('teachers');?></div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="element-box infodash green centered padded">
                            <div class="bg-icon">
                            <i class="os-icon picons-thin-icon-thin-0729_student_degree_science_university_school_graduate"></i>
                            </div>
                            <div class="value">
                              <?php //echo $this->db->count_all_results('student');?>
                            </div>
                            <div class="label"><?php //echo get_phrase('students');?></div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="element-box infodash orange centered padded">
                            <div class="bg-icon">
                            <i class="os-icon picons-thin-icon-thin-0703_users_profile_group_two"></i>
                            </div>
                            <div class="value">
                              <?php //echo $this->db->count_all_results('parent');?>
                            </div>
                            <div class="label"><?php //echo get_phrase('parents');?></div>
                          </div>
                        </div>
                      </div> -->
                  </div>
                </div> 
				<?php 
				$users_dist[0] = $this->db->count_all_results('admin') - 1 ;
				$users_dist[1] = $this->db->count_all_results('teacher') ;
				$users_dist[2] = $this->db->count_all_results('parent')  ;
				$users_dist[3] = $this->db->count_all_results('student') ;
				?>
				   <div class="row m-t" id="contain">
						<div id="canvas-holder"  style="float: left;" class="col-sm-6">
								<canvas id="pie-chart"  width="400" height="450" style=" float:right;margin-left:2em" ></canvas>			
						</div>
						<div id="canvas-holder"  style="float: right;" class="col-sm-6">						
								  <canvas id="pie-chart-active"  width="400" height="450" style=" float:right;margin-left:2em"></canvas>
						</div>
					</div>
				
              <div class="row m-t">
              <div class="col-sm-12">
              <div class="element-wrapper">
               <div class="pipeline white lined-primary">
                <div class="pipeline-header"><h5 class="pipeline-name"> <?php echo get_phrase('noticeboard');?></h5></div>
                <div class="row">
                <?php 
                    $this->db->limit(3);
                    $this->db->order_by('news_id', 'desc');
                    $news = $this->db->get('news')->result_array();
                    foreach($news as $new):?>
                     <div class="col-sm-4">
                   <a href="<?php echo base_url();?>admin/read/<?php echo $new['news_code'];?>"> <div class="pipeline white lined-<?php if($new['type'] == 'event') echo 'primary'; else echo 'success';?>" style="padding:0px;-webkit-animation:none;">
                    <div style="margin:0px;padding:0px;top:0px;background-image:url(<?php echo base_url().$subdomain;?>uploads/news_images/<?php echo $new['news_code'];?>.jpg);background-size:cover;background-repeat:no-repeat;height:150px;"></div>
                      <div class="pipeline-header" style="margin-top:-7rem;padding:1.5rem;">
                        <h5 class="pipeline-name" style="background:rgba(0, 0, 0, 0.4);color:white"><?php echo $new['title'];?></h5>
                        <div class="pipeline-header-numbers" >
                          <div class="pipeline-count" style="background:rgba(0, 0, 0, 0.4);color:white">
                            <?php echo $new['date'];?>
                          </div>
                          <div class="text-right">
                            <?php if($new['type'] == "news"):?>
                                <a class="btn nc btn-round btn-sm btn-primary text-left" style="text-transform:uppercase;color:white;"><?php echo get_phrase('news');?></a>
                            <?php endif;?>
                            <?php if($new['type'] == "event"):?>
                                <a class="btn nc btn-round btn-sm btn-secondary text-left" style="text-transform:uppercase;color:white;"><?php echo get_phrase('event');?></a>
                            <?php endif;?>
                          </div>
                        </div>
                      </div>
                          <div style="padding:0 1.5rem 1.5rem 1.5rem;">
                            <p><?php echo substr($new['description'], 0, 70) . '...';?></p>
                          </div>
                        </div></a>
                        </div>
                <?php endforeach;?>
                    </div><div class="legendy"><span><a class="btn btn-rounded btn-sm btn-primary" style="text-transform:uppercase;color:white;" href="<?php echo base_url();?>admin/news/"><?php echo get_phrase('view_more');?></a></span></div>
                    </div>
                   </div>
                   </div>
        </div>      
        <div class="row">
                <div class="col-sm-6">
<div id="panel">
                  <div class="element-wrapper">
                <?php //echo form_open(base_url() . 'admin/polls/response/' , array('enctype' => 'multipart/form-data'));?>
        <?php 
          $polls = $this->db->get_where('polls', array('status' => 1))->result_array();
          foreach($polls as $row):
        ?>
        <?php if($row['user'] == 'admin' || $row['user'] == 'all'):?>
            <?php 
                $type = 'admin';
                $id = $this->session->userdata('login_user_id');
                $user = $type. "-".$id;
                $query = $this->db->get_where('poll_response', array('poll_code' => $row['poll_code'], 'user' => $user));
            ?>
            <?php if($query->num_rows() <= 0):?>
            <div class="pipeline white lined-warning">
              <div class="pipeline-header">
              <h5 class="pipeline-name"><?php echo $row['question'];?></h5>
           <div class="pipeline-header-numbers">
           <div class="pipeline-count"><?php echo $row['date'];?></div>
           <div class="text-right">
           <input type="hidden" name="poll_code" id="poll_code" value="<?php echo $row['poll_code'];?>">
            <a class="btn nc btn-round btn-sm btn-warning text-left" style="text-transform:uppercase;color:white;"><?php echo get_phrase('polls');?></a>
           </div>
           </div>
          </div>
            <div class="element-box-content example-content">
              <div class="col-sm-12">
              <?php 
                $array = ( explode(',' , $row['options']));
                for($i = 0 ; $i<count($array)-1; $i++):
              ?>
                  <div class="form-check">
                    <label class="form-check-label">
                    <input class="form-check-input" name="answer" id="answer" type="radio" value="<?php echo $array[$i];?>"><?php echo $array[$i];?></label>
                  </div>
                <?php endfor;?>
              </div>
              <div class="form-buttons-w">
                <button class="btn btn-primary" type="button" id="add"> <?php echo get_phrase('send');?></button>
              </div>        
            </div>
          </div>
            <?php endif;?>
            <?php if($query->num_rows() > 0):?>
          <div class="pipeline white lined-warning">
            <div class="pipeline-header">
              <h5 class="pipeline-name"><?php echo $row['question'];?></h5>
              <div class="pipeline-header-numbers">
              <div class="pipeline-count"><?php echo $row['date'];?></div>
              <div class="text-right">
                <a class="btn btn-round btn-sm btn-warning text-left" style="text-transform:uppercase;color:white;"><?php echo get_phrase('polls');?></a>
              </div>
           </div>
          </div>
        <div class="element-box-content example-content">
            <?php 
              $this->db->where('poll_code', $row['poll_code']);
              $polls = $this->db->count_all_results('poll_response');
              $array = ( explode(',' , $row['options']));
              $questions = count($array)-1;
              $op = 0;
              for($i = 0 ; $i<count($array)-1; $i++):
            ?>
            <div class="row">
            <?php 
            $this->db->group_by('poll_code');
            $po = $this->db->get_where('poll_response', array('poll_code' => $row['poll_code']))->result_array();
              foreach($po as $p):
            ?>
              <div class="col-sm-12">
                <div class="os-progress-bar">
                  <div class="bar-labels">
                    <div class="bar-label-left">
                    <?php 
                        $this->db->where('answer', $array[$i]);
                        $res = $this->db->count_all_results('poll_response');
                    ?>
                      <span><?php echo $array[$i];?></span>
                    </div>
                    <?php 
                      $response = $res/$polls;
                      $response2 = $response*100;
                    ?>
                    <div class="bar-label-right">
                      <span class="color-primary"><?php echo round($response2);?>/100%</span>
                    </div>
                  </div>         
                  <div class="progress">
            <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="33" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?php echo $response2;?>%"></div>
                  </div>
                </div>
                </div>
              <?php endforeach;?>
              </div>
              <?php endfor;?>
              </div>
            </div>
            <?php endif;?>
            <?php endif;?>
          <?php endforeach;?>
</div>
          <?php //echo form_close();?>
                </div>
            </div>
			
			<div class="col-sm-6">
                   <div class="element-wrapper">
                    <div class="pipeline white lined-success">
                        <div class="pipeline-header">
                            <h5 class="pipeline-name">
                              <?php echo get_phrase('online_users');?> <br><small>(<?php echo get_phrase('last_5_minutes');?>)</small>
                            </h5>
                          </div>
                          <?php 
                      session_start();
                      $session    = session_id();
                      $time       = time();
                      $time_check = $time-300;
                      $this->db->where('session', $session);
                      $count = $this->db->get('online_users')->num_rows();
                      if($count == 0)
                      { 
                        $data['time'] = $time;
                        $data['type'] = $this->session->userdata('login_type');
                        $data['id_usuario'] = $this->session->userdata('login_user_id');
                                                $data['gp'] = $this->session->userdata('login_user_id')."-".$this->session->userdata('login_type');
                        $data['session'] = $session;
                        $this->db->insert('online_users',$data);
                      }
                      else 
                      {
                        $data['session'] = $session;
                        $data['time'] = $time;
                                                $data['gp'] = $this->session->userdata('login_user_id')."-".$this->session->userdata('login_type');
                        $data['id_usuario'] = $this->session->userdata('login_user_id');
                        $data['type'] = $this->session->userdata('login_type');
                        $this->db->where('session', $session);
                        $this->db->update('online_users', $data);
                      }  
                      $this->db->where('time <', $time_check);
                      $this->db->delete('online_users');
                    ?>          
                        <div class="full-chat-w">
                        <div class="full-chat-middle">
                        <div class="chat-content-w min">
                        <div class="chat-content min">  
                            <div class="users-list-w">
                            <?php  
                                                                        $this->db->group_by('gp');
                                                                        $usuarios = $this->db->get('online_users')->result_array();
                                    foreach($usuarios as $row):
                                ?>
                                    <div class="user-w with-status min status-green">
                                        <div class="user-avatar-w min">
                                            <div class="user-avatar" >
                                                <img alt="" src="<?php echo $this->crud_model->get_image_url($row['type'], $row['id_usuario']);?>">
                                            </div>
                                        </div>
                                        <div class="user-name">
                                            <h6 class="user-title min"><?php echo $this->db->get_where($row['type'], array($row['type']."_id" => $row['id_usuario']))->row()->name;?></h6>
                                            <div class="user-role min">
                                                <?php if($row['type'] == 'student'):?>
                                                    <a class="btn nc btn-sm btn-rounded btn-secondary" href="#"><?php echo get_phrase('student'); $onlineusers[3] = $onlineusers[3] +1;?></a>
                                                <?php endif;?>
                                                <?php if($row['type'] == 'parent'):?>
                                                    <a class="btn nc btn-sm btn-rounded btn-purple" href="#"><?php echo get_phrase('parent'); $onlineusers[2] = $onlineusers[2] +1;?></a>
                                                <?php endif;?>
                                                <?php if($row['type'] == 'admin'):?>
                                                    <a class="btn nc btn-sm btn-rounded btn-primary" href="#"><?php echo get_phrase('admin'); $onlineusers[0] = $onlineusers[0] +1;?></a>
                                                <?php endif;?>
                                                <?php if($row['type'] == 'teacher'):?>
                                                    <a class="btn nc btn-sm btn-rounded btn-success" href="#"><?php echo get_phrase('teacher'); $onlineusers[1] = $onlineusers[1] +1;?></a>
                                                <?php endif;?>
                                            </div>
                                        </div>            
                                        <div class="user-action min">
                                            <?php $data = $row['type'] ."-".$row['id_usuario'];
                                                  $send_data = base64_encode($data);
                                            ?>
											<?php if($messaging==1):?>
                                            <a href="<?php echo base_url();?>admin/message/message_new/<?php echo $send_data;?>"><i class="os-icon picons-thin-icon-thin-0319_email_mail_post_card" style="font-weight:bold"></i></a>
											<?php endif;?>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
              </div> 
            </div>
			
			
			
             
              </div>
			  <div class="row">
			    <?php if($this->db->get_where('settings', array('type' => 'register'))->row()->description == 1):?>     
              <div class="col-sm-12">
                  <div class="element-wrapper">
                    <div class="pipeline white lined-secondary">
          <div class="pipeline-header">
                <h5 class="pipeline-name"><?php echo get_phrase('pending_users');?></h5>
          </div>
          <div class="full-chat-w">
            <div class="full-chat-middle">
            <div class="chat-content-w min">
            <div class="chat-content min">
          <div class="users-list-w">
          <?php $pending_users = $this->db->get('pending_users')->result_array();
            foreach($pending_users as $row):
          ?>
            <div class="user-w">
              <div class="user-avatar-w">
                <div class="user-avatar">
                  <img alt="" src="<?php echo base_url().$subdomain;?>uploads/user.jpg">
                </div>
              </div>
              <div class="user-name">
                <h6 class="user-title"><?php echo $row['name'];?></h6>
                <div class="user-role">
                    <?php if($row['type'] == 'student'):?>
                        <a class="btn nc btn-sm btn-rounded btn-secondary" href="<?php echo base_url();?>admin/admissions/"><?php echo get_phrase('student');?></a>
                    <?php endif;?>
                    <?php if($row['type'] == 'parent'):?>
                        <a class="btn nc btn-sm btn-rounded btn-purple" href="<?php echo base_url();?>admin/admissions/"><?php echo get_phrase('parent');?></a>
                    <?php endif;?>
                    <?php if($row['type'] == 'teacher'):?>
                        <a class="btn nc btn-sm btn-rounded btn-success" href="<?php echo base_url();?>admin/admissions/"><?php echo get_phrase('teacher');?></a>
                    <?php endif;?>
                </div>
              </div>              
            <div class="user-action min">
                <a href="<?php echo base_url();?>admin/admissions/"><i class="os-icon picons-thin-icon-thin-0701_user_profile_avatar_man_male" style="font-weight:bold"></i></a>
              </div>
            </div>
        <?php endforeach;?>
                </div>
                </div>
                </div>
                </div>
          </div>
        </div>
                  </div>
                </div>
				<?php endif;?>
			  </div>
          </div>
          </div>          
          <div class="footy padded">
            <div class="schoollogo">
              <img alt="" src="<?php echo base_url().$subdomain;?>uploads/logo.png"><span><?php echo $this->db->get_where('settings', array('type' => 'system_title'))->row()->description;?></span>
            </div>
            <div class="schoolinfo">
              <span><?php echo $this->db->get_where('settings', array('type' => 'system_email'))->row()->description;?></span><span><?php echo $this->db->get_where('settings', array('type' => 'phone'))->row()->description;?></span><a href="<?php echo $this->db->get_where('settings', array('type' => 'facebook'))->row()->description;?>" target="_blank"><i class="picons-social-icon-facebook"></i></a><a href="<?php echo $this->db->get_where('settings', array('type' => 'twitter'))->row()->description;?>" target="_blank"><i class="picons-social-icon-twitter"></i></a><a href="<?php echo $this->db->get_where('settings', array('type' => 'instagram'))->row()->description;?>" target="_blank"><i class="picons-social-icon-instagram"></i></a><a href="<?php echo $this->db->get_where('settings', array('type' => 'youtube'))->row()->description;?>" target="_blank"><i class="picons-social-icon-youtube"></i></a>
            </div>
          </div>
          </div>


<script>
    var post_message        =   '<?php echo get_phrase('thank_you_polls');?>';
    $(document).ready(function()
    {
      $("#add").click(function()
      {
        answer = $('input[name=answer]:checked').val();
        poll_code= $("#poll_code").val();
        if(answer!="" && poll_code!="")
        {
            $.ajax({url:"<?php echo base_url();?>admin/polls/response/",type:'POST',data:{answer:answer,poll_code:poll_code},success:function(result)
            {
                 $('#panel').load(document.URL + ' #panel');
                 $("#message").val('');
                 toastr.success(post_message, "Success");
            }});
        }
      });
    });
</script>

<script>
window.onload = function () {
	var ctx = document.getElementById("pie-chart");
	var ctx_active = document.getElementById("pie-chart-active");
	
new Chart(ctx, {
    type: 'doughnut',
	responsive: true,
         maintainAspectRatio: false,
    data: {
      labels: ["Adminstrators", "Teachers", "Parents", "Students"],
      datasets: [{
        label: "Number of Active Users",
        backgroundColor: ["#2e99b0", "#fcd77f","#ff2e4c","#1e1548"],
        data: [<?php echo $users_dist[0];?> , <?php echo $users_dist[1];?> ,  <?php echo $users_dist[2];?>, <?php echo $users_dist[3];?>]
      }]
    },
    options: {

      title: {
        display: true,
		fontColor : "#0e3150",
		fontSize  :   32 ,
        text: 'SchoolX users distribution'
		
      }
    }
});

new Chart(ctx_active, {
    type: 'pie',
	responsive: true,
         maintainAspectRatio: false,
    data: {
      labels: ["Adminstrators", "Teachers", "Parents", "Students"],
      datasets: [{
        label: "Number of Active Users",
        backgroundColor: ["#394a51", "#78fee0","#0960bd","#ff6107"],
        data: [<?php echo $onlineusers[0];?> , <?php echo $onlineusers[1];?> ,  <?php echo $onlineusers[2];?>, <?php echo $onlineusers[3];?>]
      }]
    },
    options: {

      title: {
        display: true,
		fontColor : "#0e3150",
		fontSize  :   32 ,
        text: 'Online users distribution (Last 5Mins)'
		
      }
    }
});

 
}
</script>
