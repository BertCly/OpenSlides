<?php
require_once( dirname( dirname( dirname( __FILE__ ))) . '/load.php' );
$statusFile = ABSPATH . "\admin\inc\serverStatus.txt";
$status = file_get_contents($statusFile);
if($status == "0"){
	/* This means, the WebSocket server is not started. So we, start it */
	function execInbg($cmd) { 
		if (substr(php_uname(), 0, 7) == "Windows"){ 
			pclose(popen("start /B ". $cmd, "r"));  
		} else { 
			exec($cmd . " > /dev/null &");   
		} 
	}
	execInbg("php " . ADMIN_HOME . "/inc/bg.php");
	file_put_contents($statusFile, 1);
}
?>