<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$data = explode('.',$_SERVER['SERVER_NAME']); // Get the sub-domain here
class Admin extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    if (!empty($data[0]))
			{
               $subdomain = $data[0]; // The * of *.mydummyapp.com will be now stored in $subdomain 
               $this->load->database();
		    }
	    else
		    {
			  $this->load->database();
			}
    $this->load->model('users_model');			
    $this->load->library('session');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache'); 
    $this->load->model('Calendar_model');	
	$this->load->helper('date');
  }

    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($this->session->userdata('admin_login') == 1)
        {
            redirect(base_url() . 'admin/panel/', 'refresh');
        }
    }
    
    function query() 
    {
        if($_POST['b'] != "")
        {       
            $this->db->like('name' , $_POST['b']);
            $query = $this->db->get_where('student')->result_array();
            if(count($query) > 0)
            {
                foreach ($query as $row) 
                {
                    echo '<p style="text-align: left; color:#fff; font-size:14px;"><a style="text-align: left; color:#fff; font-weight: bold;" href="'.base_url().'admin/student_portal/'. $row['student_id'] .'/">'. $row['name'] .'</a>' ." &nbsp;".$status.""."</p>";
                }
            } else{
                echo '<p class="col-md-12" style="text-align: left; color: #fff; font-weight: bold; ">No results.</p>';
            }
        }
    }

     function group($param1 = "group_message_home", $param2 = "")
     {
	  $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
      if ($this->session->userdata('admin_login') != 1)
      {
          redirect(base_url(), 'refresh');
      }
      $max_size = 2097152;
      if ($param1 == "create_group") 
      {
        $this->crud_model->create_group();
        $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
        redirect(base_url() . 'admin/group/', 'refresh');
      }
      elseif($param1 == "delete_group")
      {
        $this->db->where('group_message_thread_code', $param2);
        $this->db->delete('group_message');
        $this->db->where('group_message_thread_code', $param2);
        $this->db->delete('group_message_thread');
        $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
        redirect(base_url() . 'admin/group/', 'refresh');
      }
      elseif ($param1 == "edit_group") 
      {
        $this->crud_model->update_group($param2);
        $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
        redirect(base_url() . 'admin/group/', 'refresh');
      }
      elseif ($param1 == 'group_message_read') 
      {
        $page_data['current_message_thread_code'] = $param2;
      }
      else if($param1 == 'send_reply')
      {
        if(!file_exists($subdomain.'uploads/group_messaging_attached_file/')) 
        {
          $oldmask = umask(0);
          mkdir ($subdomain.'uploads/group_messaging_attached_file/', 0777);
        }
            if ($_FILES['attached_file_on_messaging']['name'] != "") 
            {
                if($_FILES['attached_file_on_messaging']['size'] > $max_size)
                {
                    $this->session->set_flashdata('error_message' , "2MB Allowed");
                    redirect(base_url() . 'admin/group/group_message_read/'.$param2, 'refresh');
                }
                else{
                    $file_path = $subdomain.'uploads/group_messaging_attached_file/'.$_FILES['attached_file_on_messaging']['name'];
                    move_uploaded_file($_FILES['attached_file_on_messaging']['tmp_name'], $file_path);
                }
            }

            $this->crud_model->send_reply_group_message($param2);
            $this->session->set_flashdata('flash_message', get_phrase('message_sent'));
            redirect(base_url() . 'admin/group/group_message_read/'.$param2, 'refresh');
        }

        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'group';
        $page_data['page_title']                = get_phrase('message_group');
        $this->load->view('backend/index', $page_data);
    }

    function grade($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(site_url('login'), 'refresh');
        }
        if ($param1 == 'create') 
        {
			$subjects        = $this->input->post('subject_id');
			$subjects_number = sizeof($subjects);
            for($i = 0; $i < $subjects_number; $i++) 
            {
				$data['class_id']        = $this->input->post('class_id');
				$data['subject_id'] = $subjects[$i];
				$data['name']        = $this->input->post('name');
                $data['grade_point'] = $this->input->post('point');
                $data['mark_from']   = $this->input->post('from');
                $data['mark_upto']   = $this->input->post('to');
				$this->db->insert('grade', $data);
			}
           
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/grade/', 'refresh');
        }
        if ($param1 == 'update') 
        {
			$data['class_id']    = $this->input->post('class_id');
			$data['subject_id']  = $this->input->post('subject_id');
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('point');
            $data['mark_from']   = $this->input->post('from');
            $data['mark_upto']   = $this->input->post('to');
            $this->db->where('grade_id', $param2);
            $this->db->update('grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/grade/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('grade_id', $param2);
            $this->db->delete('grade');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/grade/', 'refresh');
        }
        $page_data['page_name']  = 'grade';
        $page_data['page_title'] = get_phrase('grades');
        $this->load->view('backend/index', $page_data);
    }

    function message($param1 = 'message_home', $param2 = '', $param3 = '') 
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'send_new') 
        {
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            $message_thread_code = $this->crud_model->send_new_private_message();
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            redirect(base_url() . 'admin/message/message_read/' . $message_thread_code, 'location');
        }
        if ($param1 == 'send_reply') 
        {
            $this->session->set_flashdata('flash_message' , get_phrase('reply_sent'));
            $this->crud_model->send_reply_message($param2);
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            redirect(base_url() . 'admin/message/message_read/' . $param2, 'location');
        }
        if ($param1 == 'message_read') 
        {
            $page_data['current_message_thread_code'] = $param2; 
            $this->crud_model->mark_thread_messages_read($param2);
        }
        $page_data['infouser'] = $param2;
        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'message';
        $page_data['page_title']                = get_phrase('private_messages');
        $this->load->view('backend/index', $page_data);
    }

    function admins($param1 = '' , $param2 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
			$username = $this->input->post('username');
			if($this->users_model->validate_username($username))
			{
				$data['name']         = $this->input->post('name');
				$data['username']     = $this->input->post('username');
				$data['email']        = $this->input->post('email');
				$data['birthday']     = $this->input->post('birthday');
				$data['phone']     = $this->input->post('phone');
				$data['address']     = $this->input->post('address');
				$data['password']     = sha1($this->input->post('password'));
				$data['owner_status'] = $this->input->post('type');
                $data['subdomain'] = $subdomain;
				$data['messages'] = $this->input->post('messages');
				$data['notify'] = $this->input->post('notify');
				$data['information'] = $this->input->post('info');
				$data['marks'] = $this->input->post('marks');
				$data['academic'] = $this->input->post('academic');
				$data['attendance'] = $this->input->post('attendance');
				$data['schedules'] = $this->input->post('schedules');
				$data['news'] = $this->input->post('noticeboard');
				$data['library'] = $this->input->post('library');
				$data['be'] = $this->input->post('behavior');
				$data['acc'] = $this->input->post('accounting');
				$data['class'] = $this->input->post('classrooms');
				$data['school'] = $this->input->post('school_bus');
				$data['polls'] = $this->input->post('polls');
				$data['settings'] = $this->input->post('system_settings');
				$data['academic_se'] = $this->input->post('academic_settings');
				$data['files'] = $this->input->post('teacher_files');
				$data['users'] = $this->input->post('users');
				$this->db->insert('admin', $data);
				$admin_id = $this->db->insert_id();
				move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/admin_image/' . $admin_id . '.jpg');
				$this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
				
			}
			else
			{
			  $this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');	
			}
			redirect(base_url() . 'admin/admins/', 'refresh');
        }
        if ($param1 == 'edit') 
        {
            if($this->crud_model->admin_edit($param2))
			{
               $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
			}
			else
			{
				 $this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
			}
            redirect(base_url() . 'admin/admin_profile/'.$param2 ."/", 'refresh');
        }
        if ($param1 == 'delete')
        {
            $this->db->where('admin_id', $param2);
            $this->db->delete('admin');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/admins/', 'refresh');
        }
        $page_data['page_name']     = 'admins';
        $page_data['page_title']    = get_phrase('admins');
        $this->load->view('backend/index', $page_data);
    }

    function students($id)
    {
      if ($this->session->userdata('admin_login') != 1)
      {
        redirect('login', 'refresh');
      }
      $id = $this->input->post('class_id');
      if ($id == '')
      {
        $id = $this->db->get('class')->first_row()->class_id;
      }
      $page_data['page_name']   = 'students';
      $page_data['page_title']  = get_phrase('students');
      $page_data['id']  = $id;
      $this->load->view('backend/index', $page_data);
    }

    function admin_profile($admin_id)
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'admin_profile';
        $page_data['page_title'] =  get_phrase('profile');
        $page_data['admin_id']  =  $admin_id;
        $this->load->view('backend/index', $page_data);
    }
    
    function teachers($param1 = '', $param2 = '', $param3 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
			$username = $this->input->post('username');
			if($this->users_model->validate_username($username))
			{
				$data['name']        = $this->input->post('name');
				$data['username']    = $this->input->post('username');
				$data['salary']      = $this->input->post('salary');
				$data['sex']         = $this->input->post('sex');
				$data['address']     = $this->input->post('address');
				$data['phone']       = $this->input->post('phone');
				$data['email']       = $this->input->post('email');
				$data['birthday']    = $this->input->post('birthday');
				$data['password']    = sha1($this->input->post('password'));
				$data['subdomain']   = $subdomain;
				$this->db->insert('teacher', $data);
				$teacher_id = $this->db->insert_id();
				move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/teacher_image/' . $teacher_id . '.jpg');
				$this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
				redirect(base_url() . 'admin/teachers/', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
				redirect(base_url() . 'admin/teachers/', 'refresh');
			}
        }
		if ($param1 == 'update') 
        {
            if($this->crud_model->teacher_edit($param2) == true)
			{
               $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated').$param2 . $this->crud_model->teacher_edit($param2) );
			}
			else
			{
				 $this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
			}
            redirect(base_url() . 'admin/teacher_profile/'. $param2, 'refresh');
        }
        
        if($param1 == 'accept')
        {
			
            $pending = $this->db->get_where('pending_users', array('user_id' => $param2))->result_array();
            foreach ($pending as $row) 
            {
				$username = $row['username'];
				if($this->users_model->validate_username($username))
				{
					$data['name'] = $row['name'];
					$data['email'] = $row['email'];
					$data['username'] = $row['username'];
					$data['sex'] = $row['sex'];
					$data['address'] = $row['address'];
					$data['password'] = $row['password'];
					$data['phone'] = $row['phone'];
					$data['subdomain']   = $subdomain;
					
					$this->db->insert('teacher', $data);
					$teacher_id = $this->db->insert_id();
					$this->crud_model->account_confirm('teacher', $teacher_id);
				}
            }
			if($this->users_model->validate_username($username))
				{
					$this->db->where('user_id', $param2);
					$this->db->delete('pending_users');
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
				}
				else
				{
					$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
				}
            redirect(base_url() . 'admin/teachers/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('teacher_id', $param2);
            $this->db->delete('teacher');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/teachers/', 'refresh');
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teachers';
        $page_data['page_title'] = get_phrase('teachers');
        $this->load->view('backend/index', $page_data);
    }
    
    function teacher_profile($teacher_id)
    {
        if ($this->session->userdata('admin_login') != 1) 
        {            
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'teacher_profile';
        $page_data['page_title'] =  get_phrase('profile');
        $page_data['teacher_id']  =  $teacher_id;
        $this->load->view('backend/index', $page_data);
    }
    
    function parents($param1 = '', $param2 = '', $param3 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
           redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
			$username = $this->input->post('username');
				if($this->users_model->validate_username($username))
				{
					$data['name']             = $this->input->post('name');
					$data['username']             = $this->input->post('username');
					$data['email']            = $this->input->post('email');
					$data['password']         = sha1($this->input->post('password'));
					$data['phone']            = $this->input->post('phone');
					$data['address']          = $this->input->post('address');
					$data['profession']       = $this->input->post('profession');
					$data['subdomain']        = $subdomain;
					$this->db->insert('parent', $data);
					$parent_id     =   $this->db->insert_id();
					move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/parent_image/' . $parent_id . '.jpg');
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
				}
				else
				{
					$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
				}
            redirect(base_url() . 'admin/parents/', 'refresh');
        }
        if ($param1 == 'update') 
        {
			$username = $this->db->get_where('parent' , array('parent_id' => $param2))->row()->username;
			$new_username = $this->input->post('username');
			if($username == $new_username || $this->users_model->validate_username($new_username))
			   {
					$data['name']                   = $this->input->post('name');
					$data['username']               = $this->input->post('username');
					$data['email']                  = $this->input->post('email');
					$data['phone']                  = $this->input->post('phone');
					$data['address']                = $this->input->post('address');
					$data['profession']             = $this->input->post('profession');
					$data['subdomain']              = $subdomain;
					if($this->input->post('password') != ""){
						$data['password'] = sha1($this->input->post('password'));
					}
					$this->db->where('parent_id' , $param2);
					$this->db->update('parent' , $data);
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
					move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/parent_image/' . $param2 . '.jpg');
				}
				else
				{
					$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
				}
            redirect(base_url() . 'admin/parent_profile/'.$param2."/", 'refresh');
        }
        if($param1 == 'accept')
        {
            $pending = $this->db->get_where('pending_users', array('user_id' => $param2))->result_array();
            foreach ($pending as $row) 
            {
				$username = $row['username'];
				if($this->users_model->validate_username($username))
				{
					$data['name'] = $row['name'];
					$data['email'] = $row['email'];
					$data['username'] = $row['username'];
					$data['profession'] = $row['profession'];
					$data['address'] = $row['address'];
					$data['password'] = $row['password'];
					$data['phone'] = $row['phone'];
					$data['subdomain']   = $subdomain;
					$this->db->insert('parent', $data);
					$parent_id = $this->db->insert_id();
					$this->crud_model->account_confirm('parent', $parent_id);
				}
            }
			if($this->users_model->validate_username($username))
				{
					$this->db->where('user_id', $param2);
					$this->db->delete('pending_users');
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
				}
			else
				{
					$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
				}	
            redirect(base_url() . 'admin/parents/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('parent_id' , $param2);
            $this->db->delete('parent');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/parents/', 'refresh');
        }
        $page_data['page_title']  = get_phrase('parents');
        $page_data['page_name']  = 'parents';
        $this->load->view('backend/index', $page_data);
    }

    function marks()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'marks';
        $page_data['page_title'] = get_phrase('marks');
        $this->load->view('backend/index', $page_data);
    }

    function notify($param1 = '', $param2 = '')
    {
      if ($this->session->userdata('admin_login') != 1)
      {
          redirect(base_url(), 'refresh');
      }
      if($param1 == 'markssms')
      {        
         $sms_status = $this->db->get_where('settings' , array('type' => 'sms_status'))->row()->description; 
         $year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
         require_once 'smsGateway.php';
         $email = $this->db->get_where('settings' , array('type' => 'android_email'))->row()->description;
         $pass   = $this->db->get_where('settings' , array('type' => 'android_password'))->row()->description;    
         $device   = $this->db->get_where('settings' , array('type' => 'android_device'))->row()->description;        
         $object = new SmsGateway($email, $pass);
         $class_id   =   $this->input->post('class_id');
         $receiver   =   $this->input->post('receiver');
         $students = $this->db->get_where('enroll' , array('class_id' => $class_id, 'year' => $year))->result_array();
         $nums = array();
         $message = $this->input->post('message');
         foreach ($students as $row) 
         {
                if ($receiver == 'student')
                {
                    $phones = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->phone;
                }
                if ($receiver == 'parent') 
                {
                    $parent_id =  $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->parent_id;
                    $phones = $this->db->get_where('parent' , array('parent_id' => $parent_id))->row()->phone;
                }
                array_push($nums, $phones);
                if ($sms_status == 'twilio') 
                {
                  $this->crud_model->twilio("".$message."","".$phones."");
                }else if ($sms_status == 'clickatell') 
                {
                  $this->crud_model->clickatell($message,$phones);
                }  
          }
          if ($sms_status == 'android') 
          {
             $result = $object->sendMessageToManyNumbers($nums, $message, $device);
          }
            $this->session->set_flashdata('flash_message' , get_phrase('sent_successfully'));
          redirect(base_url() . 'admin/notify/', 'refresh');
      }
      if($param1 == 'bulkemail')
      {
          $this->crud_model->bulk_email($this->input->post('type'), $this->input->post('subject'), $this->input->post('message'));
            $this->session->set_flashdata('flash_message' , get_phrase('sent_successfully'));
          redirect(base_url() . 'admin/notify/', 'refresh');
      }
      $page_data['page_name']  = 'notify';
      $page_data['page_title'] = get_phrase('notifications');
      $this->load->view('backend/index', $page_data);
    }

    function parent_profile($parent_id)
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['parent_id']  = $parent_id;
        $page_data['page_name']  = 'parent_profile';
        $page_data['page_title'] = get_phrase('profile');
        $this->load->view('backend/index', $page_data);
    }
    
    function delete_student($student_id, $class_id) 
    {
      $tables = array('student', 'attendance', 'enroll', 'invoice', 'mark', 'payment', 'students_request', 'student_question', 'reporte_alumnos');
      $this->db->delete($tables, array('student_id' => $student_id));
      $threads = $this->db->get('message_thread')->result_array();
      if (count($threads) > 0) 
      {
        foreach ($threads as $row) 
        {
          $sender = explode('-', $row['sender']);
          $receiver = explode('-', $row['reciever']);
          if (($sender[0] == 'student' && $sender[1] == $student_id) || ($receiver[0] == 'student' && $receiver[1] == $student_id)) 
          {
            $thread_code = $row['message_thread_code'];
            $this->db->delete('message', array('message_thread_code' => $thread_code));
            $this->db->delete('message_thread', array('message_thread_code' => $thread_code));
          }
        }
      }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
      redirect(base_url() . 'admin/students_area/', 'refresh');
    }
    
	function delete_multiple_students() 
    {
		
      $tables = array('student', 'attendance', 'enroll', 'invoice', 'mark', 'payment', 'students_request', 'student_question', 'reporte_alumnos');
	   $ids = explode(",", $this->input->post('ids'));
	   foreach ($ids as $student_id)
				{
				  $this->db->delete($tables, array('student_id' => $student_id));
				  $threads = $this->db->get('message_thread')->result_array();
				  if (count($threads) > 0) 
				  {
					foreach ($threads as $row) 
					{
					  $sender = explode('-', $row['sender']);
					  $receiver = explode('-', $row['reciever']);
					  if (($sender[0] == 'student' && $sender[1] == $student_id) || ($receiver[0] == 'student' && $receiver[1] == $student_id)) 
					  {
						$thread_code = $row['message_thread_code'];
						$this->db->delete('message', array('message_thread_code' => $thread_code));
						$this->db->delete('message_thread', array('message_thread_code' => $thread_code));
					  }
					}
				  }
				}
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
      redirect(base_url() . 'admin/students_area/', 'refresh');
    }
	
	
	function delete_multiple_teachers() 
    {
		
      
	   $ids = explode(",", $this->input->post('ids'));
	   foreach ($ids as $teacher_id)
				{
					$this->db->where('teacher_id', $teacher_id);
                    $this->db->delete('teacher');
				}
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/teachers/', 'refresh');
    }
	
	
	
	
	function delete_multiple_parents() 
    {
		
	   $ids = explode(",", $this->input->post('ids'));
	   
	   
	  foreach ($ids as $parent_id)
				{
					$this->db->where('parent_id' , $parent_id);
                    $this->db->delete('parent');
				}
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
      redirect(base_url() . 'admin/parents/', 'refresh');
    }
	
	
	
    function attendance_selector()
    {
        $data['class_id']   = $this->input->post('class_id');
        $data['year']       = $this->input->post('year');
        $originalDate =$this->input->post('timestamp');
		$originalDate = str_replace('/','-',$originalDate);
        $newDate = date("d-m-Y", strtotime($originalDate));
        $data['timestamp']  = strtotime($newDate);
        $data['section_id'] = $this->input->post('section_id');
            $query = $this->db->get_where('attendance' ,array(
                'class_id'=>$data['class_id'],
                    'section_id'=>$data['section_id'],
                        'year'=>$data['year'],
                            'timestamp'=>$data['timestamp']));
        if($query->num_rows() < 1) 
        {
            $students = $this->db->get_where('enroll' , array('class_id' => $data['class_id'] , 'section_id' => $data['section_id'] , 'year' => $data['year']))->result_array();
            foreach($students as $row) {
                $attn_data['class_id']   = $data['class_id'];
                $attn_data['year']       = $data['year'];
                $attn_data['timestamp']  = $data['timestamp'];
                $attn_data['section_id'] = $data['section_id'];
                $attn_data['student_id'] = $row['student_id'];
                $this->db->insert('attendance' , $attn_data);  
            }
        }
        redirect(base_url().'admin/manage_attendance/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['timestamp'],'refresh');
    }
    
    function attendance_update($class_id = '' , $section_id = '' , $timestamp = '')
    {        
        require_once 'smsGateway.php';
        $email = $this->db->get_where('settings' , array('type' => 'android_email'))->row()->description;
        $pass   = $this->db->get_where('settings' , array('type' => 'android_password'))->row()->description;    
        $device   = $this->db->get_where('settings' , array('type' => 'android_device'))->row()->description;    
        $object = new SmsGateway($email, $pass);

        $sms_status = $this->db->get_where('settings' , array('type' => 'sms_status'))->row()->description;

        $notify = $this->db->get_where('settings' , array('type' => 'absences'))->row()->description;

        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $attendance_of_students = $this->db->get_where('attendance' , array('class_id'=>$class_id,'section_id'=>$section_id,'year'=>$running_year,'timestamp'=>$timestamp))->result_array();
        foreach($attendance_of_students as $row) 
        {
            $attendance_status = $this->input->post('status_'.$row['attendance_id']);
            $this->db->where('attendance_id' , $row['attendance_id']);
            $this->db->update('attendance' , array('status' => $attendance_status));
            if ($attendance_status == 2) 
            {
                $student_name   = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
                $parent_id      = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->parent_id;
                $parent_em      = $this->db->get_where('parent' , array('parent_id' => $parent_id))->row()->email;
                $receiver       = $this->db->get_where('parent' , array('parent_id' => $parent_id))->row()->phone;
                $message        = 'Su hijo' . ' ' . $student_name . ' esta ausente el día de hoy.';
               if($notify == 1)
               {
                if ($sms_status == 'android') 
                {
                    $result = $object->sendMessageToNumber($receiver, $message, $device);
                }
                else if ($sms_status == 'twilio') 
                {
                     $this->crud_model->twilio($message,"".$receiver."");
                }
                else if ($sms_status == 'clickatell') 
                {
                    $this->crud_model->clickatell($message,$receiver);
                }
              }
              $this->crud_model->attendance($student_name, "".$parent_em."");
            }
        }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
        redirect(base_url().'admin/manage_attendance/'.$class_id.'/'.$section_id.'/'.$timestamp , 'refresh');
    }

    function update_news($code)
    {
        if($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['code'] = $code;
        $page_data['page_name'] = 'update_news';
        $page_data['page_title'] = get_phrase('update_news');
        $this->load->view('backend/index', $page_data);
    }

    function database($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'restore')
        {
            $this->crud_model->import_db();
            $this->session->set_flashdata('flash_message' , get_phrase('restored'));
            redirect(base_url() . 'admin/database/', 'refresh');
        }
        if($param1 == 'create')
        {
            $this->crud_model->create_backup();
            $this->session->set_flashdata('flash_message' , get_phrase('backup_created'));
            redirect(base_url() . 'admin/database/', 'refresh');
        }
        $page_data['page_name']                 = 'database';
        $page_data['page_title']                = get_phrase('database');
        $this->load->view('backend/index', $page_data);
    }

    function panel()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'panel';
        $page_data['page_title'] = get_phrase('dashboard');
        $this->load->view('backend/index', $page_data);
    }
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 function calendar()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'calendar';
        $page_data['page_title'] = get_phrase('academic_calendar');
        $this->load->view('backend/index', $page_data);
    }
	
	 function c_get_events($start, $end) 
    {
        return $this->db->get('calendar_events');
           // ->where('start >=', $start)
           // ->where('end <=', $end)
           // ->get('calendar_events');
    }
	
	function calendar_get_events() 
    {
        // Our Stand and End Dates
		$start = ($_POST["start"]);
        $end = ($_POST["end"]);
        

        $startdt = new DateTime('now'); // setup a local datetime
        $startdt->setTimestamp($start); // Set the date based on timestamp
        $format = $startdt->format('d/m/Y H:i:s');

        $enddt = new DateTime('now'); // setup a local datetime
        $enddt->setTimestamp($end); // Set the date based on timestamp
        $format2 = $enddt->format('d/m/Y H:i:s');
        $events =  $this->c_get_events($format, $format2);

        $data_events = array();

        foreach($events->result() as $r) { 

            $data_events[] = array(
                'id' => $r->ID,
                'title' => $r->title,
                'description' => $r->description,
                'end' => $r->end,
                'start' => $r->start,
				'color' => $r->eventcolor
            );
        }
       
        echo json_encode(array('events' => $data_events));
        exit();
    }
	
   function calendar_add_event() 
    {
		
        /* Our calendar data */
		$name = ($_POST["name"]);
		$desc = ($_POST["description"]);
		$start_date = ($_POST["start_date"]);
		$end_date = ($_POST["end_date"]);
		$event_color = ($_POST["event_color"]);


        if(!empty($start_date)) {
			

			$start_date = $start_date;
        } else {
            $start_date = date("d/m/Y H:i:s", time());
        }

        if(!empty($end_date)) {
			
			$end_date = $end_date;
            
        } else {
            $end_date = date("d/m/Y H:i:s", time());
          
        }
		  $data = array(
            'title' => $name,
            'description' => $desc,
            'start' => $start_date,
            'end' => $end_date,
			'eventColor' => $event_color
            );
		   $this->db->insert('calendar_events', $data);

       $account_type		=	$this->session->userdata('login_type');
		redirect(base_url() .$account_type .'/calendar/', 'refresh');
    }
	
	function calendar_edit_event() 
    {
        $eventid = intval($_POST["eventid"]); 
		$event   =  $this->db->where('ID', $eventid)->get('calendar_events');
        if($event->num_rows() == 0) {
            echo"Invalid Event";
			echo $eventid;
            exit();
        }

        //$event->row();

        /* Our calendar data */
        $name = ($_POST["name"]); 
        $desc = ($_POST["description"]); 
        $start_date = ($_POST["start_date"]);
        $end_date = ($_POST["end_date"]);
        $delete = intval($_POST["delete"]);
		$deleteme = ($_POST["delete"]);
		$event_color = ($_POST["event_color"]);

        if(!$deleteme) {

            if(!empty($start_date)) {
                $start_date = $start_date;
            } else {
                $start_date = date("d/m/Y H:i:s", time());
                $start_date_timestamp = time();
            }

            if(!empty($end_date)) {
                $end_date = $end_date;
            } else {
                $end_date = date("d/m/Y H:i:s", time());
                $end_date_timestamp = time();
            }
			
			$data = array("title" => $name,"description" => $desc,"start" => $start_date,"end" => $end_date, 'eventcolor' => $event_color);
            $this->db->where('ID', $eventid)->update('calendar_events', $data);
            
        } else {
            $this->db->where('ID', $eventid);
			$this->db->delete('calendar_events');
        }
		
        $account_type		=	$this->session->userdata('login_type');
		redirect(base_url() .$account_type. '/calendar/', 'refresh');
    }


	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function sms($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'update')
        {
            $data['description'] = $this->input->post('sms_status');
            $this->db->where('type' , 'sms_status');
            $this->db->update('settings' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/sms/', 'refresh');
        }
        if($param1 == 'android')
        {
            $data['description'] = $this->input->post('android_email');
            $this->db->where('type' , 'android_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('android_password');
            $this->db->where('type' , 'android_password');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('android_device');
            $this->db->where('type' , 'android_device');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/sms/', 'refresh');
        }
        if($param1 == 'clickatell')
        {
            $data['description'] = $this->input->post('clickatell_username');
            $this->db->where('type' , 'clickatell_username');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_password');
            $this->db->where('type' , 'clickatell_password');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_api');
            $this->db->where('type' , 'clickatell_api');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/sms/', 'refresh');
        }
        if($param1 == 'twilio') 
        {
            $data['description'] = $this->input->post('twilio_account');
            $this->db->where('type' , 'twilio_account');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('authentication_token');
            $this->db->where('type' , 'authentication_token');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('registered_phone');
            $this->db->where('type' , 'registered_phone');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/sms/', 'refresh');
        }
        if($param1 == 'services') 
        {
            $data['description'] = $this->input->post('absences');
            $this->db->where('type' , 'absences');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('students_reports');
            $this->db->where('type' , 'students_reports');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('p_new_invoice');
            $this->db->where('type' , 'p_new_invoice');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('new_homework');
            $this->db->where('type' , 'new_homework');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('s_new_invoice');
            $this->db->where('type' , 's_new_invoice');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/sms/', 'refresh');
        }
        $page_data['page_name']  = 'sms';
        $page_data['page_title'] = get_phrase('sms');
        $this->load->view('backend/index', $page_data);
    }

    function email($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'template')
        {
            $data['subject'] = $this->input->post('subject');
            $data['body'] = $this->input->post('body');
            $this->db->where('email_template_id', $param2);
            $this->db->update('email_template', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/email/', 'refresh');
        }
        if ($param1 == 'update') 
        {
            $this->load->helper('file');
            $data =     '<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");'."\n".''
                         .'$config["useragent"] = "CodeIgniter";'."\n".''
                         .'$config["protocol"]  = "smtp";'."\n".''
                         .'$config["smtp_host"] ="'.$this->input->post('smtp_host').'";'."\n".''
                         .'$config["smtp_user"] ="'.$this->input->post('smtp_user').'";'."\n".''
                         .'$config["smtp_pass"] ="'.$this->input->post('smtp_pass').'";'."\n".''
                         .'$config["smtp_port"] ="'.$this->input->post('smtp_port').'";'."\n".''
                         .'$config["smtp_timeout"] ="'.$this->input->post('smtp_timeout').'";'."\n".''
                         .'$config["wordwrap"]  = "TRUE";'."\n".''
                         .'$config["wrapchars"]  = "76";'."\n".''
                         .'$config["mailtype"] ="'.$this->input->post('mail_type').'";'."\n".''
                         .'$config["charset"] ="'.$this->input->post('char_set').'";'."\n".''
                         .'$config["validate"]  = "FALSE";'."\n".''
                         .'$config["priority"]  = "3";'."\n".''
                         .'$config["crlf"]  = "\r\n";'."\n".''
                         .'$config["newline"]="'. '\r\n'.'";'."\n".''
                         .'$config["bcc_batch_mode"] ="'. 'FALSE' .'";'."\n".''
                         .'$config["bcc_batch_size"] ="'. '200' .'";'."\n".'';

            if ( ! write_file('./application/config/email.php', $data))
            {
                 $this->session->set_flashdata('error_message', 'Error');
                 redirect(base_url() . 'admin/email/', 'refresh');
            }
            else
            {
                 $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
                 redirect(base_url() . 'admin/email/', 'refresh');
            }
        }
        $page_data['page_name']  = 'email';
        $page_data['current_email_template_id']  = 1;
        $page_data['page_title'] = get_phrase('email_settings');
        $this->load->view('backend/index', $page_data);
    }

    function view_teacher_report()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'view_teacher_report';
        $page_data['page_title'] = get_phrase('teacher_report');
        $this->load->view('backend/index', $page_data);
    }

    function translate($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'update') 
        {
            $page_data['edit_profile']  = $param2;
        }
        if ($param1 == 'update_phrase') 
        {
            $language   =   $param2;
            $total_phrase   =   $this->input->post('total_phrase');
            for($i = 1 ; $i < $total_phrase ; $i++)
            {
                $this->db->where('phrase_id' , $i);
                $this->db->update('language' , array($language => $this->input->post('phrase'.$i)));
            }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/translate/update/'.$language, 'refresh');
        }
        if ($param1 == 'add') 
        {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT'
				)
			);
			$this->dbforge->add_column('language', $fields);
			move_uploaded_file($_FILES['file_name']['tmp_name'], 'style/flags/' . $this->input->post('language') . '.png');
			$this->session->set_flashdata('flash_message', get_phrase('successfully_updated'));
			redirect(base_url() . 'admin/translate/', 'refresh');
		}
        if ($param1 == 'do_update') 
        {
            $language        = $this->input->post('language');
            $data[$language] = $this->input->post('phrase');
            $this->db->where('phrase_id', $param2);
            $this->db->update('language', $data);
            $this->session->set_flashdata('flash_message', "");
            redirect(base_url() . 'admin/translate/', 'refresh');
        }
        $page_data['page_name']  = 'translate';
        $page_data['page_title'] = get_phrase('translate');
        $this->load->view('backend/index', $page_data);
    }

    function polls($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'create')
        {
            $db['status'] = 0;
            $this->db->update('polls', $db);

            $data['question'] = $this->input->post('question');
            foreach ($this->input->post('options') as $row)
            {
                $data['options'] .= $row . ',';
            }
            $data['user'] = $this->input->post('user');
            $data['status'] = 1;
            $data['date'] = date('d M, Y');
            $data['poll_code'] = substr(md5(rand(0, 1000000)), 0, 7);
            $this->db->insert('polls', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/polls/', 'refresh');
        }
        if($param1 == 'response')
        {
            $data['poll_code'] = $this->input->post('poll_code');
            $data['answer'] = $this->input->post('answer');
            $user = $this->session->userdata('login_user_id');
            $user_type = $this->session->userdata('login_type');
            $data['user'] = $user_type ."-".$user;
            $data['date'] = date('d M, Y');
            $this->db->insert('poll_response', $data);
        }
        if($param1 == 'delete')
        {
            $this->db->where('poll_code', $param2);
            $this->db->delete('polls');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/polls/', 'refresh');
        }
        $page_data['page_name']  = 'polls';
        $page_data['page_title'] = get_phrase('polls');
        $this->load->view('backend/index', $page_data);
    }

    function view_poll($code)
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['code'] = $code;
        $page_data['page_name']  = 'view_poll';
        $page_data['page_title'] = get_phrase('poll_details');
        $this->load->view('backend/index', $page_data);
    }

    function admissions($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'reject')
        {
            $this->db->where('user_id', $param2);
            $this->db->delete('pending_users');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/admissions/', 'refresh');
        }
        $page_data['page_name']  = 'admissions';
        $page_data['page_title'] = get_phrase('admissions');
        $this->load->view('backend/index', $page_data);
    }

    function teacher_routine()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $teacher_id = $this->input->post('teacher_id');
        $page_data['page_name']  = 'teacher_routine';
        $page_data['teacher_id']  = $teacher_id;
        $page_data['page_title'] = get_phrase('teacher_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    function add_student()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'add_student';
        $page_data['page_title'] = get_phrase('add_student');
        $this->load->view('backend/index', $page_data);
    }

    function get_class_area()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $id = $this->input->post('class_id');
        redirect(base_url() . 'admin/students_area/'.$id."/", 'refresh');
    }

    function student_bulk($param1 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'add_bulk_student') 
        {
            $names     = $this->input->post('name');
            $rolls     = $this->input->post('roll');
            $emails    = $this->input->post('username');
            $passwords = $this->input->post('password');
            $date           = strtotime(date("d M,Y"));
            $phones    = $this->input->post('phone');
            $genders   = $this->input->post('sex');
            $student_entries = sizeof($names);
            for($i = 0; $i < $student_entries; $i++) 
            {
                $data['name']     =   $names[$i];
                $data['username']    =   $emails[$i];
                $data['password'] =   sha1($passwords[$i]);
                $data['date']           = strtotime(date("d M,Y"));
                $data['phone']    =   $phones[$i];
                $data['sex']      =   $genders[$i];
				$data['subdomain']   = $subdomain;
                if($data['name'] == '' || $data['username'] == '' || $data['password'] == '')
                    continue;
                $this->db->insert('student' , $data);
                $student_id = $this->db->insert_id();
                $data2['enroll_code']   =   substr(md5(rand(0, 1000000)), 0, 7);
                $data2['student_id']    =   $student_id;
                $data2['class_id']      =   $this->input->post('class_id');
                if($this->input->post('section_id') != '') 
                {
                    $data2['section_id']    =   $this->input->post('section_id');
                }
                $data2['roll']          =   $rolls[$i];
                $data2['date_added']    =   strtotime(date("d/m/Y H:i:s"));
                $data2['year']          =   $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
                $this->db->insert('enroll' , $data2);
            }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/students_area/' . $this->input->post('class_id')."/" , 'refresh');
        }
        if($param1 == 'excel')
        {
          move_uploaded_file($_FILES['excel']['tmp_name'], $subdomain.'uploads/excel.xlsx');
          include 'simplexlsx.class.php';
          $xlsx = new SimpleXLSX($subdomain.'uploads/excel.xlsx');
          list($num_cols, $num_rows) = $xlsx->dimension();
          $f = 0;
          foreach( $xlsx->rows() as $r ) 
          {
            if ($f == 0)
            {
                $f++;
                continue;
            }
            for( $i=0; $i < $num_cols; $i++ )
           {
                if ($i == 0) $data['name']           =  $r[$i];
                else if ($i == 1) $data['username']       =  $r[$i];
                else if($i == 2) $data['password']       =  sha1($r[$i]);
                else if($i == 3) $data['phone']          =  $r[$i];
                else if($i == 4) $data['sex']            =  $r[$i];
                else if($i == 5) $data['date']           =  strtotime(date("d M,Y"));
            }
			$data['subdomain']   = $subdomain;
            $this->db->insert('student' , $data);
              $student_id = $this->db->insert_id();
            for($x=0; $x < $num_cols; $x++)
            {
                $data2['roll'] =  $r[$x];
              $data2['enroll_code']   =   substr(md5(rand(0, 1000000)), 0, 7);
                $data2['student_id']    =   $student_id;
                $data2['class_id']      =   $this->input->post('class_id');
              $data2['section_id']    =   $this->input->post('section_id');
                $data2['date_added']    =   strtotime(date("d/m/Y H:i:s"));
                $data2['year']          =   $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
              }
              $this->db->insert('enroll' , $data2);
        }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/add_student/', 'refresh');
        }           
        $page_data['page_name']  = 'student_bulk';
        $page_data['page_title'] = get_phrase('student_bulk');
        $this->load->view('backend/index', $page_data);
    }
	
	/////////////////////// Teachers From Excel  ///////////////////////////////////
	function teacher_bulk($param1 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'excel')
        {
          move_uploaded_file($_FILES['excel']['tmp_name'], $subdomain.'uploads/teachers_excel.xlsx');
          include 'simplexlsx.class.php';
          $xlsx = new SimpleXLSX($subdomain.'uploads/teachers_excel.xlsx');
          list($num_cols, $num_rows) = $xlsx->dimension();
          $f = 0;
          foreach( $xlsx->rows() as $r ) 
          {
            if ($f == 0)
            {
                $f++;
                continue;
            }
            for( $i=0; $i < $num_cols; $i++ )
           {
                if ($i == 0) $data['name']           =  $r[$i];
                else if ($i == 1) $data['username']       =  $r[$i];
                else if($i == 2) $data['password']       =  sha1($r[$i]);
				else if($i == 3) $data['salary']          =  $r[$i];
				else if($i == 4) $data['sex']            =  $r[$i];
				else if($i == 5) $data['address']            =  $r[$i];
                else if($i == 6) $data['phone']          =  $r[$i];
				else if($i == 7) $data['email']          =  $r[$i];
                else if($i == 8) $data['birthday']           =  $r[$i];
            }
			$data['subdomain']        = $subdomain;
			$this->db->insert('teacher', $data);
			$teacher_id = $this->db->insert_id();
			move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/teacher_image/' . $teacher_id . '.jpg');
              
          }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
				redirect(base_url() . 'admin/teachers/', 'refresh');
        }           
        $page_data['page_name']  = 'teachers';
        $page_data['page_title'] = 'Teachers';
        $this->load->view('backend/index', $page_data);
    }
	//////////////////////////////////////////////////////////////////////////////
	/////////////////////// BUS From Excel  ///////////////////////////////////
	function bus_bulk($param1 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }		
        if($param1 == 'excel')
        {
          move_uploaded_file($_FILES['excel']['tmp_name'], $subdomain.'uploads/bus_excel.xlsx');
          include 'simplexlsx.class.php';
          $xlsx = new SimpleXLSX($subdomain.'uploads/bus_excel.xlsx');
          list($num_cols, $num_rows) = $xlsx->dimension();
          $f = 0;
          foreach( $xlsx->rows() as $r ) 
          {
            if ($f == 0)
            {
                $f++;
                continue;
            }
            for( $i=0; $i < $num_cols; $i++ )
           {
                if ($i == 0) $data['route_name']           =  $r[$i];
                else if ($i == 1) $data['number_of_vehicle']       =  $r[$i];
                else if($i == 2) $data['driver_name']       =  $r[$i];
				else if($i == 3) $data['driver_phone']          =  $r[$i];
				else if($i == 4) $data['supervisor_name']            =  $r[$i];
				else if($i == 5) $data['supervisor_phone']            =  $r[$i];
                else if($i == 6) $data['route']          =  $r[$i];
				else if($i == 7) $data['route_fare']          =  $r[$i];
                
            }
			$this->db->insert('transport', $data);
              
          }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
				redirect(base_url() . 'admin/school_bus/', 'refresh');
        }           
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'school_bus';
        $page_data['page_title'] = get_phrase('school_bus');
        $this->load->view('backend/index', $page_data); 
    }
	//////////////////////////////////////////////////////////////////////////////
	
	/////////////////////// Parents From Excel  ///////////////////////////////////
	function parent_bulk($param1 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
		
        if($param1 == 'excel')
        {
          move_uploaded_file($_FILES['excel']['tmp_name'], $subdomain.'uploads/parents_excel.xlsx');
          include 'simplexlsx.class.php';
          $xlsx = new SimpleXLSX($subdomain.'uploads/parents_excel.xlsx');
          list($num_cols, $num_rows) = $xlsx->dimension();
          $f = 0;
          foreach( $xlsx->rows() as $r ) 
          {
            if ($f == 0)
            {
                $f++;
                continue;
            }
            for( $i=0; $i < $num_cols; $i++ )
           {
                if ($i == 0) $data['name']           =  $r[$i];
                else if ($i == 1) $data['username']       =  $r[$i];
                else if($i == 2) $data['password']       =  sha1($r[$i]);
				else if($i == 3) $data['address']            =  $r[$i];
                else if($i == 4) $data['phone']          =  $r[$i];
				else if($i == 5) $data['profession']           =  $r[$i];
				else if($i == 6) $data['email']          =  $r[$i];
                
            }
			$data['subdomain']        = $subdomain;
			$this->db->insert('parent', $data);
			$parent_id     =   $this->db->insert_id();
			move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/parent_image/' . $parent_id . '.jpg');  
          }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
				redirect(base_url() . 'admin/parents/', 'refresh');
        }           
        $page_data['page_name']  = 'parents';
        $page_data['page_title'] = 'Parents';
        $this->load->view('backend/index', $page_data);
    }
	//////////////////////////////////////////////////////////////////////////////
	
	
	

    function student_portal($student_id, $param1='')
    {
         if ($this->session->userdata('admin_login') != 1)
         {
            redirect('login', 'refresh');
         }

        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->row()->class_id;

        $student_name = $this->db->get_where('student' , array('student_id' => $student_id))->row()->name;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $system = $this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;

        $page_data['page_name']  = 'student_portal';
        $page_data['page_title'] =  get_phrase('student_portal');
        $page_data['student_id'] =  $student_id;
        $page_data['class_id']   =   $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function get_sections($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/student_bulk_sections' , $page_data);
    }

    function my_account($param1 = "", $page_id = "")
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }       
        if($param1 == 'update')
        {
            $data['name'] = $this->input->post('name');
            $data['username'] = $this->input->post('username');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['address'] = $this->input->post('address');
            $data['birthday'] = $this->input->post('birthday');
            if($this->input->post('password') != "")
            {
                $data['password'] = sha1($this->input->post('password'));
            }
            $this->db->where('admin_id', $this->session->userdata('login_user_id'));
            $this->db->update('admin', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/admin_image/' . $this->session->userdata('login_user_id') . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/my_account/', 'refresh');
        }

        $data['page_name']              = 'my_account';
        $data['page_title']             = get_phrase('profile');
        $this->load->view('backend/index', $data);
    }

     function request($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
                
        if ($param1 == "accept")
        {
            $data['status'] = 1;
            $this->db->update('request', $data, array('request_id' => $param2));
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/request/', 'refresh');
        }
                
        if ($param1 == "reject")
        {
            $data['status'] = 2;
            $this->db->update('request', $data, array('request_id' => $param2));
            $this->session->set_flashdata('flash_message' , get_phrase('rejected_successfully'));
            redirect(base_url() . 'admin/request/', 'refresh');
        }
        
        $data['page_name']  = 'request';
        $data['page_title'] = get_phrase('permissions');
        $this->load->view('backend/index', $data);
    }

    function request_student($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
                
        if ($param1 == "accept")
        {
            $data['status'] = 1;
            $this->db->update('students_request', $data, array('request_id' => $param2));
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/request/', 'refresh');
        }
                
        if ($param1 == "reject")
        {
            $data['status'] = 2;
            $this->db->update('students_request', $data, array('request_id' => $param2));
            $this->session->set_flashdata('flash_message' , get_phrase('rejected_successfully'));
            redirect(base_url() . 'admin/request/', 'refresh');
        }
        if($param1 == 'delete')
        {
           $this->db->where('report_code',$param2);
           $this->db->delete('report_response');
           $this->db->where('code',$param2);
           $this->db->delete('reports');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/request_student/', 'refresh');
        }
        if($param1 == 'delete_teacher')
        {
            $this->db->where('report_code',$param2);
           $this->db->delete('reporte_alumnos');
           $this->db->where('report_code',$param2);
           $this->db->delete('reporte_mensaje');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/request_student/', 'refresh');
        }
        $data['page_name']  = 'request_student';
        $data['page_title'] = get_phrase('reports');
        $this->load->view('backend/index', $data);
    }

    function create_report_message($code = '') 
    {
        $data['message']      = $this->input->post('message');
        $data['report_code']  = $this->input->post('report_code');
        $data['timestamp']    = date("d M, Y");
        $data['sender_type']    = $this->session->userdata('login_type');
        $data['sender_id']      = $this->session->userdata('login_user_id');
        return $this->db->insert('reporte_mensaje', $data);
    }  

    function view_report($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'update')
        {
            $data['status'] = 1;
            $this->db->where('report_code', $param2);
            $this->db->update('reporte_alumnos', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/view_report/'.$param2, 'refresh');
        }
        $page_data['report_code'] = $param1;
        $page_data['page_title'] =   get_phrase('report_details');
        $page_data['page_name']  = 'view_report';
        $this->load->view('backend/index', $page_data);
    }

    function online_exams($param1 = '', $param2 = '', $param3 ='') 
    {
        if ($param1 == 'edit') 
        {
            if($this->crud_model->update_exam($param2))
			{
			  $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));	
			}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: exam end time can not be earlier than exam start time!!!');
			}
            
            redirect(base_url() . 'admin/exam_edit/' . $param2 , 'refresh');
        }
        if ($param1 == 'questions') 
        {
            $this->crud_model->add_questions();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/exam_questions/' . $param2 , 'refresh');
        }
        if ($param1 == 'delete_questions') 
        {
            $this->db->where('question_id', $param2);
            $this->db->delete('questions');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/exam_questions/'.$param3, 'refresh');
        }
        if ($param1 == 'delete'){
            $this->crud_model->delete_exam($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/online_exams/', 'refresh');
        }

        $page_data['page_name'] = 'online_exams';
        $page_data['page_title'] = get_phrase('online_exams');
        $this->load->view('backend/index', $page_data);
    }

	function exam_edit($exam_code= '') 
    { 
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }   
        $page_data['exam_code'] = $exam_code;
        $page_data['page_name'] = 'exam_edit';
        $page_data['page_title'] = get_phrase('update_exam');
        $this->load->view('backend/index', $page_data);
    }

    function exam_results($exam_code) 
    { 
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }   
        $page_data['exam_code'] = $exam_code;
        $page_data['page_name'] = 'exam_results';
        $page_data['page_title'] = get_phrase('exams_results');
        $this->load->view('backend/index', $page_data);
    }

    function exam_questions($exam_code = '') 
    {    
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['exam_code'] = $exam_code;
        $page_data['page_name'] = 'exam_questions';
        $page_data['page_title'] = get_phrase('exam_questions');
        $this->load->view('backend/index', $page_data);
    }

    function manage_exams($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'delete')
        {
            $this->db->where('exam_id', $param2);
            $this->db->delete('exams');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/online_exams/', 'refresh');
        }
    }

    function homeworkroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'file') 
        {
            $page_data['room_page']    = 'homework_file';
            $page_data['homework_code'] = $param2;
        }  
        else if ($param1 == 'details') 
        {
            $page_data['room_page'] = 'homework_details';
            $page_data['homework_code'] = $param2;
        }
        else if ($param1 == 'edit') 
        {
            $page_data['room_page'] = 'homework_edit';
            $page_data['homework_code'] = $param2;
        }

        $page_data['homework_code'] =   $param1;
        $page_data['page_name']   = 'homework_room'; 
        $page_data['page_title']  = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

     function homework_edit($homework_code = '') 
    {   
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        } 
        $page_data['homework_code'] = $homework_code;
        $page_data['page_name'] = 'homework_edit';
        $page_data['page_title'] = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

    function single_homework($param1 = '', $param2 = '') 
    {
       if ($this->session->userdata('admin_login') != 1)
       {
            redirect(base_url(), 'refresh');
       }
       
       $page_data['answer_id'] = $param1;
       $page_data['page_name'] = 'single_homework';
       $page_data['page_title'] = get_phrase('homework');
       $this->load->view('backend/index', $page_data);
    }

    function homework_details($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['homework_code'] = $param1;
        $page_data['page_name']  = 'homework_details';
        $page_data['page_title'] = get_phrase('homework_details');
        $this->load->view('backend/index', $page_data);
    }

    function homework($param1 = '', $param2 = '') 
    {
        if ($param1 == 'create') 
        {
            $homework_code = $this->crud_model->homework_create();
            $class_id = $this->input->post('class_id');
            $subject_id = $this->input->post('subject_id');
            $section_id = $this->input->post('section_id');
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $this->crud_model->send_homework_notify($class_id,$section_id,$subject_id,"".$title."","".$description."");
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/homeworkroom/' . $homework_code , 'refresh');
        }
        if($param1 == 'update')
        {
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['time_end'] = $this->input->post('time_end');
            $data['date_end'] = $this->input->post('date_end');
            $data['user'] = $this->session->userdata('login_type');
            $data['type'] = $this->input->post('type');
            $this->db->where('homework_code', $param2);
            $this->db->update('homework', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/homework_edit/' . $param2 , 'refresh');
        }
        if($param1 == 'review')
        {
            $id = $this->input->post('answer_id');
            $mark = $this->input->post('mark');
            $comment = $this->input->post('comment');
            $entries = sizeof($mark);
            for($i = 0; $i < $entries; $i++) 
            {
                $data['mark']    = $mark[$i];
                $data['teacher_comment'] = $comment[$i];
                $this->db->where_in('id', $id[$i]);
                $this->db->update('deliveries', $data);
            }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/homework_details/' . $param2 , 'refresh');
        }
        if($param1 == 'single')
        {
            $data['teacher_comment'] = $this->input->post('comment');
            $data['mark'] = $this->input->post('mark');
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('deliveries', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/single_homework/' . $this->input->post('id') , 'refresh');
        }
        if ($param1 == 'edit') 
        {
            $this->crud_model->update_homework($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/homeworkroom/edit/' . $param2 , 'refresh');
        }
        if ($param1 == 'delete')
        {
            $this->crud_model->delete_homework($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/homework', 'refresh');
        }

        $page_data['page_name'] = 'homework';
        $page_data['page_title'] = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

    function forum($param1 = '', $param2 = '') 
    {
        if ($param1 == 'create') 
        {
            $post_code = $this->crud_model->create_post();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/forumroom/' . $post_code , 'refresh');
        }
        if ($param1 == 'update') 
        {
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['class_id'] = $this->input->post('class_id');
            $data['type'] = $this->session->userdata('login_type');
            $data['section_id'] = $this->input->post('section_id');
            $data['timestamp'] = strtotime(date("d M,Y"));
            $data['subject_id'] = $this->input->post('subject_id');
            $data['teacher_id']  =   $this->session->userdata('login_user_id');
            $this->db->where('post_code', $param2);
            $this->db->update('forum', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/edit_forum/' . $param2 , 'refresh');
        }
        if ($param1 == 'delete')
        {
            $this->crud_model->delete_post($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/forum/' , 'refresh');
        }
        $page_data['page_name'] = 'forum';
        $page_data['page_title'] = get_phrase('forum');
        $this->load->view('backend/index', $page_data);
    }

    function study_material($task = "", $document_id = "")
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        } 
        if ($task == "create")
        {
            $this->crud_model->save_study_material_info();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_uploaded'));
            redirect(base_url() . 'admin/study_material' , 'refresh');
        }
        if ($task == "delete")
        {
            $this->crud_model->delete_study_material_info($document_id);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/study_material/');
        }
        
        $data['page_name']              = 'study_material';
        $data['page_title']             = get_phrase('study_material');
        $this->load->view('backend/index', $data);
    }

    function edit_forum($code = '')
    {
        $page_data['page_name']  = 'edit_forum';
        $page_data['page_title'] = get_phrase('update_forum');
        $page_data['code']   = $code;
        $this->load->view('backend/index', $page_data);    
    }

    function forumroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'comment') 
        {
            $page_data['room_page']    = 'comments';
            $page_data['post_code'] = $param2; 
        }
        else if ($param1 == 'posts') 
        {
            $page_data['room_page'] = 'post';
            $page_data['post_code'] = $param2; 
        }
        else if ($param1 == 'edit') 
        {
            $page_data['room_page'] = 'post_edit';
            $page_data['post_code'] = $param2;
        }

        $page_data['page_name']   = 'forum_room'; 
        $page_data['post_code']   = $param1;
        $page_data['page_title']  = get_phrase('forum');
        $this->load->view('backend/index', $page_data);
    }

    function forum_message($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_post_message($this->input->post('post_code'));
        }
    }

    function examroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']   = 'exam_room'; 
        $page_data['exam_code']  = $param1;
        $page_data['page_title']  = get_phrase('online_exams');
        $this->load->view('backend/index', $page_data);
    }

    function create_exam($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'create')
        {
            if($this->crud_model->create_online_exam())
			{
				$this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
			}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: exam end time can not be earlier than exam start time!!!');
			}
            
            redirect(base_url() . 'admin/online_exams/', 'refresh');
        }

        $page_data['page_name']  = 'create_exam';
        $page_data['page_title'] = get_phrase('online_exams');
        $this->load->view('backend/index', $page_data);
    }

    function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        if ($param1 == 'create') 
        {
            $data['student_id']         = $this->input->post('student_id');
            $data['class_id']         = $this->input->post('class_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $amount;
            if($this->input->post('amount_paid') == "")
            {
            	$amount = 0;
            }else {
            	$amount = $this->input->post('amount_paid');
            }
            $data['amount_paid']        = $amount;
            $data['due']                = $data['amount'] - $amount;
            $data['status']             = $this->input->post('status');
			
			$originalDate =$this->input->post('date');
			$originalDate = str_replace('/', '-', $originalDate);
			$newDate = date("d-m-Y", strtotime($originalDate));
			$data['creation_timestamp']  = strtotime($newDate);
			
            //$data['creation_timestamp'] = strtotime($this->input->post('date'));
            $data['year']               = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            
            $this->db->insert('invoice', $data);
            $invoice_id = $this->db->insert_id();

            $data2['invoice_id']        =   $invoice_id;
            $data2['student_id']        =   $this->input->post('student_id');
            $data2['title']             =   $this->input->post('title');
            $data2['description']       =   $this->input->post('description');
            $data2['payment_type']      =  'income';
            $data2['method']            =   $this->input->post('method');
            $data2['amount']            =   $this->input->post('amount_paid');
			
			$originalDate =$this->input->post('date');
			$originalDate = str_replace('/', '-', $originalDate);
			$newDate = date("d-m-Y", strtotime($originalDate));
			$data2['timestamp']  = strtotime($newDate);
            //$data2['timestamp']         =   strtotime($this->input->post('date'));
            $data2['year']              =  $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('payment' , $data2);

            $student_name = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->name;
            $student_email = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->email;
            $student_phone = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->phone;
            $parent_id = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->parent_id;
            $parent_phone = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->phone;
            $parent_email = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->email;
            $notify = $this->db->get_where('settings' , array('type' => 'p_new_invoice'))->row()->description;
            $notify2 = $this->db->get_where('settings' , array('type' => 's_new_invoice'))->row()->description;

              $message = "A new invoice has been generated for " . $student_name;
              require_once 'smsGateway.php';
              $email = $this->db->get_where('settings' , array('type' => 'android_email'))->row()->description;
              $pass   = $this->db->get_where('settings' , array('type' => 'android_password'))->row()->description;    
              $device   = $this->db->get_where('settings' , array('type' => 'android_device'))->row()->description;    
              $object = new SmsGateway($email, $pass);
              $sms_status = $this->db->get_where('settings' , array('type' => 'sms_status'))->row()->description;

            if($notify == 1)
            {
              if ($sms_status == 'android') 
              {
                 $result = $object->sendMessageToNumber($parent_phone, $message, $device);
              }
              else if ($sms_status == 'twilio') 
              {
                  $this->crud_model->twilio($message,"".$parent_phone."");
              }
              else if ($sms_status == 'clickatell') 
              {
                  $this->crud_model->clickatell($message,$parent_phone);
              }
            }
            $this->crud_model->parent_new_invoice($student_name, "".$parent_email."");
            if($notify2 == 1)
            {
              if ($sms_status == 'android') 
              {
                 $result = $object->sendMessageToNumber($student_phone, $message, $device);
              }
              else if ($sms_status == 'twilio') 
              {
                  $this->crud_model->twilio($message,"".$student_phone."");
              }
              else if ($sms_status == 'clickatell') 
              {
                  $this->crud_model->clickatell($message,$student_phone);
              }
            }
            $this->crud_model->student_new_invoice($student_name, "".$student_email."");
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/payments', 'refresh');
        }
        if ($param1 == 'do_update') 
        {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['status']             = $this->input->post('status');

            $this->db->where('invoice_id', $param2);
            $this->db->update('invoice', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/students_payments/', 'refresh');
        }else if ($param1 == 'edit') 
        {
            $page_data['edit_data'] = $this->db->get_where('invoice', array('invoice_id' => $param2))->result_array();
        }

        if ($param1 == 'delete') 
        {
            $this->db->where('invoice_id', $param2);
            $this->db->delete('invoice');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/students_payments/', 'refresh');
        }
        $page_data['page_name']  = 'invoice';
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function invoice_details($id)
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['invoice_id'] = $id;
        $page_data['page_title'] = get_phrase('invoice_details');
        $page_data['page_name']  = 'invoice_details';
        $this->load->view('backend/index', $page_data);
    }

    function looking_report($report_code = '') 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['code'] = $report_code;
        $page_data['page_name'] = 'looking_report';
        $page_data['page_title'] = get_phrase('report_details');
        $this->load->view('backend/index', $page_data);
    }

    function students_area($id)
    {
      if ($this->session->userdata('admin_login') != 1)
      {
        redirect('login', 'refresh');
      }
      $id = $this->input->post('class_id');
      if ($id == '')
      {
        $id = $this->db->get('class')->first_row()->class_id;
      }
      $page_data['page_name']   = 'students_area';
      $page_data['page_title']  = get_phrase('students_area');
      $page_data['id']  = $id;
      $this->load->view('backend/index', $page_data);
    }

    function student($param1 = '', $param2 = '', $param3 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        if ($param1 == 'create') 
        {
			$username = $this->input->post('username');
			if($this->users_model->validate_username($username))
			{
					$data['name']           = $this->input->post('name');
					$data['username']       = $this->input->post('username');
					$data['birthday']       = $this->input->post('birthday');
					$data['date']           = strtotime(date("d M,Y"));
					$data['sex']            = $this->input->post('sex');
					$data['address']        = $this->input->post('address');
					$data['phone']          = $this->input->post('phone');
					$data['email']          = $this->input->post('email');
					$data['password']       = sha1($this->input->post('password'));
					$data['parent_id']      = $this->input->post('parent_id');
					$data['dormitory_id']  = $this->input->post('dormitory_id');
					$data['transport_id']  = $this->input->post('transport_id');
					$data['subdomain']   = $subdomain;
					$this->db->insert('student', $data);
					$student_id = $this->db->insert_id();
					$data2['student_id']     = $student_id;
					$data2['enroll_code']    = substr(md5(rand(0, 1000000)), 0, 7);
					$data2['class_id']       = $this->input->post('class_id');
					if ($this->input->post('section_id') != '') 
					{
						$data2['section_id'] = $this->input->post('section_id');
					}
					$data2['roll']           = $this->input->post('roll');
					$data2['date_added']     = strtotime(date("d/m/Y H:i:s"));
					$data2['year']           = $running_year;
					$this->db->insert('enroll', $data2);
					move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/student_image/' . $student_id . '.jpg');
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
			}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
			}
			redirect(base_url() . 'admin/add_student/', 'refresh');
        }
        if ($param1 == 'do_update') 
        {
			
			$username = $this->db->get_where('student' , array('student_id' => $param2))->row()->username;
			$new_username = $this->input->post('username');
			if($username == $new_username || $this->users_model->validate_username($new_username))
			{	
					$data['name']            = $this->input->post('name');
					$data['username']        = $this->input->post('username');
					$data['phone']           = $this->input->post('phone');
					$data['address']         = $this->input->post('address');
					$data['parent_id']       = $this->input->post('parent_id');
					$data['birthday']        = $this->input->post('birthday');
					$data['dormitory_id']    = $this->input->post('dormitory_id');
					$data['transport_id']    = $this->input->post('transport_id');
					$data['student_session'] = $this->input->post('student_session');
					$data['email']           = $this->input->post('email');
					$data['sex']             = $this->input->post('sex');
					$data['subdomain']       = $subdomain;
					if($this->input->post('password') != "")
					{
					   $data['password'] = sha1($this->input->post('password'));
					}
					$this->db->where('student_id', $param2);
					$this->db->update('student', $data);
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));

					move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/student_image/' . $param2 . '.jpg');
					$this->crud_model->clear_cache();
			}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
			}
            redirect(base_url() . 'admin/student_portal/'. $param2, 'refresh');
			
        }
        if ($param1 == 'do_updates') 
        {
			$username = $this->db->get_where('student' , array('student_id' => $param2))->row()->username;
			$new_username = $this->input->post('username');
			if($username == $new_username || $this->users_model->validate_username($new_username))
			{
                    $data['name']            = $this->input->post('name');
					$data['username']        = $this->input->post('username');
					$data['phone']           = $this->input->post('phone');
					$data['address']         = $this->input->post('address');
					$data['parent_id']       = $this->input->post('parent_id');
					$data['birthday']        = $this->input->post('birthday');
					$data['dormitory_id']    = $this->input->post('dormitory_id');
					$data['transport_id']    = $this->input->post('transport_id');
					$data['student_session'] = $this->input->post('student_session');
					$data['email']           = $this->input->post('email');
					$data['sex']           = $this->input->post('sex');
					$data['subdomain']   = $subdomain;
					if($this->input->post('password') != "")
					{
					   $data['password'] = sha1($this->input->post('password'));
					}
					$this->db->where('student_id', $param2);
					$this->db->update('student', $data);
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));

					move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/student_image/' . $param2 . '.jpg');
					$this->crud_model->clear_cache();
			}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
			}
            redirect(base_url() . 'admin/students/'. $param2, 'refresh');
        }
        if($param1 == 'accept')
        {
            $pending = $this->db->get_where('pending_users', array('user_id' => $param2))->result_array();
            foreach ($pending as $row) 
            {
				$username = $row['username'];
				if($this->users_model->validate_username($username))
				{
					$data['name'] = $row['name'];
					$data['email'] = $row['email'];
					$data['username'] = $row['username'];
					$data['sex'] = $row['sex'];
					$data['address'] = $row['address'];
					$data['password'] = $row['password'];
					$data['birthday'] = $row['birthday'];
					$data['parent_id'] = $row['parent_id'];
					$data['phone'] = $row['phone'];
					$data['date'] = date('d M, Y');
					$data['subdomain']   = $subdomain;
					$this->db->insert('student', $data);
					$student_id = $this->db->insert_id();

					$data2['student_id']     = $student_id;
					$data2['enroll_code']    = substr(md5(rand(0, 1000000)), 0, 7);
					$data2['class_id']       = $row['class_id'];
					$data2['section_id']     = $row['section_id'];
					$data2['roll']           = $row['roll'];
					$data2['date_added']     = strtotime(date("d/m/Y H:i:s"));
					$data2['year']           = $running_year;
					$this->db->insert('enroll', $data2);
					$this->crud_model->account_confirm('student', $student_id);
					
				}
            }
           if($this->users_model->validate_username($username))
				{
					$this->db->where('user_id', $param2);
					$this->db->delete('pending_users');
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
				}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: Username is Already Taken!!!');
			}	
            redirect(base_url() . 'admin/students_area/', 'refresh');
        }
    }

    function student_promotion($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        if($param1 == 'promote') 
        {
            $running_year  =   $this->input->post('running_year');  
            $from_class_id =   $this->input->post('promotion_from_class_id'); 
            $students_of_promotion_class =   $this->db->get_where('enroll' , array('class_id' => $from_class_id , 'year' => $running_year))->result_array();
            foreach($students_of_promotion_class as $row) 
            {
                $enroll_data['enroll_code']     =   substr(md5(rand(0, 1000000)), 0, 7);
                $enroll_data['student_id']      =   $row['student_id'];
                $enroll_data['class_id']        =   $this->input->post('promotion_status_'.$row['student_id']);
                $enroll_data['year']            =   $this->input->post('promotion_year');
                $enroll_data['date_added']      =   strtotime(date("d/m/Y H:i:s"));
                $this->db->insert('enroll' , $enroll_data);
            } 
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_promoted'));
            redirect(base_url() . 'admin/student_promotion' , 'refresh');
        }
        $page_data['page_title']    = get_phrase('student_promotion');
        $page_data['page_name']  = 'student_promotion';
        $this->load->view('backend/index', $page_data);
    }

    function get_students_to_promote($class_id_from , $class_id_to , $running_year , $promotion_year)
    {
        $page_data['class_id_from']     =   $class_id_from;
        $page_data['class_id_to']       =   $class_id_to;
        $page_data['running_year']      =   $running_year;
        $page_data['promotion_year']    =   $promotion_year;
        $this->load->view('backend/admin/student_promotion_selector' , $page_data);
    }

    function events($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        } 
        if ($param1 == 'create') 
        {
            $data['title']         = $this->input->post('title');
            $data['description']   = $this->input->post('description');
            $data['datefrom']      = $this->input->post('datefrom');
            $data['dateto']        = $this->input->post('dateto');
            $this->db->insert('events', $data);
            redirect(base_url() . 'admin/events/', 'refresh');
        }
        if ($param1 == 'edit') 
        {
            $data['title']         = $this->input->post('title');
            $data['description']   = $this->input->post('description');
            $data['datefrom']      = $this->input->post('datefrom');
            $data['dateto']        = $this->input->post('dateto');
            $this->db->where('event_id' , $param2);
            $this->db->update('events' , $data);
            redirect(base_url() . 'admin/events/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('event_id' , $param2);
            $this->db->delete('events');
            redirect(base_url() . 'admin/events/', 'refresh');
        }
        $page_data['page_title']    = get_phrase('add_event');
        $page_data['page_name']  = 'events';
        $this->load->view('backend/index', $page_data);
    }

    function view_marks($student_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $year =  $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' =>$year))->row()->class_id;
        $page_data['class_id']   =   $class_id;
        $page_data['page_name']  = 'view_marks';
        $page_data['page_title'] = get_phrase('marks');
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);    
    }

    function subject_marks($data) 
     {
         $page_data['data'] = $data;
         $page_data['page_name']    = 'subject_marks';
         $page_data['page_title']   = get_phrase('subject_marks');
         $this->load->view('backend/index',$page_data);
     }

    function courses($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
			$data['type']       = $this->input->post('subject_type');
			$subject_type       = $this->input->post('subject_type');
			if($subject_type =='1' )  // Obligatory Subject
			{
				$students           = $this->input->post('students_holder');
			    $students_json      = json_encode($students);
			    $data['students']   = $students_json;
				
			}
			else
			{
				$data['students'] = 'all';
			}
			
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
			$data['section_id']   = $this->input->post('section_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
			
			
			
			
            $data['year']       = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/courses/', 'refresh');
        }
        if ($param1 == 'update_labs') 
        {
            $class_id = $this->db->get_where('subject', array('subject_id' => $param2))->row()->class_id;
            $data['la1'] = $this->input->post('la1');
            $data['la2'] = $this->input->post('la2');
            $data['la3'] = $this->input->post('la3');
            $data['la4'] = $this->input->post('la4');
            $data['la5'] = $this->input->post('la5');
            $data['la6'] = $this->input->post('la6');
            $data['la7'] = $this->input->post('la7');
            $data['la8'] = $this->input->post('la8');
            $data['la9'] = $this->input->post('la9');
            $data['la10'] = $this->input->post('la10');
            $this->db->where('subject_id', $param2);
            $this->db->update('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/marks_upload/'.$this->input->post('exam_id')."/".$class_id."/".$this->input->post('section_id')."/".$param2, 'refresh');
        }
        if ($param1 == 'update') 
        {
			$data['type']       = $this->input->post('subject_type');
			$subject_type       = $this->input->post('subject_type');
			if($subject_type =='1' )  // Obligatory Subject
			{
				$students           = $this->input->post('students_holder');
			    $students_json      = json_encode($students);
			    $data['students']   = $students_json;
				
			}
			else
			{
				$data['students'] = 'all';
			}
            $data['name'] = $this->input->post('name');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $this->db->where('subject_id', $param2);
            $this->db->update('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/courses/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('subject_id', $param2);
            $this->db->delete('subject');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/courses/', 'refresh');
        }
        $id = $this->input->post('class_id');
        $secid = $this->input->post('section_id');
        $page_data['id']   = $id;
		$page_data['secid']   = $secid;
        $page_data['subjects']   = $this->db->get_where('subject' , array('class_id' => $param1, 'section_id' => $secid))->result_array();
        $page_data['page_name']  = 'coursess';
        $page_data['page_title'] = get_phrase('subjects');
        $this->load->view('backend/index', $page_data);
    }

    function manage_classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['name']         = $this->input->post('name');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $this->db->insert('class', $data);
            $class_id = $this->db->insert_id();
            $data2['class_id']  =   $class_id;
            $data2['name']      =   'A';
            $this->db->insert('section' , $data2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/manage_classes/', 'refresh');
        }
        if ($param1 == 'update')
        {
            $data['name']         = $this->input->post('name');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $this->db->where('class_id', $param2);
            $this->db->update('class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/manage_classes/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('class_id', $param2);
            $this->db->delete('class');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/manage_classes/', 'refresh');
        }
        $page_data['classes']    = $this->db->get('class')->result_array();
        $page_data['page_name']  = 'manage_class';
        $page_data['page_title'] = get_phrase('manage_class');
        $this->load->view('backend/index', $page_data);
    }

    function get_subject($class_id) 
    {
        $subject = $this->db->get_where('subject' , array('class_id' => $class_id))->result_array();
        foreach ($subject as $row) 
        {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function upload_book()
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        $data['libro_code'] =   substr(md5(rand(0, 1000000)), 0, 7);
        $data['nombre']                 =   $this->input->post('nombre');
        $data['autor']                  =   $this->input->post('autor');
        $data['description']            =   $this->input->post('description');
        $data['class_id']               =   $this->input->post('class_id');
        $data['subject_id']             =   $this->input->post('subject_id');
        $data['uploader_type']          =   $this->session->userdata('login_type');
        $data['uploader_id']            =   $this->session->userdata('login_user_id');
        $data['year']                   =   $this->db->get_where('settings',array('type'=>'running_year'))->row()->description;
        $data['timestamp']              =   strtotime(date("d/m/Y H:i:s"));
        $files = $_FILES['file_name'];
        $this->load->library('upload');
        $config['upload_path']   =  $subdomain.'uploads/libreria/';
        $config['allowed_types'] =  '*';
        $_FILES['file_name']['name']     = $files['name'];
        $_FILES['file_name']['type']     = $files['type'];
        $_FILES['file_name']['tmp_name'] = $files['tmp_name'];
        $_FILES['file_name']['size']     = $files['size'];
        $this->upload->initialize($config);
        $this->upload->do_upload('file_name');
        $data['file_name'] = $_FILES['file_name']['name'];
        $this->db->insert('libreria', $data);
        redirect(base_url() . 'index.php?admin/virtual_library/' . $data['class_id'] , 'refresh');
    }

    function download_book($libro_code)
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        $file_name = $this->db->get_where('libreria', array('libro_code' => $libro_code))->row()->file_name;
        $this->load->helper('download');
        $data = file_get_contents($subdomain."uploads/libreria/" . $file_name);
        $name = $file_name;
        force_download($name, $data);
    }

    function delete_book($libro_id)
    {
        $this->crud_model->delete_book($libro_id);
        redirect(base_url() . 'admin/virtual_library/' . $data['class_id'] , 'refresh');
    }

    function section($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $id = $this->input->post('class_id');
        
        if($param1 == 'update')
        {
            $data['name'] = $this->input->post('name');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $this->db->where('section_id', $param2);
            $this->db->update('section', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/section/', 'refresh');
        }
		
        $page_data['page_name']  = 'section';
        $page_data['page_title'] = get_phrase('sections');
        $page_data['id']   = $id;
        $this->load->view('backend/index', $page_data);    
    }

    function sections($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['name']       =   $this->input->post('name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $this->db->insert('section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/section/' . $data['class_id'] ."/", 'refresh');
        }
        if ($param1 == 'edit') {
            $data['name']       =   $this->input->post('name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $this->db->where('section_id' , $param2);
            $this->db->update('section' , $data);
            redirect(base_url() . 'admin/section/' . $data['class_id'] , 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('section_id' , $param2);
            $this->db->delete('section');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/section/' , 'refresh');
        }
    }

    function get_class_section($class_id)
    {
        $sections = $this->db->get_where('section' , array('class_id' => $class_id))->result_array();
        echo '<option value="">' . get_phrase('select') . '</option>';
        foreach ($sections as $row) 
        {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }
	
	
    function get_class_stundets($section_id)
    {
        $students = $this->db->get_where('enroll' , array('section_id' => $section_id))->result_array();
        foreach ($students as $row) 
        {
         echo '<option value="' . $row['student_id'] . '">' . $this->db->get_where('student', array('student_id'=> $row['student_id']))->row()->name  . '</option>';
        }
    }

    function get_class_subject($class_id)
    {
        $subjects = $this->db->get_where('subject' , array('class_id' => $class_id))->result_array();
        foreach ($subjects as $row) 
        {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_students($class_id)
    {
        $students = $this->db->get_where('enroll' , array(
            'class_id' => $class_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->result_array();
        foreach ($students as $row) {
            $name = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
            echo '<option value="' . $row['student_id'] . '">' . $name . '</option>';
        }
    }
	
	function get_section_students($section_id)
    {
        $students = $this->db->get_where('enroll' , array( 'section_id' => $section_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->result_array();
        foreach ($students as $row) {
            $name = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
            echo '<option value="' . $row['student_id'] . '">' . $name . '</option>';
        }
    }

    function semesters($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['name']    = $this->input->post('name');
            $data['start']   = $this->input->post('start');
            $data['end']     = $this->input->post('end');
            $data['year']    = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));

            redirect(base_url() . 'admin/semesters/', 'refresh');
        }
        if ($param1 == 'update') 
        {
            $data['name']    = $this->input->post('name');
            $data['start']   = $this->input->post('start');
            $data['end']     = $this->input->post('end');
            $data['year']    = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->where('exam_id', $param2);
            $this->db->update('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/semesters/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('exam_id', $param2);
            $this->db->delete('exam');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/semesters/', 'refresh');
        }
        $page_data['exams']      = $this->db->get('exam')->result_array();
        $page_data['page_name']  = 'semester';
        $page_data['page_title'] = get_phrase('semesters');
        $this->load->view('backend/index', $page_data);
    }

    function update_book($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['book_id'] = $param1;
        $page_data['page_name']  =   'update_book';
        $page_data['page_title'] = get_phrase('update_book');
        $this->load->view('backend/index', $page_data);
    }

    function upload_marks()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  =   'upload_marks';
        $page_data['page_title'] = get_phrase('upload_marks');
        $this->load->view('backend/index', $page_data);
    }

    function marks_upload($exam_id = '' , $class_id = '' , $section_id = '' , $subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['exam_id']    =   $exam_id;
        $page_data['class_id']   =   $class_id;
        $page_data['subject_id'] =   $subject_id;
        $page_data['section_id'] =   $section_id;
        $page_data['page_name']  =   'marks_upload';
        $page_data['page_title'] = get_phrase('upload_marks');
        $this->load->view('backend/index', $page_data);
    }

    function marks_selector()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $data['exam_id']    = $this->input->post('exam_id');
        $data['class_id']   = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['subject_id'] = $this->input->post('subject_id');
        $data['year']       = $this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;
        $students = $this->db->get_where('enroll' , array('class_id' => $data['class_id'] , 'section_id' => $data['section_id'] , 'year' => $data['year']))->result_array();
        foreach($students as $row) 
        {
        $verify_data = array('exam_id' => $data['exam_id'],
                            'class_id' => $data['class_id'],
                            'section_id' => $data['section_id'],
                            'student_id' => $row['student_id'],
                                'subject_id' => $data['subject_id'],
                                    'year' => $data['year']);

        $query = $this->db->get_where('mark' , $verify_data);
        if($query->num_rows() < 1) 
        {   
                $data['student_id'] = $row['student_id'];
                $this->db->insert('mark' , $data);
        }
     }
        redirect(base_url() . 'admin/marks_upload/' . $data['exam_id'] . '/' . $data['class_id'] . '/' . $data['section_id'] . '/' . $data['subject_id'] , 'refresh');
    }

    function marks_update($exam_id = '' , $class_id = '' , $section_id = '' , $subject_id = '')
    {
        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $marks_of_students = $this->db->get_where('mark' , array('exam_id' => $exam_id, 'class_id' => $class_id,'section_id' => $section_id, 'year' => $running_year,'subject_id' => $subject_id))->result_array();
        foreach($marks_of_students as $row) 
        {
            $obtained_marks = $this->input->post('marks_obtained_'.$row['mark_id']);
            $labouno = $this->input->post('lab_uno_'.$row['mark_id']);
            $labodos = $this->input->post('lab_dos_'.$row['mark_id']);
            $labotres = $this->input->post('lab_tres_'.$row['mark_id']);
            $labocuatro = $this->input->post('lab_cuatro_'.$row['mark_id']);
            $labocinco = $this->input->post('lab_cinco_'.$row['mark_id']);
            $laboseis = $this->input->post('lab_seis_'.$row['mark_id']);
            $labosiete = $this->input->post('lab_siete_'.$row['mark_id']);
            $laboocho = $this->input->post('lab_ocho_'.$row['mark_id']);
            $comment = $this->input->post('comment_'.$row['mark_id']);
            $labonueve = $this->input->post('lab_nueve_'.$row['mark_id']);
            $labototal = $obtained_marks + $labouno + $labodos + $labotres + $labocuatro + $labocinco + $laboseis + $labosiete + $laboocho + $labonueve;
            $this->db->where('mark_id' , $row['mark_id']);
            $this->db->update('mark' , array('mark_obtained' => $obtained_marks , 'labuno' => $labouno
            , 'labdos' => $labodos, 'labtres' => $labotres, 'labcuatro' => $labocuatro, 'labcinco' => $labocinco, 'labseis' => $laboseis
                , 'labsiete' => $labosiete, 'labocho' => $laboocho, 'labnueve' => $labonueve, 'labtotal' => $labototal, 'comment' => $comment));
        }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
        redirect(base_url().'admin/marks_upload/'.$exam_id.'/'.$class_id.'/'.$section_id.'/'.$subject_id , 'refresh');
    }

    function tab_sheet($class_id = '' , $exam_id = '') 
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        if ($this->input->post('operation') == 'selection') 
        {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0) 
            {
                redirect(base_url() . 'admin/tab_sheet/' . $page_data['class_id'] . '/' . $page_data['exam_id'] , 'refresh');
            } else 
            {
                redirect(base_url() . 'admin/tab_sheet/', 'refresh');
            }
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['page_info'] = 'Exam marks';
        $page_data['page_name']  = 'tab_sheet';
        $page_data['page_title'] = get_phrase('tabulation_sheet');
        $this->load->view('backend/index', $page_data);
    }

    function tab_sheet_print($class_id , $exam_id) 
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['class_id'] = $class_id;
        $page_data['exam_id']  = $exam_id;
        $this->load->view('backend/admin/tab_sheet_print' , $page_data);
    }

    function marks_get_subject($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/marks_get_subject' , $page_data);
    }

    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['class_id']       = $this->input->post('class_id');
            if($this->input->post('section_id') != '') 
            {
                $data['section_id'] = $this->input->post('section_id');
            }
            $subject_id = $this->input->post('subject_id');
            $teacher_id = $this->db->get_where('subject', array('subject_id' => $subject_id))->row()->teacher_id;
            $data['subject_id']     = $this->input->post('subject_id');
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['day']            = $this->input->post('day');
            $data['teacher_id'] = $teacher_id;
            $data['year']           = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/class_routine_view/', 'refresh');
        }
        if ($param1 == 'update') 
        {
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['day']            = $this->input->post('day');
            $data['year']           = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->where('class_routine_id', $param2);
            $this->db->update('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/class_routine_view/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('class_routine_id', $param2);
            $this->db->delete('class_routine');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/class_routine_view/' . $class_id, 'refresh');
        } 
    }

    function exam_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['class_id']       = $this->input->post('class_id');
            if($this->input->post('section_id') != '') 
            {
                $data['section_id'] = $this->input->post('section_id');
            }
            $data['teacher_id']     = $this->db->get_where('subject', array('subject_id' => $this->input->post('subject_id')))->row()->teacher_id;
            $data['subject_id']     = $this->input->post('subject_id');
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['fecha']          = $this->input->post('fecha');
            $data['day']            = $this->input->post('day');
            $data['year']           = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('horarios_examenes', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/looking_routine/', 'refresh');
        }
        if ($param1 == 'update') 
        {
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['day']            = $this->input->post('day');
            $data['year']           = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->where('horario_id', $param2);
            $this->db->update('horarios_examenes', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/looking_routine/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $class_id = $this->db->get_where('horarios_examenes' , array('horario_id' => $param2))->row()->class_id;
            $this->db->where('horario_id', $param2);
            $this->db->delete('horarios_examenes');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/looking_routine/', 'refresh');
        }
    }

    function looking_routine()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $id = $this->input->post('class_id');
        if ($id == '')
        {
            $id = $this->db->get('class')->first_row()->class_id;
        }
        $page_data['page_name']  = 'looking_routine';
        $page_data['id']  =   $id;
        $page_data['page_title'] = get_phrase('exam_routine');
        $this->load->view('backend/index', $page_data);
    }

    function add_exam_routine()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'add_exam_routine';
        $page_data['page_title'] = get_phrase('add_exam_routine');
        $this->load->view('backend/index', $page_data);
    }

    function class_routine_add()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'class_routine_add';
        $page_data['page_title'] = "";
        $this->load->view('backend/index', $page_data);
    }

    function class_routine_view($class_id)
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $id = $this->input->post('class_id');
        if ($id == '')
        {
            $id = $this->db->get('class')->first_row()->class_id;
        }
        $page_data['page_name']  = 'class_routine_view';
        $page_data['id']  =   $id;
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function get_class_section_subject($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/class_routine_section_subject_selector' , $page_data);
    }

    function section_subject_edit($class_id , $class_routine_id)
    {
        $page_data['class_id']          =   $class_id;
        $page_data['class_routine_id']  =   $class_routine_id;
        $this->load->view('backend/admin/class_routine_section_subject_edit' , $page_data);
    }

    function attendance()
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        
        $page_data['page_name']  =  'attendance';
        $page_data['page_title'] =  get_phrase('attendance');
        $this->load->view('backend/index', $page_data);
    }

    function manage_attendance($class_id = '' , $section_id = '' , $timestamp = '')
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['class_id'] = $class_id;
        $page_data['timestamp'] = $timestamp;
        $page_data['page_name'] = 'manage_attendance';
        $section_name = $this->db->get_where('section' , array('section_id' => $section_id))->row()->name;
        $page_data['section_id'] = $section_id;
        $page_data['page_title'] = get_phrase('attendance');
        $this->load->view('backend/index', $page_data);
    }

    function get_sectionss($class_id)
    {
        $sections = $this->db->get_where('section' , array('class_id' => $class_id))->result_array();
        foreach ($sections as $row) 
        {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_section($class_id) 
    {
          $page_data['class_id'] = $class_id; 
          $this->load->view('backend/admin/manage_attendance_section_holder' , $page_data);
    }

     function attendance_report() 
     {
         $page_data['month']        = date('m');
         $page_data['page_name']    = 'attendance_report';
         $page_data['page_title']   = get_phrase('attendance_report');
         $this->load->view('backend/index',$page_data);
     }

     function report_attendance_view($class_id = '' , $section_id = '', $month = '') 
     {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['class_id'] = $class_id;
        $page_data['month']    = $month;
        $page_data['page_name'] = 'report_attendance_view';
        $section_name = $this->db->get_where('section' , array('section_id' => $section_id))->row()->name;
        $page_data['section_id'] = $section_id;
        $page_data['page_title'] = get_phrase('attendance_report');
        $this->load->view('backend/index', $page_data);
     }

    function news_message($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_news_message($this->input->post('codigo'));
        }
    }

    function notice_message($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_notice_message($param2);
        }
    }

    function news($param1 = '', $param2 = '', $param3 = '') 
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'create') 
        {
            $news_code = $this->crud_model->create_news();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/news/', 'refresh');
        }
        if($param1 == 'create_event')
        {
            if($this->crud_model->create_event())
			{
				$this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
			}
			else
			{
				$this->session->set_flashdata('flash_message' , 'Error: Event end time can not be earlier than event start time!!!');
			}
            
            redirect(base_url() . 'admin/news/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            unlink($subdomain.'uploads/news_images/'.$param2. ".jpg");
            $id = $this->db->get_where('news', array('news_code' => $param2))->row()->news_id;
            $this->db->where('news_code' , $param2);
            $this->db->delete('news');
            $this->db->where('news_id' , $id);
            $this->db->delete('mensaje_reporte');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/news/', 'refresh');
        }

        $page_data['page_name'] = 'news';
        $page_data['page_title'] = get_phrase('noticeboard');
        $this->load->view('backend/index', $page_data);
    }

    function update($param1 = '', $param2 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'news') 
        {
            $data['title']               = $this->input->post('title');
            $data['description']         = $this->input->post('description');
            $data['date']                = date('d, M Y');
            $data['users']               = $this->input->post('users');
            $this->db->where('news_code', $param2);
            $this->db->update('news', $data);            
            move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/news_images/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/news/', 'refresh');
        }
        if($param1 == 'event')
        {
			    $startdate =  implode('', array_reverse(explode('/', $this->input->post('from')))); //($this->input->post('from').' 00:00');
				$enddate   = implode('', array_reverse(explode('/', $this->input->post('to')))); //($this->input->post('to').' 00:00');
				if ($enddate >= $startdate)
				{
					$data['title']               = $this->input->post('title');
					$data['description']         = $this->input->post('description');
					$data['date']                = date('d, M Y');
					$data['users']               = $this->input->post('users');
					$data['from_']               = $this->input->post('from');
					$data['to_']                 = $this->input->post('to');
					$this->db->where('news_code', $param2);
					$this->db->update('news', $data);            
					$this->session->set_flashdata('flash_message' , get_phrase('successfully_updated').$startdate.' '.$enddate);
					move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/news_images/' . $param2 . '.jpg');
				}
				else
				{
					$this->session->set_flashdata('flash_message' , 'Error: Event end time can not be earlier than event start time!!!');
				}
					redirect(base_url() . 'admin/news/', 'refresh');
        }
    }


    function create_report($param1 = '', $param2 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'send')
        {
            $parent_id = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->parent_id;
            $student_name = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->name;
            $parent_phone = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->phone;
            $parent_email = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->email;
            $data['student_id'] = $this->input->post('student_id');
            $data['class_id']   = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $one = 'admin';
            $two = $this->session->userdata('login_user_id');
            $data['user_id']    = $one."-".$two;
            $data['title']      = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['file'] = $_FILES["file_name"]["name"];
            $data['date'] = date('d M, Y');
            $data['priority'] = $this->input->post('priority');
            $data['status'] = 0;
            $data['code'] = substr(md5(rand(0, 1000000)), 0, 7);
            $this->db->insert('reports', $data);
            $this->crud_model->students_reports("".$student_name."", "".$parent_email."");
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain.'uploads/report_files/'. $_FILES["file_name"]["name"]);

            $notify = $this->db->get_where('settings' , array('type' => 'students_reports'))->row()->description;
            if($notify == 1)
            {
              $message = "A behavioral report has been created for " . $student_name;
              require_once 'smsGateway.php';
              $email = $this->db->get_where('settings' , array('type' => 'android_email'))->row()->description;
              $pass   = $this->db->get_where('settings' , array('type' => 'android_password'))->row()->description;    
              $device   = $this->db->get_where('settings' , array('type' => 'android_device'))->row()->description;    
              $object = new SmsGateway($email, $pass);
              $sms_status = $this->db->get_where('settings' , array('type' => 'sms_status'))->row()->description;

              if ($sms_status == 'android') 
              {
                 $result = $object->sendMessageToNumber($parent_phone, $message, $device);
              }
              else if ($sms_status == 'twilio') 
              {
                  $this->crud_model->twilio($message,"".$parent_phone."");
              }
              else if ($sms_status == 'clickatell') 
              {
                  $this->crud_model->clickatell($message,$parent_phone);
              }
            }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/request_student/', 'refresh');
        }
        if($param1 == 'response')
        {
            $data['report_code'] = $this->input->post('report_code');
            $data['message'] = $this->input->post('message');
            $data['date'] = date('d M, Y');
            $data['sender_type'] = $this->session->userdata('login_type');
            $data['sender_id'] = $this->session->userdata('login_user_id');
            $this->db->insert('report_response', $data);
        }
        if($param1 == 'update')
        {
            $data['status'] = 1;
            $this->db->where('code', $param2);
            $this->db->update('reports', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/looking_report/'.$param2, 'refresh');
        }
		
		if($param1 == 'approve')
        {
            $data['approved'] = 1;
            $this->db->where('code', $param2);
            $this->db->update('reports', $data);
			$this->crud_model->students_reports("".$student_name."", "".$parent_email."");
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain.'uploads/report_files/'. $_FILES["file_name"]["name"]);

            $notify = $this->db->get_where('settings' , array('type' => 'students_reports'))->row()->description;
            if($notify == 1)
            {
              $message = "A behavioral report has been created for " . $student_name;
              require_once 'smsGateway.php';
              $email = $this->db->get_where('settings' , array('type' => 'android_email'))->row()->description;
              $pass   = $this->db->get_where('settings' , array('type' => 'android_password'))->row()->description;    
              $device   = $this->db->get_where('settings' , array('type' => 'android_device'))->row()->description;    
              $object = new SmsGateway($email, $pass);
              $sms_status = $this->db->get_where('settings' , array('type' => 'sms_status'))->row()->description;

              if ($sms_status == 'android') 
              {
                 $result = $object->sendMessageToNumber($parent_phone, $message, $device);
              }
              else if ($sms_status == 'twilio') 
              {
                  $this->crud_model->twilio($message,"".$parent_phone."");
              }
              else if ($sms_status == 'clickatell') 
              {
                  $this->crud_model->clickatell($message,$parent_phone);
              }
            }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/looking_report/'.$param2, 'refresh');
        }
    }

    function update_events($code)
    {
        if ($this->session->userdata('admin_login') != 1)
         {
            redirect(base_url(), 'refresh');
         }
        $page_data['page_name']  = 'update_events';
        $page_data['page_title'] = get_phrase('update_event');
        $page_data['code']   = $code;
        $this->load->view('backend/index', $page_data); 
    }

    function send_news() 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name'] = 'enviar_noticia';
        $page_data['page_title'] = get_phrase('send_news');
        $this->load->view('backend/index', $page_data);
    }

    function attendance_report_selector()
    {
       if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $data['class_id']   = $this->input->post('class_id');
        $data['year']       = $this->input->post('year');
        $data['month']  = $this->input->post('month');
        $data['section_id'] = $this->input->post('section_id');
        redirect(base_url().'admin/report_attendance_view/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['month'],'refresh');
    }

    function read($code = "")
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'read';
        $page_data['page_title'] = get_phrase('noticeboard');
        $page_data['code']   = $code;
        $this->load->view('backend/index', $page_data); 
    }

    function unit_content()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $id = $this->input->post('class_id');
        if ($id == '')
        {
            $id = $this->db->get('class')->first_row()->class_id;
        }
        $page_data['id']   = $id;
        $page_data['page_name']  = 'unit_content';
        $page_data['page_title'] = get_phrase('syllabus');
        $this->load->view('backend/index', $page_data);
    }

    function upload_unit_content()
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'xlsx', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', '.mp3', 'wav', 'mp4', 'mov', 'wmv', 'txt'); // Allowed file extensions
        $fileParts = pathinfo($_FILES['file_name']['name']);
        if (in_array(strtolower($fileParts['extension']), $fileTypes)) 
        {
            
            $data['academic_syllabus_code'] =   substr(md5(rand(0, 1000000)), 0, 7);
            $data['title']                  =   $this->input->post('title');
            $data['class_id']               =   $this->input->post('class_id');
            $data['subject_id']             =   $this->input->post('subject_id');
            $data['file_type']              =   $this->input->post('file_type');
            $data['uploader_type']          =   $this->session->userdata('login_type');
            $data['uploader_id']            =   $this->session->userdata('login_user_id');
            $data['year']                   =   $this->db->get_where('settings',array('type'=>'running_year'))->row()->description;
            $data['date']              =   date("d M, Y");
            $files = $_FILES['file_name'];
            $this->load->library('upload');
            $config['upload_path']   =  $subdomain.'uploads/syllabus/';
            $config['allowed_types'] =  '*';
            $_FILES['file_name']['name']     = $files['name'];
            $_FILES['file_name']['type']     = $files['type'];
            $_FILES['file_name']['tmp_name'] = $files['tmp_name'];
            $_FILES['file_name']['size']     = $files['size'];
            $this->upload->initialize($config);
            $this->upload->do_upload('file_name');
            $data['file_name'] = $_FILES['file_name']['name'];
            $this->db->insert('academic_syllabus', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_uploaded'));
            redirect(base_url() . 'admin/unit_content/' , 'refresh');
        } 
        else 
        {
            $this->session->set_flashdata('error_message' , "Extension not allowed.");
            redirect(base_url() . 'admin/unit_content' , 'refresh');
        }
    }

    function download_unit_content($academic_syllabus_code)
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        $file_name = $this->db->get_where('academic_syllabus', array('academic_syllabus_code' => $academic_syllabus_code))->row()->file_name;
        $this->load->helper('download');
        $data = file_get_contents($subdomain."uploads/syllabus/" . $file_name);
        $name = $file_name;
        force_download($name, $data);
    }

    function delete_unit_content($academic_syllabus_id)
    {
         if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $this->crud_model->delete_unit($academic_syllabus_id);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
        redirect(base_url() . 'admin/unit_content/', 'refresh');
    }

    function students_payments($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        
        $page_data['page_name']  = 'students_payments';
        $page_data['page_title'] = get_phrase('student_payments');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data); 
    }

    function payments($param1 = '' , $param2 = '' , $param3 = '') 
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        $page_data['page_name']  = 'payments';
        $page_data['page_title'] = get_phrase('payments');
        $this->load->view('backend/index', $page_data); 
    }

    function expense($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
			$originalDate =$this->input->post('timestamp');
			$originalDate = str_replace('/', '-', $originalDate);
			$newDate = date("d-m-Y", strtotime($originalDate));
			$data['timestamp']  = strtotime($newDate);
            //$data['timestamp']           =   strtotime($this->input->post('timestamp'));
            $data['year']                =   $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));

            redirect(base_url() . 'admin/expense', 'refresh');
        }
        if ($param1 == 'edit') 
        {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
            $data['year']                =   $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->where('payment_id' , $param2);
            $this->db->update('payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/expense', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->db->where('payment_id' , $param2);
            $this->db->delete('payment');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/expense/', 'refresh');
        }
        $page_data['page_name']  = 'expense';
        $page_data['page_title'] = get_phrase('expense');
        $this->load->view('backend/index', $page_data); 
    }

    function expense_category($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }

        if ($param1 == 'create') {
            $data['name']   =   $this->input->post('name');
            $this->db->insert('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/expense');
        }
        if ($param1 == 'update') {
            $data['name']   =   $this->input->post('name');
            $this->db->where('expense_category_id' , $param2);
            $this->db->update('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/expense');
        }
        if ($param1 == 'delete') {
            $this->db->where('expense_category_id' , $param2);
            $this->db->delete('expense_category');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/expense');
        }
        $page_data['page_name']  = 'expense';
        $page_data['page_title'] = get_phrase('expense');
        $this->load->view('backend/index', $page_data);
    }

     function teacher_attendance()
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $page_data['page_name']  =  'teacher_attendance';
        $page_data['page_title'] =  get_phrase('teacher_attendance');
        $this->load->view('backend/index', $page_data);
    }

    function teacher_attendance_report() 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
         $page_data['month']        =  date('m');
         $page_data['page_name']    = 'teacher_attendance_report';
         $page_data['page_title']   = get_phrase('teacher_attendance_report');
         $this->load->view('backend/index',$page_data);
     }

    function teacher_report_selector()
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $data['year']       = $this->input->post('year');
        $data['month']      = $this->input->post('month');
        $this->session->set_flashdata('flash_message' , "Information generated Successfully");
        redirect(base_url().'admin/teacher_report_view/'.$data['month'],'refresh');
    }

    function teacher_report_view($month = '') 
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $page_data['month']    = $month;
        $page_data['page_name'] = 'teacher_report_view';
        $page_data['page_title'] = get_phrase('teacher_attendance_report');
        $this->load->view('backend/index', $page_data);
     }

    function attendance_teacher()
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $data['year']       = $this->input->post('year');
        $str = $this->input->post('timestamp');
        $originalDate =$this->input->post('timestamp');
		$originalDate = str_replace('/', '-', $originalDate);
        $newDate = date("d-m-Y", strtotime($originalDate));
        $data['timestamp']  = strtotime($newDate);
        $query = $this->db->get_where('teacher_attendance' ,array('year'=>$data['year'],'timestamp'=>$data['timestamp']));
        if($query->num_rows() < 1) 
        {
            $teacher = $this->db->get_where('teacher')->result_array();
            foreach($teacher as $row) 
            {
                $attn_data['teacher_id']   = $row['teacher_id'];
                $attn_data['year']       = $data['year'];
                $attn_data['timestamp']  = $data['timestamp'];
                $this->db->insert('teacher_attendance' , $attn_data);  
            }
        }
        redirect(base_url().'admin/teacher_attendance_view/'. $data['timestamp'],'refresh');
    }

    function attendance_update2($timestamp = '')
    {
         if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $attendance_of = $this->db->get_where('teacher_attendance' , array('year'=>$running_year,'timestamp'=>$timestamp))->result_array();
        foreach($attendance_of as $row) 
        {
            $attendance_status = $this->input->post('status_'.$row['attendance_id']);
            $this->db->where('attendance_id' , $row['attendance_id']);
            $this->db->update('teacher_attendance' , array('status' => $attendance_status));
        }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
        redirect(base_url().'admin/teacher_attendance_view/'.$timestamp , 'refresh');
    }


    function teacher_attendance_view($timestamp = '')
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $page_data['timestamp'] = $timestamp;
        $page_data['page_name'] = 'teacher_attendance_view';
        $page_data['page_title'] = get_phrase('teacher_attendance');
        $this->load->view('backend/index', $page_data);
    }

    function school_bus($param1 = '', $param2 = '', $param3 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['driver_name'] = $this->input->post('driver_name');
            $data['driver_phone'] = $this->input->post('driver_phone');
			$data['supervisor_name'] = $this->input->post('supervisor_name');
            $data['supervisor_phone'] = $this->input->post('supervisor_phone');
            $data['route']        = $this->input->post('route');
            $data['route_fare']        = $this->input->post('route_fare');
            $this->db->insert('transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
			$superuploads  = $subdomain.'uploads/supervisor_image';
			$driveruploads = $subdomain.'uploads/driver_image';
			
			if (!file_exists($superuploads))
			{
				if (!mkdir($superuploads, 0755, true))
			         die('Failed to create Supervisor Upload Area...');
		    }
			if (!file_exists($driveruploads))
			{
				if (!mkdir($driveruploads, 0755, true))
			         die('Failed to create Supervisor Upload Area...');
		    }
			move_uploaded_file($_FILES['supervisorfile']['tmp_name'], $subdomain.'uploads/supervisor_image/' .$data['supervisor_name']. '.jpg');
			move_uploaded_file($_FILES['driverfile']['tmp_name'], $subdomain.'uploads/driver_image/' . $data['driver_name'] . '.jpg');
            redirect(base_url() . 'admin/school_bus/', 'refresh');
        }
        if ($param1 == 'update') 
        {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['driver_name'] = $this->input->post('driver_name');
            $data['driver_phone'] = $this->input->post('driver_phone');
			$data['supervisor_name'] = $this->input->post('supervisor_name');
            $data['supervisor_phone'] = $this->input->post('supervisor_phone');
            $data['route']        = $this->input->post('route');
            $data['route_fare']        = $this->input->post('route_fare');
            $this->db->where('transport_id', $param2);
            $this->db->update('transport', $data);
			$superuploads  = $subdomain.'uploads/supervisor_image';
			$driveruploads = $subdomain.'uploads/driver_image';
			if (!file_exists($superuploads))
			{
				if (!mkdir($superuploads, 0755, true))
			         die('Failed to create Supervisor Upload Area...');
		    }
			if (!file_exists($driveruploads))
			{
				if (!mkdir($driveruploads, 0755, true))
			         die('Failed to create Supervisor Upload Area...');
		    }
			move_uploaded_file($_FILES['supervisorfile']['tmp_name'], $subdomain.'uploads/supervisor_image/' .$data['supervisor_name']. '.jpg');
			move_uploaded_file($_FILES['driverfile']['tmp_name'], $subdomain.'uploads/driver_image/' . $data['driver_name'] . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/school_bus', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('transport_id', $param2);
            $this->db->delete('transport');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/school_bus/', 'refresh');
        }
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'school_bus';
        $page_data['page_title'] = get_phrase('school_bus');
        $this->load->view('backend/index', $page_data); 
    }

    function classrooms($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        if ($param1 == 'create') 
        {
            $data['name']           = $this->input->post('name');
            $data['number']         = $this->input->post('number');
            $this->db->insert('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'admin/classrooms/', 'refresh');
        }
        if ($param1 == 'update') 
        {
            $data['name']           = $this->input->post('number');
            $data['number'] = $this->input->post('name');
            $this->db->where('dormitory_id', $param2);
            $this->db->update('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/classrooms/', 'refresh');
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('dormitory_id', $param2);
            $this->db->delete('dormitory');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/classrooms/', 'refresh');
        }
        $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
        $page_data['page_name']   = 'classroom';
        $page_data['page_title']  = get_phrase('classrooms');
        $this->load->view('backend/index', $page_data);
    }

    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        if ($param1 == 'do_update') 
        {
            //$data['description'] = $this->input->post('system_name');
            //$this->db->where('type' , 'system_name');
           // $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('register');
            $this->db->where('type' , 'register');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_title');
            $this->db->where('type' , 'system_title');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('address');
            $this->db->where('type' , 'address');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('phone');
            $this->db->where('type' , 'phone');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('language');
            $this->db->where('type' , 'language');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('currency');
            $this->db->where('type' , 'currency');
            $this->db->update('settings' , $data);
 
            $data['description'] = $this->input->post('paypal_email');
            $this->db->where('type' , 'paypal_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_email');
            $this->db->where('type' , 'system_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('rtl');
            $this->db->where('type' , 'rtl');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('running_year');
            $this->db->where('type' , 'running_year');
            $this->db->update('settings' , $data);
        
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/system_settings/', 'refresh');
        }
        if($param1 == 'skin')
        {
            $data['description'] = $this->input->post('skin');
            $this->db->where('type' , 'skin');
            $this->db->update('settings' , $data);

            move_uploaded_file($_FILES['slide1']['tmp_name'], $subdomain.'uploads/slider/slider1.png');
            move_uploaded_file($_FILES['slide2']['tmp_name'], $subdomain.'uploads/slider/slider2.png');
            move_uploaded_file($_FILES['slide3']['tmp_name'], $subdomain.'uploads/slider/slider3.png');
            move_uploaded_file($_FILES['favicon']['tmp_name'], $subdomain.'uploads/favicon.png');
            move_uploaded_file($_FILES['logow']['tmp_name'], $subdomain.'uploads/logo-white.png');
            move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/logo.png');
            move_uploaded_file($_FILES['avatar']['tmp_name'], $subdomain.'uploads/user.jpg');
            move_uploaded_file($_FILES['bglogin']['tmp_name'], $subdomain.'uploads/bglogin.jpg');
            move_uploaded_file($_FILES['logocolor']['tmp_name'], $subdomain.'uploads/logo-color.png');
            move_uploaded_file($_FILES['icon_white']['tmp_name'], $subdomain.'uploads/logo-icon.png');
            
           $this->crud_model->clear_cache();

            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/system_settings/', 'refresh');
        }
        if($param1 == 'social')
        {
            $data['description'] = $this->input->post('facebook');
            $this->db->where('type' , 'facebook');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twitter');
            $this->db->where('type' , 'twitter');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('instagram');
            $this->db->where('type' , 'instagram');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('youtube');
            $this->db->where('type' , 'youtube');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/system_settings/', 'refresh');
        }
        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }


   function prueba()
   {
      
     
   }

    function academic_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'do_update') 
        {
            $data['description'] = $this->input->post('report_teacher');
            $this->db->where('type' , 'report_teacher');
            $this->db->update('academic_settings' , $data);

            $data['description'] = $this->input->post('minium_mark');
            $this->db->where('type' , 'minium_mark');
            $this->db->update('academic_settings' , $data);

            $data['description'] = $this->input->post('routine');
            $this->db->where('type' , 'routine');
            $this->db->update('academic_settings' , $data);

            $data['description'] = $this->input->post('tabulation');
            $this->db->where('type' , 'tabulation');
            $this->db->update('academic_settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'admin/academic_settings/', 'refresh');
        }

        $page_data['page_name']  = 'academic_settings';
        $page_data['page_title'] = get_phrase('academic_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function library($param1 = '', $param2 = '', $param3 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') 
        {
            $fileTypes = array('pdf', 'doc', 'docx', '.mp3', 'wav', 'mp4', 'mov', 'wmv', 'txt'); // Allowed file extensions
            $fileParts = pathinfo($_FILES['file_name']['name']);
            if($this->input->post('type')  == 'virtual')
            {
                if (in_array(strtolower($fileParts['extension']), $fileTypes)) 
                {               
                    $data['name']        = $this->input->post('name');
                    $data['description'] = $this->input->post('description');
                    $data['price']       = $this->input->post('price');
                    $data['author']      = $this->input->post('author');
                    $data['class_id']    = $this->input->post('class_id');
                    $data['type']        = $this->input->post('type');
                    $data['file_name']   = $_FILES["file_name"]["name"];
                    $data['status']      = $this->input->post('status');
                    move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/library/" . $_FILES["file_name"]["name"]);
                    $this->db->insert('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_uploaded'));
                    redirect(base_url() . 'admin/library', 'refresh');
                } 
                else 
                {
                    $this->session->set_flashdata('error_message' , "Extension not allowed.");
                    redirect(base_url() . 'admin/unit_content' , 'refresh');
                }
            }else
            {
                $data['name']        = $this->input->post('name');
                $data['description'] = $this->input->post('description');
                $data['price']       = $this->input->post('price');
                $data['author']      = $this->input->post('author');
                $data['class_id']    = $this->input->post('class_id');
                $data['type']        = $this->input->post('type');
                $data['status']      = $this->input->post('status');
                $this->db->insert('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
                redirect(base_url() . 'admin/library', 'refresh');
            }
        }
        if ($param1 == 'update') 
        {
            $fileTypes = array('pdf', 'doc', 'docx', '.mp3', 'wav', 'mp4', 'mov', 'wmv', 'txt'); // Allowed file extensions
            $fileParts = pathinfo($_FILES['file_name']['name']);
            if($this->input->post('type')  == 'virtual')
            {
                if (in_array(strtolower($fileParts['extension']), $fileTypes)) 
                {               
                    $data['name']        = $this->input->post('name');
                    $data['description'] = $this->input->post('description');
                    $data['price']       = $this->input->post('price');
                    $data['author']      = $this->input->post('author');
                    $data['class_id']    = $this->input->post('class_id');
                    $data['type']        = $this->input->post('type');
                    $data['file_name']   = $_FILES["file_name"]["name"];
                    $data['status']      = $this->input->post('status');
                    move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/library/" . $_FILES["file_name"]["name"]);
                    $this->db->where('book_id', $param2);
                    $this->db->update('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
                    redirect(base_url() . 'admin/update_book/'.$param2, 'refresh');
                } 
                else 
                {
                    $this->session->set_flashdata('error_message' , "No extension allowed.");
                    redirect(base_url() . 'admin/update_book/'.$param2, 'refresh');
                }
            }else
            {
                $data['name']        = $this->input->post('name');
                $data['description'] = $this->input->post('description');
                $data['price']       = $this->input->post('price');
                $data['author']      = $this->input->post('author');
                $data['class_id']    = $this->input->post('class_id');
                $data['type']        = $this->input->post('type');
                $data['status']      = $this->input->post('status');
                $this->db->where('book_id', $param2);
                $this->db->update('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
                redirect(base_url() . 'admin/update_book/'.$param2, 'refresh');
            }
        }
        if ($param1 == 'delete') 
        {
            $this->db->where('book_id', $param2);
            $this->db->delete('book');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/library', 'refresh');
        }
        $id = $this->input->post('class_id');
        if ($id == '')
        {
            $id = $this->db->get('class')->first_row()->class_id;
        }
        $page_data['id']  = $id;
        $page_data['page_name']  = 'library';
        $page_data['page_title'] = get_phrase('library');
        $this->load->view('backend/index', $page_data);
    }

     function marks_print_view($student_id , $exam_id) 
     {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect('login', 'refresh');
        }
        $class_id     = $this->db->get_where('enroll' , array(
            'student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->row()->class_id;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;

        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $page_data['exam_id']    =   $exam_id;
        $this->load->view('backend/admin/marks_print_view', $page_data);
    }

    function files($task = "", $code = "")
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('admin_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }       
        if ($task == 'create')
        {
            $this->crud_model->teacher_files();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_uploaded'));
            redirect(base_url() . 'admin/files' , 'refresh');
        }
        if($task == 'download')
        {
            $file_name = $this->db->get_where('teacher_files', array('file_code' => $code))->row()->file;
            $this->load->helper('download');
            $data = file_get_contents($subdomain."uploads/teacher_files/" . $file_name);
            $name = $file_name;
            force_download($name, $data);
        }
        if ($task == 'delete')
        {
            $file_name = $this->db->get_where('teacher_files', array('file_code' => $code))->row()->file;
            $this->db->where('file_code',$code);
            $this->db->delete('teacher_files');
            unlink($subdomain.'uploads/teacher_files/'.$file_name);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'admin/files');
        }

        $data['page_name']              = 'files';
        $data['page_title']             = get_phrase('teacher_files');
        $this->load->view('backend/index', $data);
    }


}