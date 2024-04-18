<?php
	include 'includes/session.php';
	include '../message/message.php';

	$return = 'home.php';
	if(isset($_GET['return'])){
		$return = $_GET['return'];
	}

	if(isset($_POST['send'])){
		$msg = sendMessages();
		$_SESSION['success'] = $msg;
		
	}
	else{
		$_SESSION['error'] = "Error sending messages";
	}

	header('location: '.$return);

?>