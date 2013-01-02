<?php
	
	include_once('lib/Layroute.php');
	
	$layroute = new Layroute();
	
	$layroute->options = array(
		'data' => true
	);
	
	$layroute->routes = array(
		'bargaz' => 'front',
		'hey' => 'bargad/fish'
	);
	
	$layroute->run();

?>
