<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$data = explode('.',$_SERVER['SERVER_NAME']); // Get the sub-domain here
 
// Add some sanitization for $data
if(!empty($data[0]) && $data[0] == 'www'  )
{
   $subdomain = strtolower($data[1]);
}
else
{
$subdomain = strtolower($data[0]);
}

define('DB_SERVER','localhost');
 define('DB_PORT','3306');
 define('DB_NAME','schoolx_maindb');
 define('DB_USERNAME','schoolx_dbadmin');
 define('DB_PASSWORD','GaintHero83!');
 // Connect to database instance (which stores all app DBs)
 $dbfirst = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD) or die('Error connect to MySQL: ' . mysql_error());
 $maindb = "schoolx_maindb";
 mysql_select_db($maindb) or die('Unable to connect MySQL: ' . mysql_error());
 $query   = 'SELECT * FROM clients WHERE client_subdomain="'.$subdomain.'"';
 $result1 = mysql_query($query, $dbfirst) or die (mysql_error().$query);
 $row = mysql_fetch_row($result1);
 $count   = mysql_num_rows($row);
 $dbuname =  $row[3];
 $dbpass  =  $row[4];
 mysql_close($dbfirst);

if (!empty($subdomain) && $subdomain != 'schoolxapp'  && $subdomain != 'erp') {

			$active_group = 'default';
			$query_builder = TRUE;
			$db['default'] = array(
			'dsn'	=> '',
			'hostname' => 'localhost', 
			'username' => $dbuname,
			'password' => $dbpass,
			'database' => 'schoolx_maindb_'.$subdomain,
			'dbdriver' => 'mysqli',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => TRUE,
			'autoinit' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => '',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt' => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => array(),
			'save_queries' => TRUE
		                           );
}
else
{
$active_group = 'default';
$query_builder = TRUE;
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost', 
	'username' => 'schoolx_dbadmin',
	'password' => 'GaintHero83!',
	'database' => 'schoolx_maindb',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'autoinit' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
}

