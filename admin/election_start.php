<?php
	include 'includes/session.php';
	include '../message/message.php'; ?>

<?php

	$return = 'home.php';
	if(isset($_GET['return'])){
		$return = $_GET['return'];
	}

	if(isset($_POST['start'])){
		$file = 'electionStatus.ini';
		$content = 'isElectionEnded = false';
		file_put_contents($file, $content);
		$msg = sendMessages();
		$_SESSION['success'] = $msg;
	}
		
	else{
		$_SESSION['error'] = "Error sending messages";
	}

	header('location: '.$return);

?>