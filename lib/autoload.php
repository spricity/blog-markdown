<?php
define( 'DS' , DIRECTORY_SEPARATOR);
define( 'PS' , PATH_SEPARATOR);
define('BASEDIR', str_replace(DS.'lib','',dirname(__FILE__)));
$script_name = $_SERVER['SCRIPT_NAME'];
$script_name = str_replace('index.php', '', $script_name);
define('BASEURL', 'http://' . $_SERVER['HTTP_HOST'] . $script_name);


/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


include_once('controller.php');
include_once('session.php');
include_once('function.php');

$config = read_file(BASEDIR . DS . 'config.json');
$config = json_decode($config);

if($config->DOC_DIR){
    $path = realpath($config->DOC_DIR);
    define('DIR_DOCS', $path);
}else{
    define('DIR_DOCS', BASEDIR.DS.'docs' );
}

define('SITE', $config->site ? $config->site : '');
