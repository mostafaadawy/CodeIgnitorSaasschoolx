<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Loading extends CI_Controller 
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
	$this->load->view('install/loading');
	
  }

  function setup()
  {

        $this->load->view('install/loading');
	     define('DB_SERVER','localhost');
		define('DB_PORT','3306');
        define('DB_NAME','schoolx_maindb');
		define('DB_USERNAME','schoolx_dbadmin');
		define('DB_PASSWORD','GaintHero83!');
        // Connect to database instance (which stores all app DBs)
		$db = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD) or die('Error connect to MySQL: ' . mysql_error());
               
		$admin_name = ($_POST["admin"]);
		$admin_pass = ($_POST["adminpass"]);
		$passhash = sha1($_POST["adminpass"]);
		$systitle = ($_POST["system_title"]);
		$sysname = strtolower($_POST["system_name"]);
		$theme = ($_POST["theme"]);
		$lang = ($_POST["language"]);
		$currency = ($_POST["currency"]);
		$authtoken = ($_POST["authtoken"]);
		
		
		/////////////////////////////  Insert New School Information in Main SchoolX Database  ///////////////////////
	  $maindb = "schoolx_maindb";
	   mysql_select_db($maindb) or die('Unable to connect MySQL: ' . mysql_error());
	   ///======================================================================////
	   /*for ($x = 0; $x <= 100000; $x++) 
	    {
			$randomstr = RandomString();
			$stringhash = sha1($randomstr);
			$query   = 'SELECT * FROM auth_tokens WHERE token="'.$stringhash.'" AND valid=1';
			   $result1 = mysql_query($query, $db) or die (mysql_error().$query);
			   $count   = mysql_num_rows($result1);
			   if($count == 0)
			   {
				 $query = 'INSERT IGNORE INTO auth_tokens (token, valid) VALUES ("'.$stringhash.'",1)';
	             $retval = mysql_query( $query, $db);  
			   }
			//$query = 'INSERT IGNORE INTO auth_tokens (token, valid) VALUES ("'.$stringhash.'",1)';
	        //$retval = mysql_query( $query, $db);
		} 
	   die();*/
	   ///======================================================================////
	   $query   = 'SELECT * FROM auth_tokens WHERE token="'.$authtoken.'" AND valid=1';
	   $result1 = mysql_query($query, $db) or die (mysql_error().$query);
	   $count   = mysql_num_rows($result1);
	   if($count == 0)
	   {
		 die( 'Sorry! You have entered an invalid authorization token. Please contact us at info@schoolxapp.com to get a valid authorization token');  
	   }
	   else
	   {
		   $query = 'UPDATE auth_tokens SET valid=0 WHERE token="'.$authtoken.'"';
	       $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not invaidate token: ' . mysql_error());
		   }
		   
		   $query = 'UPDATE auth_tokens SET school="'.$systitle.'" WHERE token="'.$authtoken.'"';
	       $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update School Name: ' . mysql_error());
		   }
	   }
	   $query   = 'SELECT * FROM clients WHERE client_subdomain="'.$sysname.'"';
       $result1 = mysql_query($query, $db) or die (mysql_error().$query);    
	   $count   = mysql_num_rows($result1);
		if ($count>0) 
		{
		die( 'Sorry! This Subdomain is already Taken! Please Try Again Using A different Subdomain Name.');
		} 
		else
		{
		
	     $query = 'INSERT IGNORE INTO clients (client_name, client_subdomain, client_admin, client_password) VALUES ("'.$systitle.'","'.$sysname.'","'.$admin_name.'","'.$admin_pass.'")';
	     $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update data in Main Database Table table: ' . mysql_error());
		   }	
			
		}
	   
	    
        ////////////////////////////////////////////// Create New School Area  /////////////////////////////////////////////////////
		$id = $sysname;	
		$newuploads    = $id.'uploads';
		$source        = 'uploads';
		if (!file_exists($newuploads))
			{
				if (!mkdir($newuploads, 0755, true))
			         die('Failed to create School Upload Area...');
		    }
		foreach (
		         $iterator = new \RecursiveIteratorIterator(
				  new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
				  \RecursiveIteratorIterator::SELF_FIRST) as $item
				) {
				  if ($item->isDir()) {
					mkdir($newuploads . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				  } else {
					copy($item, $newuploads . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				  }
				}	
			
		
		 
		// Create DB ($id will have value of client's inserted ID)
		
		mysql_query("CREATE DATABASE IF NOT EXISTS schoolx_maindb_".$id." ",$db) or die(mysql_error());
		
		// Create user and grant all privileges for the above DB
		mysql_query("GRANT ALL ON schoolx_maindb_".$id.".* to  ".$admin_name." identified by '".$admin_pass."'",$db) or die(mysql_error());    
        $filename = "uploads/install.sql";
	   
       //////////////////////////////////////////////////////////////////////////////
	 $dbnewname = "schoolx_maindb_"."$id";
        mysql_select_db($dbnewname) or die('Unable to connect MySQL: ' . mysql_error());
        $templine = '';
       // $lines_1 = file($filename);
		//$lines_2 = str_replace("CREATE TABLE IF NOT EXISTS `","CREATE TABLE IF NOT EXISTS `"."$id"."_",$lines_1);
		//$lines_3   = str_replace("INSERT IGNORE INTO `","INSERT IGNORE INTO `"."$id"."_",$lines_2);
		//$lines   = str_replace("ALTER IGNORE TABLE `","ALTER IGNORE TABLE `"."$id"."_",$lines_3);
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

	   
	   $query = 'UPDATE admin SET subdomain="'.$sysname.'" WHERE admin_id=1';
	   $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update data in admin table: ' . mysql_error());
		   }
	   
       $query = 'UPDATE admin SET username="'.$admin_name.'", password="'.$passhash.'", subdomain="'.$sysname.'" WHERE admin_id=2';
	   $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update data in admin table: ' . mysql_error());
		   }
	   
	   $query = 'UPDATE settings SET description="'.$sysname.'" WHERE type="system_name"';
	   $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update data in settings table: '.mysql_error());
		   }
	   
	   
	  $query = 'UPDATE  settings SET description="'.$systitle.'" WHERE type="system_title"';
	   $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update system_title: '.mysql_error());
		   }
		   
	   $query = 'UPDATE  settings SET description="'.$theme.'" WHERE type="skin"';
	   $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update skin: '.mysql_error());
		   }
	   $query = 'UPDATE  settings SET description="'.$lang.'" WHERE type="language"';
	   $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update language: '.mysql_error());
		   }
       $query = 'UPDATE  settings SET description="'.$currency.'" WHERE type="currency"';
	   $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update currency: '.mysql_error());
		   }
	    $query = 'INSERT IGNORE INTO clients (client_name, client_subdomain, client_admin, client_password) VALUES ("'.$systitle.'","'.$sysname.'","'.$admin_name.'","'.$admin_pass.'")';
	     $retval = mysql_query( $query, $db);
		   if(! $retval ) {
			  die('Could not update data in Clients Database Table table: ' . mysql_error());
		   }		   
		   
       
         header("Location: https://".$id.".schoolxapp.com");
         die();

  } 
  function RandomString()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
        $randstring = $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}


}
