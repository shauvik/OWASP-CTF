<?php
session_start();

$ifconfig = shell_exec("/sbin/ifconfig|grep 'inet addr'|grep -v 127.0.0.1|cut -d: -f2|awk '{print $1}'");
$_REAL_SCRIPT_DIR = realpath(dirname($_SERVER['SCRIPT_FILENAME'])); // filesystem path of this page's directory (page.php)
$_REAL_BASE_DIR = realpath(dirname(__FILE__)); // filesystem path of this file's directory (config.php)
$_MY_PATH_PART = substr( $_REAL_SCRIPT_DIR, strlen($_REAL_BASE_DIR)); // just the subfolder part between <installation_path> and the page
$INSTALLATION_PATH = $_MY_PATH_PART? substr( dirname($_SERVER['SCRIPT_NAME']), 0, -strlen($_MY_PATH_PART) ): dirname($_SERVER['SCRIPT_NAME']); // we subtract the subfolder part from the end of <installation_path>, leaving us with just <installation_path> :)
 $ignoreThesePlayers=array('<if you dont want a name in the scoreboard, add here is the list to ignore>');

define ('WEBROOT','/');
define ('SERVER_IP',$ifconfig);
define('HOST','localhost');
define('DB_USER','ctf');
define('DB_PASSWORD','T9MfKYxBGxsuK8qL');
define('DB_NAME','ctf');
define('CONFIGDIR',dirname(__FILE__));
define('CHALLENGES_DIR','challenges');
define('DICTIONARY',CONFIGDIR."/words.txt");

//loadAll("framework");
loadFramework();
loadAll("lib/geshi");


function loadFramework() {
	$directory = dirname(__FILE__) . "/../framework";
	
	require_once "$directory/mysql.class.php";
	require_once "$directory/util.class.php";
	require_once "$directory/CTF.class.php";
	require_once "$directory/Challenge.class.php";
	require_once "$directory/Vigenere.php";
	require_once "$directory/encode.class.php";
	require_once "$directory/Encoder.class.php";
	require_once "$directory/ADFGVXEncoder.class.php";
	require_once "$directory/BifidEncoder.class.php";
	require_once "$directory/VigenereEncoder.class.php";
	require_once "$directory/BeaufortEncoder.class.php";
	require_once "$directory/NihilistEncoder.class.php";
	require_once "$directory/PolluxEncoder.class.php";
	require_once "$directory/RailFenceEncoder.class.php";
}
function loadAll($directory) {
	$directory = dirname(__FILE__) . "/../$directory";
	foreach (glob("$directory/*.php") as $filename) {
		require_once $filename;
	}
}
$BASE_ARRAY=array('webroot'=>WEBROOT,'serverIP'=>SERVER_IP);
?>
