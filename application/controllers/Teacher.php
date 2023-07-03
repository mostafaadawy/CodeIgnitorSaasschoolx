<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Teacher extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    public function index()
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($this->session->userdata('teacher_login') == 1)
        {
            redirect(base_url() . 'teacher/teacher_dashboard/', 'refresh');
        }
    }


    function group($param1 = "group_message_home", $param2 = ""){
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
      if ($this->session->userdata('teacher_login') != 1)
          redirect(base_url(), 'refresh');
      $max_size = 2097152;

      if ($param1 == 'group_message_read') 
      {
        $page_data['current_message_thread_code'] = $param2;
      }
      else if($param1 == 'send_reply')
      {
        if (!file_exists($subdomain.'uploads/group_messaging_attached_file/')) 
        {
          $oldmask = umask(0);
          mkdir ($subdomain.'uploads/group_messaging_attached_file/', 0777);
        }
        if ($_FILES['attached_file_on_messaging']['name'] != "") 
        {
          if($_FILES['attached_file_on_messaging']['size'] > $max_size)
          {
            $this->session->set_flashdata('error_message' , "2MB Allowed");
            redirect(base_url() . 'teacher/group/group_message_read/'.$param2, 'refresh');
          }
          else
          {
            $file_path = $subdomain.'uploads/group_messaging_attached_file/'.$_FILES['attached_file_on_messaging']['name'];
            move_uploaded_file($_FILES['attached_file_on_messaging']['tmp_name'], $file_path);
          }
        }

        $this->crud_model->send_reply_group_message($param2);
        $this->session->set_flashdata('flash_message', get_phrase('message_sent'));
        redirect(base_url() . 'teacher/group/group_message_read/'.$param2, 'refresh');
      }
      $page_data['message_inner_page_name']   = $param1;
      $page_data['page_name']                 = 'group';
      $page_data['page_title']                = get_phrase('message_group');
      $this->load->view('backend/index', $page_data);
    }
	
	 function view_attendance($student_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $year =  $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' =>$year))->row()->class_id;
        $page_data['class_id']   =   $class_id;
        $page_data['page_name']  = 'student_attendance_report';
        $page_data['page_title'] = get_phrase('view_marks');
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);    
    }

    
    function view_marks($student_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $year =  $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' =>$year))->row()->class_id;
        $page_data['class_id']   =   $class_id;
        $page_data['page_name']  = 'view_marks';
        $page_data['page_title'] = get_phrase('view_marks');
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);    
    }
	
	function view_behavior($student_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $year =  $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' =>$year))->row()->class_id;
		$section_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' =>$year))->row()->section_id;
        $page_data['class_id']   =   $class_id;
		$page_data['section_id']   =   $section_id;
        $page_data['page_name']  = 'student_behavior';
        $page_data['page_title'] = get_phrase('view_marks');
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);    
    }
	

    function polls($param1 = '', $param2 = '')
    {
      if ($this->session->userdata('teacher_login') != 1)
      {
            redirect(base_url(), 'refresh');
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
    }

    function my_routine()
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'my_routine';
        $page_data['page_title'] = get_phrase('teacher_routine');
        $this->load->view('backend/index', $page_data);
    }


    function student_report($param1 = '', $param2 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('teacher_login') != 1)
        {
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
            $one = 'teacher';
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
            
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain.'uploads/report_files/'. $_FILES["file_name"]["name"]);

            /*
			$this->crud_model->students_reports("".$student_name."", "".$parent_email."");
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
            }*/
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'teacher/student_report/', 'refresh');
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
        $page_data['page_name']  = 'student_report';
        $page_data['page_title'] = get_phrase('reports');
        $this->load->view('backend/index', $page_data);
    }

     function view_report($report_code = '') 
    {
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['code'] = $report_code;
        $page_data['page_name'] = 'view_report';
        $page_data['page_title'] = get_phrase('report_details');
        $this->load->view('backend/index', $page_data);
    }

    function noticeboard()
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('noticeboard');
        $this->load->view('backend/index', $page_data);
    }

    function courses($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
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
            redirect(base_url() . 'teacher/marks_upload/'.$this->input->post('exam_id')."/".$class_id."/".$this->input->post('section_id')."/".$param2, 'refresh');
        }
        $page_data['class_id']   = $param1;
        $page_data['subjects']   = $this->db->get_where('subject' , array('class_id' => $param1))->result_array();
        $page_data['page_name']  = 'coursess';
        $page_data['page_title'] = get_phrase('subjects');
        $this->load->view('backend/index', $page_data);
    }
    
    function teacher_dashboard()
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'teacher_dashboard';
        $page_data['page_title'] = get_phrase('dashboard');
        $this->load->view('backend/index', $page_data);
    }
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 function calendar()
    {
        if ($this->session->userdata('teacher_login') != 1)
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
    }
	
	function calendar_get_events() 
    {
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
	///////////////////////////////////////////////

    function tab_sheet($class_id = '' , $section_id = '', $exam_id = '', $running_year = '') 
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        if ($this->input->post('operation') == 'selection') 
        {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
			$page_data['section_id']   = $this->input->post('section_id');
			$page_data['running_year']   = $this->input->post('running_year');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0  ) 
            {
                redirect(base_url() . 'teacher/tab_sheet/' . $page_data['class_id'].'/'. $page_data['section_id']  . '/' . $page_data['exam_id'] .'/'.$page_data['running_year'], 'refresh');
            } else {
                redirect(base_url() . 'teacher/tab_sheet/', 'refresh');
            }
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
		$page_data['section_id']   = $section_id;
		$page_data['running_year']   = $running_year;
        $page_data['page_info'] = 'Exam marks';
        $page_data['page_name']  = 'tab_sheet';
        $page_data['page_title'] = get_phrase('tabulation_sheet');
        $this->load->view('backend/index', $page_data);
    }

    function tab_sheet_print($class_id , $exam_id) 
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['class_id'] = $class_id;
        $page_data['exam_id']  = $exam_id;
        $this->load->view('backend/teacher/tab_sheet_print' , $page_data);
    }

    function get_class_section($class_id)
    {
        $sections = $this->db->get_where('section' , array('class_id' => $class_id))->result_array();
		echo '<option value="">' . get_phrase('select') . '</option>';
        foreach ($sections as $row) 
        {
            echo '<option value="' . $row['section_id'].'">' . $row['name'] . '</option>';
        }
    }
    
    function get_class_subject($class_id) 
    {
        $subject = $this->db->get_where('subject' , array('class_id' => $class_id))->result_array();
        foreach ($subject as $row) 
        {
            if ($this->session->userdata('login_user_id') == $row['teacher_id'])
            {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
            }
        }
    }
	
	function get_class_section_subject($class_id, $section_id) 
    {
        $subject = $this->db->get_where('subject' , array('class_id' => $class_id , 'section_id' => $section_id))->result_array();
        foreach ($subject as $row) 
        {
            if ($this->session->userdata('login_user_id') == $row['teacher_id'])
            {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
            }
        }
    }
	
	function get_classes_by_subject($subject_id) 
    {
        $subjects = $this->db->get_where('subject' , array( 'subject_id' => $subject_id , 'teacher_id'=>$this->session->userdata('login_user_id')))->result_array();
        foreach ($subjects as $row) 
        {
			$class = $this->db->get_where('class' , array( 'class_id' => $row['class_id']))->row()->name;
            echo '<option value="' . $row['class_id'] . '">' . $class . '</option>';
        }
    }

    function upload_unit_content()
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'xlsx', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', '.mp3', 'wav', 'mp4', 'mov', 'wmv', 'txt');
        $fileParts = pathinfo($_FILES['file_name']['name']);
        if (in_array(strtolower($fileParts['extension']), $fileTypes)) 
        {
            
            $data['academic_syllabus_code'] =   substr(md5(rand(0, 1000000)), 0, 7);
            $data['title']                  =   $this->input->post('title');
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
			
			$classes          = $this->input->post('class_id');
			$class_entries = sizeof($classes);
            for($i = 0; $i < $class_entries; $i++) 
            {
				$data['class_id']    = $classes[$i];
                $this->db->insert('academic_syllabus', $data);
			}
            $this->session->set_flashdata('flash_message' , "Archive Uploaded Successfully");
            redirect(base_url() . 'teacher/unit_content/' , 'refresh');
        } 
        else 
        {
            $this->session->set_flashdata('error_message' , "Invalid extension.");
            redirect(base_url() . 'teacher/unit_content' , 'refresh');
        }
    }

    function teacher_list($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'personal_profile') 
        {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teachers';
        $page_data['page_title'] = get_phrase('teachers');
        $this->load->view('backend/index', $page_data);
    }

    function students_area($class_id)
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect('login', 'refresh');
        }
        $class_id = $this->input->post('class_id');
		
        if ($class_id == '')
        {
            $class_id = $this->db->get('class')->first_row()->class_id;
        }
        $page_data['page_name']   = 'students_area';
        $page_data['page_title']  = get_phrase('students');
        $page_data['class_id']  = $class_id;
        $this->load->view('backend/index', $page_data);
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
        if($this->session->userdata('teacher_login')!=1)
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
	 
	  function attendance_report_selector()
    {
       if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $data['class_id']   = $this->input->post('class_id');
        $data['year']       = $this->input->post('year');
        $data['month']  = $this->input->post('month');
        $data['section_id'] = $this->input->post('section_id');
        redirect(base_url().'teacher/report_attendance_view/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['month'],'refresh');
    }
	
	function student_attendance_report_selector()
     {
        if($this->session->userdata('teacher_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $data['class_id']   = $this->db->get_where('enroll', array('student_id' => $this->input->post('student_id')))->row()->class_id;
        $data['section_id']   = $this->db->get_where('enroll', array('student_id' => $this->input->post('student_id')))->row()->section_id;
        $data['year']       = $this->input->post('year');
        $data['student_id'] = $this->input->post('student_id');
        $data['month']  = $this->input->post('month');
        redirect(base_url().'teacher/student_report_attendance_view/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['student_id'].'/'.$data['month'],'refresh');
    }
	
	function student_report_attendance_view($class_id = '' , $section_id = '', $student_id = '', $month = '' , $param1 = '') 
     {
        if($this->session->userdata('teacher_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['class_id'] = $class_id;
        $page_data['month']    = $month;
		$page_data['student_id'] = $student_id;
        $page_data['page_name'] = 'student_report_attendance_view';
        $section_name = $this->db->get_where('section' , array('section_id' => $section_id))->row()->name;
        $page_data['section_id'] = $section_id;
        $page_data['page_title'] = get_phrase('attendance_report');
        $this->load->view('backend/index', $page_data);
     }

	

    function subject($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
	$page_data['class_id']   = $param1;
        $page_data['subjects']   = $this->db->get_where('subject' , array('class_id' => $param1,
            'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('subjects');
        $this->load->view('backend/index', $page_data);
    }
    
    function exam_routine($class_id)
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'viendo_horarios';
        $page_data['class_id']  =   $class_id;
        $page_data['page_title'] = get_phrase('exam_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    function upload_marks()
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  =   'upload_marks';
        $page_data['page_title'] = get_phrase('upload_marks');
        $this->load->view('backend/index', $page_data);
    }

    function marks_upload($exam_id = '' , $class_id = '' , $section_id = '' , $subject_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
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
        if ($this->session->userdata('teacher_login') != 1)
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
            $verify_data = array('exam_id' => $data['exam_id'],'class_id' => $data['class_id'],'section_id' => $data['section_id'],
            'student_id' => $row['student_id'],'subject_id' => $data['subject_id'], 'year' => $data['year']);
			
			$type = $this->db->get_where('subject' , array('subject_id' => $data['subject_id'], 'class_id' =>  $data['class_id'] ,'section_id' => $data['section_id'] , 'year' => $data['year']))->row()->type;
			$students = $this->db->get_where('subject' , array('subject_id' => $data['subject_id'], 'class_id' =>  $data['class_id'] ,'section_id' => $data['section_id'] , 'year' => $data['year']))->row()->students;
			if(!$type == 1  || strpos($students ,'"'.$row['student_id'].'"' )!= false )
			{
				$query = $this->db->get_where('mark' , $verify_data);
				if($query->num_rows() < 1) 
					{   
							$data['student_id'] = $row['student_id'];
							$this->db->insert('mark' , $data);
					}
			}
			

            
        }
        redirect(base_url() . 'teacher/marks_upload/' . $data['exam_id'] . '/' . $data['class_id'] . '/' . $data['section_id'] . '/' . $data['subject_id'], 'refresh');
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
            $labonueve = $this->input->post('lab_nueve_'.$row['mark_id']);
            $comment = $this->input->post('comment_'.$row['mark_id']);
            $labototal = $obtained_marks + $labouno + $labodos + $labotres + $labocuatro + $labocinco + $laboseis + $labosiete + $laboocho + $labonueve + $labfinal;
            $this->db->where('mark_id' , $row['mark_id']);
            $this->db->update('mark' , array('mark_obtained' => $obtained_marks , 'labuno' => $labouno
            , 'labdos' => $labodos, 'labtres' => $labotres, 'labcuatro' => $labocuatro, 'labcinco' => $labocinco, 'labseis' => $laboseis
                , 'labsiete' => $labosiete, 'labocho' => $laboocho, 'labnueve' => $labonueve, 'labtotal' => $labototal, 'comment' => $comment));
        }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
        redirect(base_url().'teacher/marks_upload/'.$exam_id.'/'.$class_id.'/'.$section_id.'/'.$subject_id , 'refresh');
    }

    function subject_marks($data) 
     {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['data'] = $data;
        $page_data['page_name']    = 'subject_marks';
        $page_data['page_title']   = get_phrase('subject_marks');
        $this->load->view('backend/index',$page_data);
     }

    function files($task = "", $id_poa = "")
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('teacher_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }       
        if($task == 'download')
        {
            $file_name = $this->db->get_where('teacher_files', array('file_code' => $id_poa))->row()->file;
            $this->load->helper('download');
            $data = file_get_contents($subdomain."uploads/teacher_files/" . $file_name);
            $name = $file_name;
            force_download($name, $data);
        }
        $data['page_name']              = 'files';
        $data['page_title']             = get_phrase('teacher_files');
        $this->load->view('backend/index', $data);
    }

    function news_message($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_news_message($this->input->post('news_code'));
        }
    }

    function notice_message($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_notice_message($param2);
        }
    }

    function marks_get_subject($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/teacher/marks_get_subject' , $page_data);
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
            redirect(base_url() . 'teacher/homeworkroom/' . $homework_code , 'refresh');
        }
        if($param1 == 'update')
        {
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['time_end'] = $this->input->post('time_end');
            $data['date_end'] = $this->input->post('date_end');
            $data['type'] = $this->input->post('type');
            $this->db->where('homework_code', $param2);
            $this->db->update('homework', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'teacher/homework_edit/' . $param2 , 'refresh');
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
            redirect(base_url() . 'teacher/homework_details/' . $param2 , 'refresh');
        }
        if($param1 == 'single')
        {
            $data['teacher_comment'] = $this->input->post('comment');
            $data['mark'] = $this->input->post('mark');
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('deliveries', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'teacher/single_homework/' . $this->input->post('id') , 'refresh');
        }
        if ($param1 == 'edit') 
        {
            $this->crud_model->update_homework($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'teacher/homeworkroom/edit/' . $param2 , 'refresh');
        }
        if ($param1 == 'delete')
        {
            $this->crud_model->delete_homework($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'teacher/homework', 'refresh');
        }

        $page_data['page_name'] = 'homework';
        $page_data['page_title'] = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

    function unit_content($class_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
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
        $this->crud_model->delete_unit($academic_syllabus_id);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
        redirect(base_url() . 'teacher/unit_content/', 'refresh');
    }
    
    function class_routine($class_id)
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'class_routine';
        $page_data['class_id']  =   $class_id;
        $page_data['page_title'] = get_phrase('Class-Routine');
        $this->load->view('backend/index', $page_data);
    }

    function my_account($param1 = "", $page_id = "")
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('teacher_login') != 1)
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
            $this->db->where('teacher_id', $this->session->userdata('login_user_id'));
            $this->db->update('teacher', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/teacher_image/' . $this->session->userdata('login_user_id') . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'teacher/my_account/', 'refresh');
        }

        $data['page_name']              = 'my_account';
        $data['page_title']             = get_phrase('profile');
        $this->load->view('backend/index', $data);
    }

    function manage_attendance($class_id)
    {
        if($this->session->userdata('teacher_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['page_name']  =  'manage_attendance';
        $page_data['class_id']   =  $class_id;
        $page_data['page_title'] =  get_phrase('attendance');
        $this->load->view('backend/index', $page_data);
    }

    function manage_attendance_view($class_id = '' , $section_id = '' , $timestamp = '')
    {
        if($this->session->userdata('teacher_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['class_id'] = $class_id;
        $page_data['timestamp'] = $timestamp;
        $page_data['page_name'] = 'manage_attendance_view';
        $section_name = $this->db->get_where('section' , array('section_id' => $section_id))->row()->name;
        $page_data['section_id'] = $section_id;
        $page_data['page_title'] = get_phrase('attendance') . ' ' . $class_name . ' : ' . get_phrase('section') . ' ' . $section_name;
        $this->load->view('backend/index', $page_data);
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
            foreach($students as $row) 
            {
                $attn_data['class_id']   = $data['class_id'];
                $attn_data['year']       = $data['year'];
                $attn_data['timestamp']  = $data['timestamp'];
                $attn_data['section_id'] = $data['section_id'];
                $attn_data['student_id'] = $row['student_id'];
                $this->db->insert('attendance' , $attn_data);  
            }
        }
        redirect(base_url().'teacher/manage_attendance_view/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['timestamp'],'refresh');
    }

    function attendance_update($class_id = '' , $section_id = '' , $timestamp = '')
    {
        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $attendance_of_students = $this->db->get_where('attendance' , array('class_id'=>$class_id,'section_id'=>$section_id,'year'=>$running_year,'timestamp'=>$timestamp))->result_array();
        foreach($attendance_of_students as $row) 
        {
            $attendance_status = $this->input->post('status_'.$row['attendance_id']);
            $this->db->where('attendance_id' , $row['attendance_id']);
            $this->db->update('attendance' , array('status' => $attendance_status));
        }
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
        redirect(base_url().'teacher/manage_attendance_view/'.$class_id.'/'.$section_id.'/'.$timestamp , 'refresh');
    }
    
    function study_material($task = "", $document_id = "")
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        } 
        if ($task == "create")
        {
            $this->crud_model->save_study_material_info();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_uploaded'));
            redirect(base_url() . 'teacher/study_material' , 'refresh');
        }
        if ($task == "delete")
        {
            $this->crud_model->delete_study_material_info($document_id);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'teacher/study_material/');
        }
        
        $data['page_name']              = 'study_material';
        $data['page_title']             = get_phrase('study_material');
        $this->load->view('backend/index', $data);
    }

    function library($param1 = '', $param2 = '', $param3 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('teacher_login') != 1)
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
                    redirect(base_url() . 'teacher/library', 'refresh');
                } 
                else 
                {
                    $this->session->set_flashdata('error_message' , "Invalid extension.");
                    redirect(base_url() . 'teacher/library' , 'refresh');
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
                redirect(base_url() . 'teacher/library', 'refresh');
            }
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

    function message($param1 = 'message_home', $param2 = '', $param3 = '') 
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'send_new') 
        {
            $message_thread_code = $this->crud_model->send_new_private_message();
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            redirect(base_url() . 'teacher/message/message_read/' . $message_thread_code, 'location');
        }
        if ($param1 == 'send_reply') 
        {
            $this->crud_model->send_reply_message($param2);
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            $this->session->set_flashdata('flash_message' , get_phrase('reply_sent'));
            redirect(base_url() . 'teacher/message/message_read/' . $param2, 'location');
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

    function request($param1 = "", $param2 = "")
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('teacher_login') != 1)
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }    
        if ($param1 == "create")
        {
            $this->crud_model->permission_request();
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/request/" . $_FILES["file_name"]["name"]);            
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'teacher/request', 'refresh');
        }
        
        $data['page_name']  = 'request';
        $data['page_title'] = get_phrase('permissions');
        $this->load->view('backend/index', $data);
    }

    function homeworkroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1) 
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

    function homework_file($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $homework_code = $this->db->get_where('homework', array('homework_id'))->row()->homework_code;
        if ($param1 == 'upload')
        {
            $this->crud_model->upload_homework_file($param2);
        }
        else if ($param1 == 'download')
        {
            $this->crud_model->download_homework_file($param2);
        }
        else if ($param1 == 'delete')
        {
            $this->crud_model->delete_homework_file($param2);
            redirect(base_url() . 'teacher/homeworkroom/details/' . $homework_code , 'refresh');
        }
    }

    function forum($param1 = '', $param2 = '') 
    {
        if ($param1 == 'create') 
        {
            $post_code = $this->crud_model->create_post();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'teacher/forumroom/' . $post_code , 'refresh');
        }
        if ($param1 == 'update') 
        {
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');

            $data['timestamp'] = strtotime(date("d M,Y"));
            $data['subject_id'] = $this->input->post('subject_id');
            $data['teacher_id']  =   $this->session->userdata('login_user_id');
			
			
				$this->db->where('post_code', $param2);
				$this->db->update('forum', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'teacher/edit_forum/' . $param2 , 'refresh');
        }
        if ($param1 == 'delete')
        {
            $this->crud_model->delete_post($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
        }
        $page_data['page_name'] = 'forum';
        $page_data['page_title'] = get_phrase('forum');
        $this->load->view('backend/index', $page_data);
    }

    function single_homework($param1 = '', $param2 = '') 
    {
       if ($this->session->userdata('teacher_login') != 1)
       {
            redirect(base_url(), 'refresh');
       }
       
       $page_data['answer_id'] = $param1;
       $page_data['page_name'] = 'single_homework';
       $page_data['page_title'] = get_phrase('homework');
       $this->load->view('backend/index', $page_data);
    }

    function create_exam($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
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
            
            redirect(base_url() . 'teacher/online_exams/', 'refresh');
        }

        $page_data['page_name']  = 'create_exam';
        $page_data['page_title'] = get_phrase('online_exams');
        $this->load->view('backend/index', $page_data);
    }

    function manage_exams($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'delete')
        {
            $this->db->where('exam_id', $param2);
            $this->db->delete('exams');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'teacher/online_exams/', 'refresh');
        }
    }

    function homework_details($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['homework_code'] = $param1;
        $page_data['page_name']  = 'homework_details';
        $page_data['page_title'] = get_phrase('homework_details');
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
            redirect(base_url() . 'teacher/exam_edit/' . $param2 , 'refresh');
        }
        if ($param1 == 'questions') 
        {
            $this->crud_model->add_questions();
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_added'));
            redirect(base_url() . 'teacher/exam_questions/' . $param2 , 'refresh');
        }
        if ($param1 == 'delete_questions') 
        {
            $this->db->where('question_id', $param2);
            $this->db->delete('questions');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'teacher/exam_questions/'.$param3, 'refresh');
        }
        if ($param1 == 'delete'){
            $this->crud_model->delete_exam($param2);
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_deleted'));
            redirect(base_url() . 'index.php?teacher/online_exams', 'refresh');
        }

        $page_data['page_name'] = 'online_exams';
        $page_data['page_title'] = get_phrase('online_exams');
        $this->load->view('backend/index', $page_data);
    }

    function examroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']   = 'exam_room'; 
        $page_data['exam_code']  = $param1;
        $page_data['page_title']  = get_phrase('online_exams');
        $this->load->view('backend/index', $page_data);
    }

    function exam_questions($exam_code = '') 
    {    
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['exam_code'] = $exam_code;
        $page_data['page_name'] = 'exam_questions';
        $page_data['page_title'] = get_phrase('exam_questions');
        $this->load->view('backend/index', $page_data);
    }

    function exam_results($exam_code) 
    { 
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }   
        $page_data['exam_code'] = $exam_code;
        $page_data['page_name'] = 'exam_results';
        $page_data['page_title'] = get_phrase('exams_results');
        $this->load->view('backend/index', $page_data);
    }

    function exam_edit($exam_code= '') 
    { 
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }   
        $page_data['exam_code'] = $exam_code;
        $page_data['page_name'] = 'exam_edit';
        $page_data['page_title'] = get_phrase('update_exam');
        $this->load->view('backend/index', $page_data);
    }

    function homework_edit($homework_code = '') 
    {   
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        } 
        $page_data['homework_code'] = $homework_code;
        $page_data['page_name'] = 'homework_edit';
        $page_data['page_title'] = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

    function forumroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1) 
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

     function read($code = "")
    {
         if ($this->session->userdata('teacher_login') != 1)
         {
            redirect(base_url(), 'refresh');
         }
        $page_data['page_name']  = 'read';
        $page_data['page_title'] = get_phrase('noticeboard');
        $page_data['code']   = $code;
        $this->load->view('backend/index', $page_data); 
    }

    function edit_forum($code = '')
    {
        $page_data['page_name']  = 'edit_forum';
        $page_data['page_title'] = get_phrase('update_forum');
        $page_data['code']   = $code;
        $this->load->view('backend/index', $page_data);    
    }

    function forum_message($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('teacher_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_post_message($this->input->post('post_code'));
        }
    }
}