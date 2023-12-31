<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student extends CI_Controller
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
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($this->session->userdata('student_login') == 1)
        {
            redirect(base_url() . 'student/panel/', 'refresh');
        }
    }

     function group($param1 = "group_message_home", $param2 = "")
     {
		 $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
      if ($this->session->userdata('student_login') != 1)
      {
          redirect(base_url(), 'refresh');
      }
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
        if ($_FILES['attached_file_on_messaging']['name'] != "") {
          if($_FILES['attached_file_on_messaging']['size'] > $max_size)
          {
            $this->session->set_flashdata('error_message' , "2MB allowed");
              redirect(base_url() . 'stundent/group/group_message_read/'.$param2, 'refresh');
          }
          else{
            $file_path = $subdomain.'uploads/group_messaging_attached_file/'.$_FILES['attached_file_on_messaging']['name'];
            move_uploaded_file($_FILES['attached_file_on_messaging']['tmp_name'], $file_path);
          }
        }

        $this->crud_model->send_reply_group_message($param2);
        $this->session->set_flashdata('flash_message', get_phrase('message_sent'));
        redirect(base_url() . 'student/group/group_message_read/'.$param2, 'refresh');
      }
      $page_data['message_inner_page_name']   = $param1;
      $page_data['page_name']                 = 'group';
      $page_data['page_title']                = get_phrase('message_group');
      $this->load->view('backend/index', $page_data);
    }
	
	

    function polls($param1 = '', $param2 = '')
      {
        if ($this->session->userdata('student_login') != 1)
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
            return $this->db->insert('poll_response', $data);
        }
    }

    function entrys($e)
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $how_hear = count($_POST['answer']) ? $_POST['answer'] : array();
        $how = count($_POST['ques_id']) ? $_POST['ques_id'] : array();
        $exam_code = $_POST['exam_code'];
        $data['student_answer']  = implode(',',$how_hear);
        $data['student_id']      = $this->session->userdata('login_user_id');
        $data['question_id']     = implode(',',$how);
        $data['answered']        = "answered";
        $data['time']            = $_POST['time_left'];
        $data['total_time']      = $_POST['time'];
        $data['exam_code']       = $exam_code;
        $this->db->insert('student_question', $data);
        redirect(base_url() . 'student/online_exams/', 'refresh');
        
    }

    function exam_view($param1 = '' , $param2 = '', $question_id)
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'take_exam') 
        {
            $page_data['room_page'] = 'take_exam';
            $page_data['exam_code'] = $param2;
            if($this->db->get_where('student_question',array('exam_code'=>$param2,'student_id' => $this->session->userdata('login_user_id')))->row()->answered == 'answered')
            {
                redirect(base_url() . 'student/online_exams/', 'refresh');
            } 
        }
        if ($param1 == 'results')
        {
            $page_data['room_page'] = 'results';
            $page_data['exam_code'] = $param2;
        }
        $page_data['page_name']   = 'exam_room'; 
        $page_data['page_title']  = "";
        $this->load->view('backend/index', $page_data);
    }

   function take($exam_code)
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['questions'] = $this->db->get_where('questions' , array('exam_code' => $exam_code))->result_array();
        if($this->db->get_where('student_question',array('exam_code'=>$exam_code,'student_id'=>$this->session->userdata('login_user_id')))->row()->answered == 'answered')
        {
            redirect(base_url() . 'index.php?student/online_exams/', 'refresh');
        } 

        $page_data['exam_code'] = $exam_code;
        $page_data['page_name']   = 'take'; 
        $page_data['page_title']  = "";
        $this->load->view('backend/index', $page_data);
    }

    function request($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }  
        if ($param1 == "create")
        {
            $data['student_id']   = $this->session->userdata('login_user_id');
            $data['description']  = $this->input->post('description');
            $data['title']        = $this->input->post('title');
            $data['start_date']   = $this->input->post('start_date');
            $data['end_date']     = $this->input->post('end_date');
            $data['status']     = 0;
            $this->db->insert('students_request', $data);
            redirect(base_url() . 'student/request', 'refresh');
        }
        $data['page_name']  = 'request';
        $data['page_title'] = get_phrase('permissions');
        $this->load->view('backend/index', $data);
    }

    function attendance_report() 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        $page_data['month']        = date('m');
        $page_data['page_name']    = 'attendance_report';
        $page_data['page_title']   = get_phrase('attendance_report');
        $this->load->view('backend/index',$page_data);
    }

    function examroom($code = "") 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        $page_data['code'] = $code;
        $page_data['page_name']    = 'examroom';
        $page_data['page_title']   = get_phrase('take_exam');
        $this->load->view('backend/index',$page_data);
    }

    function exam($code = "") 
    { 
        $page_data['questions'] = $this->db->get_where('questions' , array('exam_code' => $code))->result_array();
        if($this->db->get_where('student_question',array('exam_code'=>$code,'student_id'=>$this->session->userdata('login_user_id')))->row()->answered == 'answered')
        {
            redirect(base_url() . 'student/online_exams/', 'refresh');
        } 
        $page_data['exam_code'] = $code; 
        $page_data['page_name']    = 'exam';
        $page_data['page_title']   = get_phrase('online_exam');
        $this->load->view('backend/index',$page_data);
     }

     function exam_results($code = '') 
     {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['exam_code'] = $code;
        $page_data['page_name']     = 'exam_results';
        $page_data['page_title']    = get_phrase('exam_results');
        $this->load->view('backend/index', $page_data);
    }

     function view_results() 
     {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
         
        $page_data['page_name']    = 'view_results';
        $page_data['page_title']   = "";
        $this->load->view('backend/index',$page_data);
     }

    function print_marks() 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        $page_data['month']        = date('m');
        $page_data['page_name']    = 'print_marks';
        $page_data['page_title']   = "";
        $this->load->view('backend/index',$page_data);
    }

    function subject_marks($data) 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['data'] = $data;
        $page_data['page_name']    = 'subject_marks';
        $page_data['page_title']   =  get_phrase('subject_marks');
        $this->load->view('backend/index',$page_data);
    }

    function view_invoice($id) 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['invoice_id'] = $id;
        $page_data['page_name']    = 'view_invoice';
        $page_data['page_title']   = get_phrase('invoice');
        $this->load->view('backend/index',$page_data);
    }

    function view_report($code) 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['code'] = $code;
        $page_data['page_name']    = 'view_report';
        $page_data['page_title']   = get_phrase('view_report');
        $this->load->view('backend/index',$page_data);
    }

     function view_new() 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']    = 'view_new';
        $page_data['page_title']   = "";
        $this->load->view('backend/index',$page_data);
    }

    function view_event() 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']    = 'view_event';
        $page_data['page_title']   = "";
        $this->load->view('backend/index',$page_data);
    }

    function my_profile($param1 = '', $param2 = '') 
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        if($param1 == 'update')
        {
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['address'] = $this->input->post('address');
            $data['birthday'] = $this->input->post('birthday');
            $data['name'] = $this->input->post('name');
            if($this->input->post('password') != "")
            {
                $data['password'] = sha1($this->input->post('password'));
            }
            $this->db->where('student_id', $this->session->userdata('login_user_id'));
            $this->db->update('student', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/student_image/' . $this->session->userdata('login_user_id') . '.jpg');
            redirect(base_url().'student/my_profile/','refresh');
        }
         $page_data['page_name']    = 'my_profile';
         $page_data['page_title']   = get_phrase('profile');
         $this->load->view('backend/index',$page_data);
     }

    function report_attendance_view($class_id = '' , $section_id = '', $month = '') 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
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
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $data['class_id']   = $this->input->post('class_id');
        $data['year']       = $this->input->post('year');
        $data['month']  = $this->input->post('month');
        $data['section_id'] = $this->input->post('section_id');
        redirect(base_url().'student/report_attendance_view/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['month'],'refresh');
    }

    function events($param1 = '', $param2 = '' , $param3 = '') 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'edit') 
        {
            $this->crud_model->calendar_event_edit($param2);
        }

        $page_data['page_name']     = 'events';
        $page_data['page_title']    = "";
        $this->load->view('backend/index', $page_data);
    }

    function panel()
    {
        if ($this->session->userdata('student_login') != 1)
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
        if ($this->session->userdata('student_login') != 1)
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

    function marks_print_view($student_id , $exam_id) 
     {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->row()->class_id;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $page_data['exam_id']    =   $exam_id;
        $this->load->view('backend/student/marks_print_view', $page_data);
    }
    
    function teacher_list($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'personal_profile') 
        {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = "";
        $this->load->view('backend/index', $page_data);
    }
    
    function subject($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $student_profile         = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->row();
        $student_class_id        = $this->db->get_where('enroll' , array('student_id' => $student_profile->student_id,'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->row()->class_id;
        $page_data['subjects']   = $this->db->get_where('subject', array('class_id' => $student_class_id,'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('subjects');
        $this->load->view('backend/index', $page_data);
    }
    
    function my_marks($student_id = '') 
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $student = $this->db->get_where('student' , array('student_id' => $student_id))->result_array();
        foreach ($student as $row)
        {
            if($row['student_id'] == $this->session->userdata('login_user_id'))
            {
                $page_data['student_id'] =   $student_id;
            } else if($row['parent_id'] != $this->session->userdata('login_user_id'))
            {
                redirect(base_url(), 'refresh');
            }
        }

        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->row()->class_id;
        $student_name = $this->db->get_where('student' , array('student_id' => $student_id))->row()->name;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['page_name']  =   'my_marks';
        $page_data['page_title'] =   get_phrase('marks');
        $page_data['class_id']   =   $class_id;
        $this->load->view('backend/index', $page_data);
    }
    
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        $student_profile         = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->row();
        $page_data['class_id']   = $this->db->get_where('enroll' , array('student_id' => $student_profile->student_id,'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->row()->class_id;
        $page_data['student_id'] = $student_profile->student_id;
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function exam_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        $student_profile         = $this->db->get_where('student', array('student_id' => $this->session->userdata('student_id')))->row();
        $page_data['class_id']   = $this->db->get_where('enroll' , array('student_id' => $student_profile->student_id,'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->row()->class_id;
        $page_data['student_id'] = $student_profile->student_id;
        $page_data['page_name']  = 'exam_routine';
        $page_data['page_title'] = get_phrase('exam_routine');
        $this->load->view('backend/index', $page_data);
    }

    function syllabus($student_id = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'syllabus';
        $page_data['page_title'] = get_phrase('syllabus');
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);
    }

    function homework($student_id = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'homework';
        $page_data['page_title'] = get_phrase('homework');
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);
    }
    
    function libreria_virtual($student_id = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'libreria_virtual';
        $page_data['page_title'] = "";
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);
    }

    function online_exams($student_id = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'online_exams';
        $page_data['page_title'] = get_phrase('online_exams');
        $page_data['student_id']   = $student_id;
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
    
     function descargar_libro($libro_code)
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $file_name = $this->db->get_where('libreria', array('libro_code' => $libro_code))->row()->file_name;
        $this->load->helper('download');
        $data = file_get_contents($subdomain."uploads/libreria/" . $file_name);
        $name = $file_name;
        force_download($name, $data);
    }

   function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        if ($param1 == 'make_payment') 
        {
            $invoice_id      = $this->input->post('invoice_id');
            $system_settings = $this->db->get_where('settings', array('type' => 'paypal_email'))->row();
            $invoice_details = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();
            
            $this->paypal->add_field('rm', 2);
            $this->paypal->add_field('no_note', 0);
            $this->paypal->add_field('currency_code', $this->db->get_where('settings' , array('type' =>'currency'))->row()->description);
            $this->paypal->add_field('item_name', $invoice_details->title);
            $this->paypal->add_field('amount', $invoice_details->due);
            $this->paypal->add_field('custom', $invoice_details->invoice_id);
            $this->paypal->add_field('business', $system_settings->description);
            $this->paypal->add_field('notify_url', base_url() . 'student/invoice/');
            $this->paypal->add_field('cancel_return', base_url() . 'student/invoice/paypal_cancel');
            $this->paypal->add_field('return', base_url() . 'student/invoice/paypal_success');
            $this->paypal->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
            $this->paypal->submit_paypal_post();
        }
        if ($param1 == 'paypal_cancel') 
        {
            redirect(base_url() . 'student/invoice/', 'refresh');
        }
        if ($param1 == 'paypal_success') 
        {
            foreach ($_POST as $key => $value) 
                {
                    $value = urlencode(stripslashes($value));
                    $ipn_response .= "\n$key=$value";
                }
                $data['payment_details']   = $ipn_response;
                $data['payment_timestamp'] = strtotime(date("d/m/Y"));
                $data['payment_method']    = 'paypal';
                $data['status']            = 'completed';
                $invoice_id                = $_POST['custom'];
                $this->db->where('invoice_id', $invoice_id);
                $this->db->update('invoice', $data);

                $data2['method']       =   'paypal';
                $data2['invoice_id']   =   $_POST['custom'];
                $data2['timestamp']    =   strtotime(date("d/m/Y"));
                $data2['payment_type'] =   'income';
                $data2['title']        =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->title;
                $data2['description']  =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->description;
                $data2['student_id']   =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->student_id;
                $data2['amount']       =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->amount;
                $this->db->insert('payment' , $data2);
            redirect(base_url() . 'student/invoice/', 'refresh');
        }
        $student_profile         = $this->db->get_where('student', array('student_id'   => $this->session->userdata('student_id')))->row();
        $student_id              = $student_profile->student_id;
        $page_data['invoices']   = $this->db->get_where('invoice', array('student_id' => $student_id))->result_array();
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('invoice');
        $this->load->view('backend/index', $page_data);
    }

    function listado_de_reportes($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            redirect(base_url(), 'refresh');
        }

        if ($param1 == 'create')
        {
            $this->crud_model->create_report();
            redirect(base_url(), 'refresh');
        }

        $page_data['page_title'] =  "";
        $page_data['page_name']  = 'listado_de_reportes';
        $this->load->view('backend/index', $page_data);
    }

    function send_report() 
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name'] = 'send_report';
        $page_data['page_title'] = get_phrase('teacher_report');
        $this->load->view('backend/index', $page_data);
    }

    function noticeboard($param1 = '', $param2 = '') 
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name'] = 'noticeboard';
        $page_data['page_title'] = get_phrase('noticeboard');
        $this->load->view('backend/index', $page_data);
    }

    function school_bus($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'school_bus';
        $page_data['page_title'] = "";
        $this->load->view('backend/index', $page_data);
    }

    function message($param1 = 'message_home', $param2 = '', $param3 = '') 
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'send_new') 
        {
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            $message_thread_code = $this->crud_model->send_new_private_message();
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            redirect(base_url() . 'student/message/message_read/' . $message_thread_code, 'location');
        }
        if ($param1 == 'send_reply') 
        {
            $this->session->set_flashdata('flash_message' , get_phrase('reply_sent'));
            $this->crud_model->send_reply_message($param2);
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            redirect(base_url() . 'student/message/message_read/' . $param2, 'location');
        }
        if ($param1 == 'message_read') 
        {
            $page_data['current_message_thread_code'] = $param2;
            $this->crud_model->mark_thread_messages_read($param2);
        }

        $page_data['infouser'] = $param2;
        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'message';
        $page_data['page_title']                = get_phrase('private_message');
        $this->load->view('backend/index', $page_data);
    }
    
    function study_material($task = "", $document_id = "")
    {
        if ($this->session->userdata('student_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }
        
        $data['study_material_info']    = $this->crud_model->select_study_material_info_for_student();
        $data['page_name']              = 'study_material';
        $data['page_title']             = get_phrase('study_material');
        $this->load->view('backend/index', $data);
    }

    function library($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $page_data['books']      = $this->db->get('book')->result_array();
        $page_data['page_name']  = 'library';
        $page_data['page_title'] = get_phrase('library');
        $this->load->view('backend/index', $page_data);
    }

    function homeworkroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $page_data['homework_code'] = $param1;
        $page_data['page_name']   = 'homework_room'; 
        $page_data['page_title']  = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

    function delivery($param1 = '', $param2 = '')
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'file')
        {
            $data['homework_code'] = $param2;
            $name = substr(md5(rand(0, 1000000)), 0, 7).$_FILES["file_name"]["name"];
            $data['student_id']    = $this->session->userdata('login_user_id');
            $data['date']          = date('d/m/Y H:i');
            $data['class_id']      = $this->input->post('class_id');
            $data['section_id']    = $this->input->post('section_id');
            $data['file_name']     =  $name;
            $data['student_comment'] = $this->input->post('comment');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['status'] = 1;
            $this->db->insert('deliveries', $data);
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/homework_delivery/" . $name);
            redirect(base_url() . 'student/homeworkroom/' . $param2, 'refresh');
        }
        if($param1 == 'text')
        {
            $data['homework_code'] = $param2;
            $data['student_id']    = $this->session->userdata('login_user_id');
            $data['date']          = date('d/m/Y H:i');
            $data['class_id']      = $this->input->post('class_id');
            $data['section_id']    = $this->input->post('section_id');
            $data['homework_reply'] =  $this->input->post('reply');
            $data['student_comment'] = $this->input->post('comment');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['status'] = 1;
            $this->db->insert('deliveries', $data);
            redirect(base_url() . 'student/homeworkroom/' . $param2, 'refresh');
        }
    }

    function homework_file($param1 = '', $param2 = '', $param3 = '') 
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $homework_code = $this->db->get_where('homework', array('homework_id'))->row()->homework_code;
        if ($param1 == 'upload')
        {
            $this->crud_model->upload_homework_file($param2);
            redirect(base_url() . 'student/homeworkroom/file/' . $param2, 'refresh');
        }
        else if ($param1 == 'download')
        {
            $this->crud_model->download_homework_file($param2);
        }
    }

    function forumroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $page_data['post_code'] = $param1;
        $page_data['page_name']   = 'forum_room'; 
        $page_data['page_title']  = get_phrase('forum');
        $this->load->view('backend/index', $page_data);
    }

    function newsroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        else if ($param1 == 'overview') 
        {
            $page_data['room_page'] = 'news_overview';
            $page_data['news_code'] = $param2;
        }

        $page_data['page_name']   = 'newsroom'; 
        $page_data['page_title']  ="";
        $page_data['page_title'] .=  ": " . $this->db->get_where('news',array('news_code'=>$param2))->row()->title;
        $this->load->view('backend/index', $page_data);
    }

     function create_report_message($code = '') 
     {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $data['message']      = $this->input->post('message');
        $data['report_code']  = $this->input->post('report_code');
        $data['timestamp']    = date("d M, Y");
        $data['sender_type']    = $this->session->userdata('login_type');
        $data['sender_id']      = $this->session->userdata('login_user_id');
        $this->db->insert('reporte_mensaje', $data);
    }  

    function news_message($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_news_message($this->input->post('news_code'));
        }
    }

    function read($code = "")
    {
         if ($this->session->userdata('student_login') != 1)
         {
            redirect(base_url(), 'refresh');
         }
        $page_data['page_name']  = 'read';
        $page_data['page_title'] = get_phrase('noticeboard');
        $page_data['code']   = $code;
        $this->load->view('backend/index', $page_data); 
    }

    function forum_message($param1 = '', $param2 = '', $param3 = '')
     {
        if ($this->session->userdata('student_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_post_message($this->input->post('post_code')); 
        }
    }

    function forum($param1 = '', $param2 = '', $student_id = '') 
    {
        if ($param1 == 'create') 
        {
            $post_code = $this->crud_model->create_post();
            redirect(base_url() . 'index.php?student/forumroom/post/' . $post_code , 'refresh');
        }

        $page_data['page_name'] = 'forum';
        $page_data['page_title'] = get_phrase('forum');
        $page_data['student_id']   = $student_id;
        $this->load->view('backend/index', $page_data);
    }
}