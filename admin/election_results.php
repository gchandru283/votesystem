<?php
	include 'includes/session.php';
	include '../message/message_results.php';

	$return = '../results.php';
	if(isset($_GET['return'])){
		$return = $_GET['return'];
	}

	if(isset($_POST['send'])){
		sendMessages();
		$_SESSION['success'] = 'Messages sent successfully';
		
	}
	else{
		$_SESSION['error'] = "Error sending messages";
	}

	header('location:'.$return);
	exit();
?>