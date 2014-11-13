<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
require_once( dirname( dirname( dirname( __FILE__ ))) . '/load.php' );
function shutdown(){
	file_put_contents(ABSPATH."/admin/inc/serverStatus.txt", "0");
	require_once ABSPATH."/admin/inc/startServer.php";
}
register_shutdown_function('shutdown');
if( isset($startNow) ){
	require_once ADMIN_HOME."/inc/vendor/autoload.php";
	require_once ADMIN_HOME."/inc/class.chat.php";
	$server = IoServer::factory(
		new ChatServer(),
        8080,
		"127.0.0.1"
	);
	$server->run();
}
?>