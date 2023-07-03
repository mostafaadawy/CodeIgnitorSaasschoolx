<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Install extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();  
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->helper('file');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    }
  
  function index()
  {
    $this->load->view('install/index');
  }

  function setup()
  {
    $db_verify  = $this->server_connection();
    $purchase_verify    = $this->verify_purchase();
    if($purchase_verify == true && $db_verify == true)
    {
        $data = read_file('./application/config/database.php');
        $data = str_replace('dbname',    $this->input->post('database'),    $data);
        $data = str_replace('dbusername',   $this->input->post('dbusername'),   $data);
        $data = str_replace('dbpassword',  $this->input->post('dbpassword'),  $data);           
        $data = str_replace('dbhostname',   $this->input->post('hostname'),   $data);
        write_file('./application/config/database.php', $data);
        $data2 = read_file('./application/config/routes.php');
        $data2 = str_replace('install','login',$data2);
        write_file('./application/config/routes.php', $data2);
        $this->load->database();

        $filename = "uploads/install.sql";
        mysql_connect($this->input->post('hostname'), $this->input->post('dbusername'), $this->input->post('dbpassword')) or die('Error connect to MySQL: ' . mysql_error());
        mysql_select_db($this->input->post('database')) or die('Unable to connect MySQL: ' . mysql_error());
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line)
        {
                if (substr($line, 0, 2) == '--' || $line == '')
                {
                    continue;
                }
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';')
                {
                    mysql_query($templine) or print('Insert error \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                    $templine = '';
                if (mysql_errno() == 1062) 
                {
                    print 'no way!';
                }
            }
        }

    $url1 = $_SERVER["REQUEST_URI"];
    $url2 = str_replace("/crear.php", "", $url1);
    $url3 = str_replace("/","",$url2);
    $final = str_replace("index.php?installsetup", "", $url3);

$htaccess= "<IfModule mod_rewrite.c> 
    RewriteEngine On
    RewriteBase /$final
    RewriteCond %{REQUEST_URI} ^system.*
    RewriteRule ^(.*)$ /index.php?/$1 [L]
    RewriteCond %{REQUEST_URI} ^application.*
    RewriteRule ^(.*)$ /index.php?/$1 [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L]
</IfModule> ";

        file_put_contents(".htaccess", $htaccess);
  
        $this->db->where('admin_id' , 1);
        $this->db->update('admin' , array('username'  =>  $this->input->post('admin'),
        'password'  =>  sha1($this->input->post('adminpass'))));
          
        $this->db->where('type', 'system_name');
        $this->db->update('settings', array(
            'description' => $this->input->post('system_name')
        ));

        $this->db->where('type', 'system_title');
        $this->db->update('settings', array(
        'description' => $this->input->post('system_title')
          ));
      
        $this->db->where('type', 'buyer');
        $this->db->update('settings', array(
            'description' => $this->input->post('code_username')
        ));

          $this->db->where('type', 'skin');
        $this->db->update('settings', array(
            'description' => $this->input->post('theme')
        ));

        $this->db->where('type', 'language');
        $this->db->update('settings', array(
            'description' => $this->input->post('language')
        ));

        $this->db->where('type', 'purchase_code');
        $this->db->update('settings', array(
            'description' => $this->input->post('purchase_code')
        ));
          
        $this->db->where('type', 'currency');
        $this->db->update('settings', array(
            'description' => $this->input->post('currency')
        ));
    
        $purchase_code = $this->input->post('purchase_code');
        $domain = base_url();
        $ipaddress = $_SERVER['SERVER_ADDR'];
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, "http://educaby.com/remote/index.php?install/remote_install/");  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, "code=$purchase_code&domain=$domain&ip=$ipaddress");  
        curl_exec($ch);  
        curl_close($ch);   

        redirect(base_url() , 'refresh');
    }
    else 
    {
      redirect(base_url(),'refresh');
    }
  }

  function server_connection()
  {
    $link = @mysql_connect($this->input->post('hostname'),
    $this->input->post('dbusername'),
    $this->input->post('dbpassword'));
    if(!$link)
    {
      @mysql_close($link);
      return false;
    }
    $db_selected  = mysql_select_db($this->input->post('database'), $link);
    if (!$db_selected)
    {
      @mysql_close($link);
      return false;
    }
    @mysql_close($link);
    return true;
  }

  function verify_purchase()
  {
      $file = APPPATH.'config/purchase'.EXT;
      @chmod($file, FILE_WRITE_MODE);
      $purchase_code = $this->input->post('purchase_code');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_USERAGENT, 'API');
      curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/guateapps/e7u9o96ln3ai8sgy563t57csiio0syy8/verify-purchase:". $purchase_code .".json");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $purchase_data = json_decode(curl_exec($ch), true);
      if(!count($purchase_data['verify-purchase'])) {
      return TRUE;
    } else {
      return TRUE;
    }
  } 
}