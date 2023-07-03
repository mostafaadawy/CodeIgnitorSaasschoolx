<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
  <script type="text/javascript">
    setInterval("my_function();",1500); 
    function my_function(){
      $('#chat_content').load(location.href + ' #chat');

    }
  </script>
  </head>
                    <div class="full-chat-middle">
                    <div class="chat-head">
                      <div class="user-info">
                     <?php $rece = $this->db->get_where('message_thread', array('message_thread_code' => $current_message_thread_code))->row()->reciever;
                            $re = explode('-', $rece);
                      ?>
                       <img alt="" src="<?php echo $this->crud_model->get_image_url($re[0], $re[1]); ?>" style="border-radius:25px;height:25px;"><span style="color:gray;font-size:16px;margin-left:10px;"> <?php echo $this->db->get_where($re[0], array($re[0] . '_id' => $re[1]))->row()->name; ?></span>
                      </div>
					  <div class="user-actions">
					  <a><i class="os-icon picons-thin-icon-thin-0315_email_mail_post_send"></i></a>
                      </div>
                    </div>
                    <div id="chat_content" class="chat-content-w">
                      <div id="chat" class="chat-content">
                      <?php
                        $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');
                        $messages = $this->db->get_where('message', array('message_thread_code' => $current_message_thread_code))->result_array();
                        foreach ($messages as $row):
                        $sender = explode('-', $row['sender']);
                        $sender_account_type = $sender[0];
                        $sender_id = $sender[1];
                    ?>
					<?php $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?>
                      <?php if($row['sender'] != $current_user):?>
                        <div class="chat-message">
                          <div class="chat-message-content-w">
                            <div class="chat-message-content">
                              <?php echo $row['message']; ?> 
                              <?php if($row['file_name'] != ""):?>
                                 <a class="btn btn-sm btn-rounded btn-success" href="<?php echo base_url().$subdomain;?>uploads/messages/<?php echo $row['file_name']; ?>" style="color:white"><i class="picons-thin-icon-thin-0121_download_file"></i>&nbsp;&nbsp;<?php echo get_phrase('download');?></a>
								 <?php if(strpos($row['file_name'] , 'jpg') !== false  || strpos($row['file_name'] , 'jpeg') !== false  || strpos($row['file_name'] , 'png') !== false  || strpos($row['file_name'] , 'tiff') !== false):?>
									 <div class="logo-wy">
									 <img alt="" width="50%" href="<?php echo base_url().$subdomain;?>uploads/messages/<?php echo $row['file_name']; ?>" src="<?php echo base_url().$subdomain;?>uploads/messages/<?php echo $row['file_name']; ?>">
									 </div>
								  <?php endif;?>
                              <?php endif;?>
                            </div>
                          </div>
                          <div class="chat-message-avatar">
                            <img alt="" src="<?php echo $this->crud_model->get_image_url($sender_account_type, $sender_id); ?>">
                          </div>
                        </div>
                        <?php endif;?>
                        <?php if($row['sender'] == $current_user):?>
                        <div class="chat-message self">
                          <div class="chat-message-content-w">
                            <div class="chat-message-content">
                              <?php echo $row['message']; ?>  <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" <?php if($row['read_status'] == 1):?> fill="#4fc3f7" <?php else:?> fill="#eee" <?php endif;?>/></svg></span>
                              <?php if($row['file_name'] != ""):?>
                                 <a class="btn btn-sm btn-rounded btn-success" href="<?php echo base_url().$subdomain;?>uploads/messages/<?php echo $row['file_name']; ?>" style="color:white"><i class="picons-thin-icon-thin-0121_download_file"></i>&nbsp;&nbsp;<?php echo get_phrase('download');?></a>
								 <div class="logo-wy">
								 <img alt="" width="50%" href="<?php echo base_url().$subdomain;?>uploads/messages/<?php echo $row['file_name']; ?>" src="<?php echo base_url().$subdomain;?>uploads/messages/<?php echo $row['file_name']; ?>">
								 </div>
                              <?php endif;?>
                            </div>
                          </div>
                          <div class="chat-message-avatar">
                            <img alt="" src="<?php echo $this->crud_model->get_image_url($sender_account_type, $sender_id); ?>">
                          </div>
                        </div>
                        <?php endif;?>
                        <?php endforeach;?>
                      </div>
                    </div>

                    <div class="chat-controls b-b">
                    <?php echo form_open(base_url() . 'parents/message/send_reply/' . $current_message_thread_code, array('enctype' => 'multipart/form-data')); ?>
                      <div class="chat-input">
                        <input placeholder="<?php echo get_phrase('write_message');?>..." required type="text" id="message" name="message">
                      </div>
                      <div class="chat-input-extra">
                       <div class="chat-extra-actions">
                           <input type="file" name="file_name" id="file-3" onclick="ToggleRequiredFunction()"  class="inputfile inputfile-3" style="display:none"/>
						<label for="file-3"><i class="os-icon picons-thin-icon-thin-0042_attachment" onclick="ToggleRequiredFunction()"></i> <span><?php echo get_phrase('send_file');?>...</span></label>
                        </div>
                        <div class="chat-btn">
                          <button class="btn btn-rounded btn-primary" type="submit"><?php echo get_phrase('send');?></button>
                        </div>
                      </div>
                      <?php echo form_close();?>
                    </div>
                  </div>

  <script>
  function ToggleRequiredFunction() {
    var x = document.getElementById("message");
     x.required =false;
} 
  </script>    
<script>
$(document).ready(function(){   
$('#chat_content').prop("scrollTop", $('#chat_content').prop("scrollHeight")); 
 });
</script>    