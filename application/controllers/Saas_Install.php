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
	    
        /// Create New School Area  /////////////////////////////////////////////////////
		$schoolxsysname = 'saasschoolx';
		$id = $this->input->post('system_title');
		$newschoolarea = '../'.$id;
		$newuploads    = '../'.$id.'/uploads';
		$newassets     = '../'.$id.'/assets';
		if (!file_exists($newschoolarea))
			{
				if(!mkdir($newschoolarea, 0777, true))
			        die('Failed to create School Work Area...');
		    }
		if (!file_exists($newuploads))
			{
				if (!mkdir($newuploads, 0777, true))
			         die('Failed to create School Upload Area...');
		    }
		if (!file_exists($newassets))
			{
				if (!mkdir($newassets, 0777, true))
			         die('Failed to create School Assets Area...');
		    }	
         
		 
		if (copy('./index.php', $newschoolarea.'/index.php'))
		{
			$reqstring     = "require_once('./config.php'); \n \$system_path = \$_SSS['app']['system_path'];";
		    $reqstring2    = "\$application_folder = \$_SSS['app']['application_path'];";
			$indexdata     = read_file($newschoolarea.'/index.php');
			$indexdata     = str_replace("\$system_path = 'system';", $reqstring , $indexdata);
			$indexdata     = str_replace("\$application_folder = 'application';", $reqstring2 , $indexdata);
			file_put_contents($newschoolarea.'/index.php', $indexdata);
		}
		else
		{
			die('Failed to Copy Index File...');
		}
		
         $newconfigfile = fopen($newschoolarea.'/config.php', 'w');
		 //////////////// Set Config File ////////////////////////////////////////////////
		 $configdata =  " <?php
		              // CORE APP config //
\$_SSS['app']['base_dir'] = \$_SERVER['DOCUMENT_ROOT'];

\$_SSS['app']['tenant_folder'] = '".$id."'; //folder name
\$_SSS['app']['app_folder'] = '".$schoolxsysname."'; //folder name
\$_SSS['app']['asset_folder'] = 'assets'; //folder name with a trailing slash only if there is an asset folder else leave blank

\$_SSS['app']['system_path'] = \$_SSS['app']['base_dir'].'/'.\$_SSS['app']['app_folder'] .'/system/'; //will be sed by the CI index file
\$_SSS['app']['application_path'] = \$_SSS['app']['base_dir'].'/'.\$_SSS['app']['app_folder'] .'/application/';
\$_SSS['app']['base_url'] = 'http://'.\$_SERVER["."SERVER_NAME"."].'/'.\$_SSS['app']['app_folder'];
\$_SSS['app']['tenant_url'] = 'http://'.\$_SERVER["."SERVER_NAME"."].'/'.\$_SSS['app']['tenant_folder'];
///The above config variable are defined to create the basic paths and URLs required for the app to work, which are defined just below


// Main Paths and urls //
if(! defined('APP_TENANTPATH')){
	define('APP_TENANTPATH', \$_SSS['app']['base_dir'].'/'.\$_SSS['app']['tenant_folder'].'/');//this will be used by ci_app config files to load this config file
	define('APP_TENANTURL', \$_SSS['app']['tenant_url']);//this the path that will show on the browser and will be used for accessing the controllers and methods
	define('APP_ASSETURL', \$_SSS['app']['base_url'].'/'.\$_SSS['app']['asset_folder']);//this will be required to access tenant specific assets
	}

// DB config //
\$_SSS['app']['db_host'] = 'localhost';
\$_SSS['app']['db_user'] = 'root';
\$_SSS['app']['db_pass'] = 'ultimate';
\$_SSS['app']['db_name'] = 'newschoolx_".$id."';
\$_SSS['app']['db_driver'] = 'mysqli';
// CI specific params//
\$_SSS['ci']['db_cache'] = FALSE;
//include any other config files here which have configurations in \$_SSS variable
//like email.php etc";
         		 fwrite($newconfigfile, $configdata);
				 
        /////////////////////////////////////////////////////////////////////////////////		
        $data = read_file('./application/config/database.php');
		define('DB_SERVER','localhost');
		define('DB_PORT','3306');
		define('DB_USERNAME','root');
		define('DB_PASSWORD','ultimate');
		define('DB_NAME','newschoolx_'.$this->input->post('system_name'));
        // Connect to database instance (which stores all app DBs)
		$db = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD) or die('Error connect to MySQL: ' . mysql_error());
		 
		// Create DB ($id will have value of client's inserted ID)
		
		mysql_query("CREATE DATABASE IF NOT EXISTS newschoolx_".$id." ",$db) or die(mysql_error());
		$admin_name =  $this->input->post('admin');
		$admin_pass =  $this->input->post('adminpass');
		// Create user and grant all privileges for the above DB
		mysql_query("GRANT ALL ON newschoolx_".$id.".* to  ".$admin_name." identified by '".$admin_pass."'",$db) or die(mysql_error());
        
        //$data = str_replace('dbname',    $this->input->post('database'),    $data);
        //$data = str_replace('dbusername',   $this->input->post('dbusername'),   $data);
        //$data = str_replace('dbpassword',  $this->input->post('dbpassword'),  $data);           
        //$data = str_replace('dbhostname',   $this->input->post('hostname'),   $data);
        //write_file('./application/config/database.php', $data);
        //$data2 = read_file('./application/config/routes.php');
        //$data2 = str_replace('install','login',$data2);
        //write_file('./application/config/routes.php', $data2);
        

        $filename = "uploads/install.sql";
       // mysql_connect($this->input->post('hostname'), $this->input->post('dbusername'), $this->input->post('dbpassword')) or die('Error connect to MySQL: ' . mysql_error());
	   $dbnewname = "newschoolx_"."$id";
        mysql_select_db($dbnewname) or die('Unable to connect MySQL: ' . mysql_error());
		$this->load->database();
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
        'password'  =>  sha1($this->input->post('adminpass')) , 'subdomain' => $this->input->post('system_title')));
          
        $this->db->where('type', 'system_name');
        $this->db->update('settings', array(
            'description' => $this->input->post('system_name')
        ));

        $this->db->where('type', 'system_title');
        $this->db->update('settings', array(
        'description' => $this->input->post('system_title')
          ));

      
        

          $this->db->where('type', 'skin');
        $this->db->update('settings', array(
            'description' => $this->input->post('theme')
        ));

        $this->db->where('type', 'language');
        $this->db->update('settings', array(
            'description' => $this->input->post('language')
        ));

        
          
        $this->db->where('type', 'currency');
        $this->db->update('settings', array(
            'description' => $this->input->post('currency')
        ));
    

        redirect("$id".base_url() , 'refresh');

  }
}


