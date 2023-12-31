<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Parents extends CI_Controller
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
		$this->load->helper('date');
    }

    public function index()
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($this->session->userdata('parent_login') == 1)
        {
            redirect(base_url() . 'parents/panel/', 'refresh');
        }
    }

    function group($param1 = "group_message_home", $param2 = "")
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
      if ($this->session->userdata('parent_login') != 1)
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
              redirect(base_url() . 'parents/group/group_message_read/'.$param2, 'refresh');
          }
          else{
            $file_path = $subdomain.'uploads/group_messaging_attached_file/'.$_FILES['attached_file_on_messaging']['name'];
            move_uploaded_file($_FILES['attached_file_on_messaging']['tmp_name'], $file_path);
          }
        }
        $this->crud_model->send_reply_group_message($param2); 
        $this->session->set_flashdata('flash_message', get_phrase('message_sent'));
        redirect(base_url() . 'parents/group/group_message_read/'.$param2, 'refresh');
      }
      $page_data['message_inner_page_name']   = $param1;
      $page_data['page_name']                 = 'group';
      $page_data['page_title']                = get_phrase('message_group');
      $this->load->view('backend/index', $page_data);
    }
	
     function view_report($report_code = '') 
    {
        if ($this->session->userdata('parent_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['code'] = $report_code;
        $page_data['page_name'] = 'view_report';
        $page_data['page_title'] = get_phrase('report_details');
        $this->load->view('backend/index', $page_data);
    }

     function student_report($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
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

    function my_profile($param1 = "", $page_id = "")
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }       
        if($param1 == 'update')
        {
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['address'] = $this->input->post('address');
            $data['profession'] = $this->input->post('profession');
            if($this->input->post('password') != "")
            {
                $data['password'] = sha1($this->input->post('password'));
            }
            $this->db->where('parent_id', $this->session->userdata('login_user_id'));
            $this->db->update('parent', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], $subdomain.'uploads/parent_image/' . $this->session->userdata('login_user_id') . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('successfully_updated'));
            redirect(base_url() . 'parents/my_profile/', 'refresh');
        }

        $data['page_name']              = 'my_profile';
        $data['page_title']             =  get_phrase('profile');
        $this->load->view('backend/index', $data);
    }

    function subject_marks($data) 
     {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }  

        $page_data['data'] = $data;
        $page_data['page_name']    = 'subject_marks';
        $page_data['page_title']   = get_phrase('marks');
        $this->load->view('backend/index',$page_data);
     }

    function online_exams()
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'online_exams';
        $page_data['page_title'] = get_phrase('online_exams');
        $this->load->view('backend/index', $page_data);
    }

     function polls($param1 = '', $param2 = '')
      {
        if ($this->session->userdata('parent_login') != 1)
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

    function homework()
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'homework';
        $page_data['page_title'] = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

    function study_material()
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'study_material';
        $page_data['page_title'] =  get_phrase('study_material');
        $this->load->view('backend/index', $page_data);
    }

   function homeworkroom($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('parent_login') != 1) 
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['homework_code'] = $param1;
        $page_data['page_name']   = 'homework_room'; 
        $page_data['page_title']  = get_phrase('homework');
        $this->load->view('backend/index', $page_data);
    }

    function syllabus()
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'syllabus';
        $page_data['page_title'] = get_phrase('syllabus');
        $this->load->view('backend/index', $page_data);
    }

    function view_invoice($id)
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['invoice_id'] = $id;
        $page_data['page_name']  = 'view_invoice';
        $page_data['page_title'] = get_phrase('view_invoice');
        $this->load->view('backend/index', $page_data);
    }

    function examroom()
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'examroom';
        $page_data['page_title'] = "Examen";
        $this->load->view('backend/index', $page_data);
    }

    function panel()
    {
        if ($this->session->userdata('parent_login') != 1)
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
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'calendar';
        $page_data['page_title'] = get_phrase('academic_calendar');
        $this->load->view('backend/index', $page_data);
    }

	function calendar_get_events() 
    {
        $events =  $this->db->get('calendar_events');
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

    function teachers()
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'teachers';
        $page_data['page_title'] = get_phrase('teachers');
        $this->load->view('backend/index', $page_data);
    }

    function download_unit_content($academic_syllabus_code)
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if($this->session->userdata('parent_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $file_name = $this->db->get_where('academic_syllabus', array('academic_syllabus_code' => $academic_syllabus_code))->row()->file_name;
        $this->load->helper('download');
        $data = file_get_contents($subdomain."uploads/syllabus/" . $file_name);
        $name = $file_name;
        force_download($name, $data);
    }
	
	
	function school_bus($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('parent_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        
        //$page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'school_bus';
        $page_data['page_title'] = "";
        $this->load->view('backend/index', $page_data);
    }

    function marks_print_view($student_id , $exam_id) 
     {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect('login', 'refresh');
        }
        $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->row()->class_id;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;

        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $page_data['exam_id']    =   $exam_id;
        $this->load->view('backend/parent/marks_print_view', $page_data);
    }

    function noticeboard($param1 = '', $param2 = '') 
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect('login', 'refresh');
        }
        $page_data['page_name'] = 'noticeboard';
        $page_data['page_title'] = get_phrase('noticeboard');
        $this->load->view('backend/index', $page_data);
    }

    function marks($param1 = '', $param2 ='')
    {
        if ($this->session->userdata('parent_login') != 1)
            redirect(base_url(), 'refresh');

        $parents = $this->db->get_where('student' , array('student_id' => $param1))->result_array();
                foreach ($parents as $row)
            {
                if($row['parent_id'] == $this->session->userdata('login_user_id'))
                {
                    $page_data['student_id'] = $param1;
                } else if($row['parent_id'] != $this->session->userdata('login_user_id'))
                {
                    redirect(base_url(), 'refresh');
                }
            }

        $page_data['page_name']  = 'marks';
        $page_data['page_title'] = get_phrase('marks');
        $this->load->view('backend/index', $page_data);
    }

    function library($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect('login', 'refresh'); 
        }
        $page_data['page_name']  = 'library';
        $page_data['page_title'] = get_phrase('library');
        $this->load->view('backend/index', $page_data);
    }
    
    
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $page_data['student_id'] = $param1;
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function attendance_report() 
     {
        if($this->session->userdata('parent_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }

        $page_data['month']        = date('m');
        $page_data['page_name']    = 'attendance_report';
        $page_data['page_title']   = get_phrase('attendance_report');
        $this->load->view('backend/index',$page_data);
     }

    function report_attendance_view($class_id = '' , $section_id = '', $student_id = '', $month = '', $param1 = '') 
     {
         if($this->session->userdata('parent_login')!=1)
         {
            redirect(base_url() , 'refresh');
         }
        
        $class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['class_id'] = $class_id;
        $page_data['month']    = $month;
        $page_data['student_id'] = $student_id;
        $page_data['page_name'] = 'report_attendance_view';
        $section_name = $this->db->get_where('section' , array('section_id' => $section_id))->row()->name;
        $page_data['section_id'] = $section_id;
        $page_data['page_title'] = get_phrase('attendance_report');
        $this->load->view('backend/index', $page_data);
     }

     function attendance_report_selector()
     {
        if($this->session->userdata('parent_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $data['class_id']   = $this->db->get_where('enroll', array('student_id' => $this->input->post('student_id')))->row()->class_id;
        $data['section_id']   = $this->db->get_where('enroll', array('student_id' => $this->input->post('student_id')))->row()->section_id;
        $data['year']       = $this->input->post('year');
        $data['student_id'] = $this->input->post('student_id');
        $data['month']  = $this->input->post('month');
        redirect(base_url().'parents/report_attendance_view/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['student_id'].'/'.$data['month'],'refresh');
    }

    function exam_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
      
        $page_data['page_name']  = 'exam_routine';
        $page_data['page_title'] = get_phrase('exam_routine');
        $this->load->view('backend/index', $page_data);
    }
    
    function invoice($student_id = '' , $param1 = '', $param2 = '', $param3 = '')
    {
        if ($param1 == 'make_payment') 
        {
            $invoice_id      = $this->input->post('invoice_id');
            $system_settings = $this->db->get_where('settings', array('type' => 'paypal_email'))->row();
            $invoice_details = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();
            $this->paypal->add_field('rm', 2);
            $this->paypal->add_field('no_note', 0);
            $this->paypal->add_field('item_name', $invoice_details->title);
            $this->paypal->add_field('amount', $invoice_details->due);
            $this->paypal->add_field('currency_code', $this->db->get_where('settings' , array('type' =>'currency'))->row()->description);
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
            redirect(base_url() . 'parents/invoice/' . $student_id, 'refresh');
        }
        if ($param1 == 'paypal_success') 
        {
            foreach ($_POST as $key => $value) 
                {
                    $value = urlencode(stripslashes($value));
                    $ipn_response .= "\n$key=$value";
                }
                $data['payment_details']   = $ipn_response;
                $data['payment_timestamp'] = strtotime(date("d-m-Y"));
                $data['payment_method']    = 'paypal';
                $data['status']            = 'completed';
                $invoice_id                = $_POST['custom'];
                $this->db->where('invoice_id', $invoice_id);
                $this->db->update('invoice', $data);

                $data2['method']       =   'paypal';
                $data2['invoice_id']   =   $_POST['custom'];
                $data2['timestamp']    =   strtotime(date("d-m-Y"));
                $data2['payment_type'] =   'income';
                $data2['title']        =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->title;
                $data2['description']  =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->description;
                $data2['student_id']   =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->student_id;
                $data2['amount']       =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->amount;
                $this->db->insert('payment' , $data2);
            redirect(base_url() . 'parents/invoice/'.$student_id, 'refresh');
        }
        if ($student_id == 'student') 
        {
            redirect(base_url() . 'parents/invoice/' . $this->input->post('student_id'), 'refresh');
        }

        $parent_profile         = $this->db->get_where('parent', array('parent_id' => $this->session->userdata('parent_id')))->row();
        $page_data['student_id'] = $student_id;
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('payments');
        $this->load->view('backend/index', $page_data);
    }

    function news_message($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('parent_login') != 1) 
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'add') 
        {
            $this->crud_model->create_news_message($this->input->post('news_code'));
        }
    }

    function exam_results($code = '') 
     {
        if ($this->session->userdata('parent_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $page_data['exam_code']     = $code;
        $page_data['page_name']     = 'exam_results';
        $page_data['page_title']    = get_phrase('exam_results');
        $this->load->view('backend/index', $page_data);
    }

    function read($code = "")
    {
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'read';
        $page_data['page_title'] = get_phrase('noticeboard');
        $page_data['code']   = $code;
        $this->load->view('backend/index', $page_data); 
    }

    function message($param1 = 'message_home', $param2 = '', $param3 = '') 
    {
		$subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
        if ($this->session->userdata('parent_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'send_new') 
        {
            $this->session->set_flashdata('flash_message' , "Mensaje enviado con éxito");
            $message_thread_code = $this->crud_model->send_new_private_message();
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            redirect(base_url() . 'parents/message/message_read/' . $message_thread_code, 'location');
        }
        if ($param1 == 'send_reply') 
        {
            $this->crud_model->send_reply_message($param2);
            move_uploaded_file($_FILES["file_name"]["tmp_name"], $subdomain."uploads/messages/" . $_FILES["file_name"]["name"]);
            $this->session->set_flashdata('flash_message' , get_phrase('reply_sent'));
            redirect(base_url() . 'parents/message/message_read/' . $param2, 'location');
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
}