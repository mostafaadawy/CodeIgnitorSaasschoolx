<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Users_model extends CI_Model {
        function __construct(){
			parent::__construct();
			$this->load->database();
			$this->load->library('session');

		}
  
  
  public function validate_login($email , $password ) 
    {
        $credential = array('username' => $email, 'password' => $password);
		$suc_flag = 0;
        $query = $this->db->get_where('admin', $credential , 1, 0);
        if ($query->num_rows() > 0) 
        {
            $row = $query->row();
            $this->session->set_userdata('admin_login', $row->status);
            $this->session->set_userdata('admin_id', $row->admin_id);
            $this->session->set_userdata('login_user_id', $row->admin_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'admin');
            $suc_flag = 1;
			//exit();
        }
        $query = $this->db->get_where('teacher', $credential , 1, 0);
        if ($query->num_rows() > 0  && $suc_flag == 0) 
        {
            $row = $query->row();
            $this->session->set_userdata('teacher_login', '1');
            $this->session->set_userdata('teacher_id', $row->teacher_id);
            $this->session->set_userdata('login_user_id', $row->teacher_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'teacher');
            $suc_flag = 1;
			//exit();
        }
        $query = $this->db->get_where('student', $credential , 1, 0);
        if ($query->num_rows() > 0 && $suc_flag == 0 ) 
        {
            $row = $query->row();
            $this->session->set_userdata('student_login', $row->student_session);
            $this->session->set_userdata('student_id', $row->student_id);
            $this->session->set_userdata('login_user_id', $row->student_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'student');
            $suc_flag = 1;
			//exit();
        }
        $query = $this->db->get_where('parent', $credential , 1, 0);
        if ($query->num_rows() > 0  && $suc_flag == 0) 
        {
            $row = $query->row();
            $this->session->set_userdata('parent_login', '1');
            $this->session->set_userdata('parent_id', $row->parent_id);
            $this->session->set_userdata('login_user_id', $row->parent_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'parent');
            $suc_flag = 1;
			//exit();
        }
		if ($suc_flag == 1)
		{
			return 'success';
		}
		else
		{
			return 'invalid';
		}
        
		
		
    }
public function validate_username($email = '') 
    {
		$credential = array('username' => $email);
		$query = $this->db->get_where('admin', $credential );
        if ($query->num_rows() > 0) 
        {
            return false;
        }
        $query = $this->db->get_where('teacher', $credential );
        if ($query->num_rows() > 0) 
        {
            return false;
        }
        $query = $this->db->get_where('student', $credential );
        if ($query->num_rows() > 0) 
        {
            return false;
        }
        $query = $this->db->get_where('parent', $credential );
        if ($query->num_rows() > 0) 
        {
            return false;
        }
        return true;
	}		
}
?>