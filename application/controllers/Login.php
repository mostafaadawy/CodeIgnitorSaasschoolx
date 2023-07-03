<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
		$this->load->model('users_model');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }

    public function index() 
    {
        if ($this->session->userdata('admin_login') == 1)
        {
            redirect(base_url() . 'admin/panel/', 'refresh');
        }
        else if ($this->session->userdata('teacher_login') == 1)
        {
            redirect(base_url() . 'teacher/teacher_dashboard/', 'refresh');
        }
       else if ($this->session->userdata('student_login') == 1)
        {
            redirect(base_url() . 'student/panel/', 'refresh');
        }
        else if ($this->session->userdata('parent_login') == 1)
        {
            redirect(base_url() . 'parents/panel/', 'refresh');
        }
        else 
		{
			$this->load->view('backend/login');
		}
    }

     function lost_password($param1 = '', $param2 = '')
    {
        if($param1 == 'recovery')
        {
		    $email 	= $_POST["field"];
		    $reset_account_type	= '';
		    $new_password =	substr( md5( rand(100000000,20000000000) ) , 0,7);
		    $new_hashed_password	=	sha1($new_password);

            $query = $this->db->get_where('admin' , array('email' => $email));
            if ($query->num_rows() > 0) 
            {
                $this->db->where('email' , $email);
                $this->db->update('admin' , array('password' =>     $new_hashed_password));
                $this->crud_model->lost_password($email , $new_password);
            }
		    
            $query = $this->db->get_where('teacher' , array('email' => $email));
            if ($query->num_rows() > 0) 
            {
        	    $this->db->where('email' , $email);
        	    $this->db->update('teacher' , array('password' => $new_hashed_password));

                $this->crud_model->lost_password($email , $new_password);
            }
		    
            $query = $this->db->get_where('parent' , array('email' => $email));
            if ($query->num_rows() > 0) 
            {
        	    $this->db->where('email' , $email);
        	    $this->db->update('parent' , array('password' => $new_hashed_password));
                $this->crud_model->lost_password($email , $new_password);
            }
            
            $query = $this->db->get_where('student' , array('email' => $email));
            if ($query->num_rows() > 0) 
            {
        	    $this->db->where('email' , $email);
        	    $this->db->update('student' , array('password' => $new_hashed_password));
                $this->crud_model->lost_password($email , $new_password);
            }
            $this->session->set_flashdata('flash_message' , get_phrase('success'));
            redirect(base_url(), 'refresh'); 
        }
        $this->load->view('backend/lost');
    }

    function ajax_login() 
    {
		$response = array();
        $email = $_POST["email"];
        $password = sha1($_POST["password"]);
        $response['submitted_data'] = $_POST;
        $login_status = $this->validate_login($email, $password);
        $response['login_status'] = $login_status;
        if ($login_status == 'success') 
        {
            $response['redirect_url'] = '';
		}
        echo json_encode($response,true);
		
    }
	
	
	
	
	 

     function validate_login($email = '', $password = '') 
    {
        $credential = array('username' => $email, 'password' => $password);
        $query = $this->db->get_where('admin', $credential , 1, 0);
        if ($query->num_rows() > 0) 
        {
            $row = $query->row();
            $this->session->set_userdata('admin_login', $row->status);
            $this->session->set_userdata('admin_id', $row->admin_id);
            $this->session->set_userdata('login_user_id', $row->admin_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'admin');
            return 'success';
        }
        $query = $this->db->get_where('teacher', $credential , 1, 0);
        if ($query->num_rows() > 0) 
        {
            $row = $query->row();
            $this->session->set_userdata('teacher_login', '1');
            $this->session->set_userdata('teacher_id', $row->teacher_id);
            $this->session->set_userdata('login_user_id', $row->teacher_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'teacher');
            return 'success';
        }
        $query = $this->db->get_where('student', $credential , 1, 0);
        if ($query->num_rows() > 0) 
        {
            $row = $query->row();
            $this->session->set_userdata('student_login', $row->student_session);
            $this->session->set_userdata('student_id', $row->student_id);
            $this->session->set_userdata('login_user_id', $row->student_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'student');
            return 'success';
        }
        $query = $this->db->get_where('parent', $credential , 1, 0);
        if ($query->num_rows() > 0) 
        {
            $row = $query->row();
            $this->session->set_userdata('parent_login', '1');
            $this->session->set_userdata('parent_id', $row->parent_id);
            $this->session->set_userdata('login_user_id', $row->parent_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'parent');
            return 'success';
        }
        return 'invalid';
		
    }

    function four_zero_four() 
    {
        $this->load->view('four_zero_four');
    }
    
    function logout() 
    {  
	    $subdomain = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
	    if ($this->session->userdata('admin_login') == 1)
        {
            $this->session->unset_userdata('admin_login');
            $this->session->unset_userdata('admin_id');
            
        }
        if ($this->session->userdata('teacher_login') == 1)
        {
            $this->session->unset_userdata('teacher_login');
			$this->session->unset_userdata('teacher_id');
        }
        if ($this->session->userdata('student_login') == 1)
        {
			$this->session->unset_userdata('student_login');
            $this->session->unset_userdata('student_id');

        }
        if ($this->session->userdata('parent_login') == 1)
        {
            $this->session->unset_userdata('parent_login');
            $this->session->unset_userdata('parent_id');
			
        }
		$this->session->unset_userdata('login_type');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('login_user_id');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url(), 'refresh');
		//header("Location: http://".$subdomain.".schoolx.com");
		exit();
    }
}